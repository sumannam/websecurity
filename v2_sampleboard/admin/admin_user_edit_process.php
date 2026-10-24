<?php
// SimpleBoard/v2/admin/admin_user_edit_process.php
require_once __DIR__ . '/../db.php';

// 1. 관리자 권한 확인
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'ADMIN') {
    echo "<script>alert('관리자 권한이 필요합니다.'); location.href = '../login.php';</script>";
    exit;
}

// 2. POST 요청 확인
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: manage_users.php");
    exit;
}

// 3. 파라미터 수신 및 공백 정리
$target_id = trim($_POST['id'] ?? '');
$name = trim($_POST['name'] ?? '');
$cellphone = trim($_POST['cellphone'] ?? '');
$email = trim($_POST['email'] ?? '');
$role = trim($_POST['role'] ?? 'USER');
$new_pw = $_POST['new_pw'] ?? '';

// 필수 항목 검증
if (empty($target_id) || empty($name)) {
    echo "<script>alert('아이디와 이름은 필수 항목입니다.'); history.back();</script>";
    exit;
}

// 권한 값 유효성 체크
if ($role !== 'ADMIN' && $role !== 'USER') {
    $role = 'USER';
}

// 4. 회원 정보 갱신 (비밀번호 입력 여부에 따른 분기)
if (!empty($new_pw)) {
    // 비밀번호 강제 변경 포함
    $sql = "UPDATE users 
            SET name = ?, cellphone = ?, email = ?, role = ?, pw = ?, updated_at = NOW() 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $cellphone, $email, $role, $new_pw, $target_id);
} else {
    // 비밀번호는 유지하고 나머지 정보만 변경
    $sql = "UPDATE users 
            SET name = ?, cellphone = ?, email = ?, role = ?, updated_at = NOW() 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $cellphone, $email, $role, $target_id);
}

// 5. 쿼리 실행 및 결과 처리
if ($stmt->execute()) {
    $stmt->close();
    $conn->close();

    echo "<script>
        alert('회원 [ " . addslashes($target_id) . " ] 님의 정보가 성공적으로 수정되었습니다.');
        location.href = 'manage_users.php';
    </script>";
    exit;
} else {
    echo "<script>
        alert('회원 정보 수정 중 오류가 발생했습니다: " . addslashes($stmt->error) . "');
        history.back();
    </script>";
    $stmt->close();
    $conn->close();
    exit;
}
?>