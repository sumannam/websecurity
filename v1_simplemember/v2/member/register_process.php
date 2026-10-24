<?php
// SimpleBoard/v2/member/register_process.php
require_once __DIR__ . '/../db.php';

// POST 요청 확인
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.php");
    exit;
}

// 1. 전송된 폼 데이터 수신 및 공백 제거
$id = trim($_POST['id'] ?? '');
$pw = $_POST['pw'] ?? '';
$pw_confirm = $_POST['pw_confirm'] ?? '';
$name = trim($_POST['name'] ?? '');
$cellphone = trim($_POST['cellphone'] ?? '');
$email = trim($_POST['email'] ?? '');

// 2. 기본 입력값 유효성 검증
if (empty($id) || empty($pw) || empty($name)) {
    echo "<script>alert('필수 입력 항목(아이디, 비밀번호, 이름)을 모두 입력해 주세요.'); history.back();</script>";
    exit;
}

if ($pw !== $pw_confirm) {
    echo "<script>alert('비밀번호와 비밀번호 확인이 일치하지 않습니다.'); history.back();</script>";
    exit;
}

// 3. 아이디 중복 확인
$check_sql = "SELECT id FROM users WHERE id = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('이미 사용 중인 아이디입니다.'); history.back();</script>";
    $stmt->close();
    exit;
}
$stmt->close();

// 4. 회원 데이터 등록 (기본 권한: USER)
$insert_sql = "INSERT INTO users (id, pw, name, cellphone, email, role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 'USER', NOW(), NOW())";
$stmt = $conn->prepare($insert_sql);
$stmt->bind_param("sssss", $id, $pw, $name, $cellphone, $email);

if ($stmt->execute()) {
    echo "<script>
        alert('회원가입이 성공적으로 완료되었습니다.');
        location.href = '../login.php';
    </script>";
} else {
    echo "<script>
        alert('회원가입 처리 중 오류가 발생했습니다: " . addslashes($stmt->error) . "');
        history.back();
    </script>";
}

$stmt->close();
$conn->close();
?>