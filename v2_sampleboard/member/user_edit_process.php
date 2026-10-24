<?php
// SimpleBoard/v2/member/user_edit_process.php
require_once __DIR__ . '/../db.php';

// 1. 로그인 여부 확인 및 POST 요청 체크
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href = '../login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: user_edit.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// 2. 파라미터 수신 및 공백 제거
$name = trim($_POST['name'] ?? '');
$cellphone = trim($_POST['cellphone'] ?? '');
$email = trim($_POST['email'] ?? '');
$new_pw = $_POST['new_pw'] ?? '';
$new_pw_confirm = $_POST['new_pw_confirm'] ?? '';
$current_pw = $_POST['current_pw'] ?? '';

// 필수 값 검증
if (empty($name) || empty($current_pw)) {
    echo "<script>alert('이름과 현재 비밀번호는 필수 입력 항목입니다.'); history.back();</script>";
    exit;
}

if (!empty($new_pw) && ($new_pw !== $new_pw_confirm)) {
    echo "<script>alert('새 비밀번호와 확인이 일치하지 않습니다.'); history.back();</script>";
    exit;
}

// 3. 현재 비밀번호 검증
$sql = "SELECT pw FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || $user['pw'] !== $current_pw) {
    echo "<script>alert('현재 비밀번호가 일치하지 않습니다.'); history.back();</script>";
    exit;
}

// 4. 정보 업데이트 (새 비밀번호 입력 여부에 따라 분기)
if (!empty($new_pw)) {
    // 비밀번호도 함께 변경
    $update_sql = "UPDATE users SET pw = ?, name = ?, cellphone = ?, email = ?, updated_at = NOW() WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssss", $new_pw, $name, $cellphone, $email, $user_id);
} else {
    // 비밀번호 제외하고 변경
    $update_sql = "UPDATE users SET name = ?, cellphone = ?, email = ?, updated_at = NOW() WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssss", $name, $cellphone, $email, $user_id);
}

if ($update_stmt->execute()) {
    // 변경된 이름 세션에 갱신
    $_SESSION['user_name'] = $name;

    echo "<script>
        alert('회원 정보가 성공적으로 수정되었습니다.');
        location.href = '../board/board_list.php';
    </script>";
} else {
    echo "<script>
        alert('회원 정보 수정 중 오류가 발생했습니다: " . addslashes($update_stmt->error) . "');
        history.back();
    </script>";
}

$update_stmt->close();
$conn->close();
?>