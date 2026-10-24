<?php
// SimpleBoard/v2/member/register_process.php
require_once __DIR__ . '/../db.php';

// POST 요청 여부 확인
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

// 1. 입력 데이터 수신 및 공백 제거
$id               = trim($_POST['id'] ?? '');
$password         = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';
$name             = trim($_POST['name'] ?? '');
$cellphone        = trim($_POST['cellphone'] ?? '');
$email            = trim($_POST['email'] ?? '');

// 2. 필수 입력값 및 유효성 검증
if (empty($id) || empty($password) || empty($password_confirm) || empty($name)) {
    echo "<script>alert('필수 항목을 모두 입력해주세요.'); history.back();</script>";
    exit;
}

// 비밀번호 일치 확인
if ($password !== $password_confirm) {
    echo "<script>alert('비밀번호와 비밀번호 확인이 일치하지 않습니다.'); history.back();</script>";
    exit;
}

// 아이디 형식 검증 (영문 대소문자, 숫자 4~20자)
if (!preg_match('/^[a-zA-Z0-9]{4,20}$/', $id)) {
    echo "<script>alert('아이디는 영문 및 숫자 조합 4~20자리여야 합니다.'); history.back();</script>";
    exit;
}

// 이메일 형식 검증 (입력된 경우에만)
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('올바른 이메일 형식이 아닙니다.'); history.back();</script>";
    exit;
}

// 3. 아이디 중복 체크
$check_sql = "SELECT id FROM users WHERE id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo "<script>alert('이미 사용 중인 아이디입니다.'); history.back();</script>";
    $check_stmt->close();
    exit;
}
$check_stmt->close();

// 4. 비밀번호 단방향 암호화 (Bcrypt/Argon2 등 기본 권장 해시 알고리즘 사용)
//$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 5. 신규 회원 DB 등록
$insert_sql = "INSERT INTO users (id, pw, name, cellphone, email, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
$insert_stmt = $conn->prepare($insert_sql);

// 테이블에 created_at 컬럼이 없을 경우:
//$insert_sql = "INSERT INTO users (id, password, name, cellphone, email) VALUES (?, ?, ?, ?, ?)";
//$insert_stmt->bind_param("sssss", $id, $hashed_password, $name, $cellphone, $email);

if ($insert_stmt) {
    $insert_stmt->bind_param("sssss", $id, $password, $name, $cellphone, $email);
    
    if ($insert_stmt->execute()) {
        echo "<script>
            alert('회원가입이 완료되었습니다. 로그인 페이지로 이동합니다.');
            location.href = '../login.php';
        </script>";
    } else {
        echo "<script>
            alert('회원가입 처리 중 오류가 발생했습니다. 다시 시도해주세요.');
            history.back();
        </script>";
    }
    
    $insert_stmt->close();
} else {
    echo "<script>alert('데이터베이스 쿼리 준비에 실패했습니다.'); history.back();</script>";
}

$conn->close();
?>