<?php
// SimpleBoard/v2/login_process.php
require_once __DIR__ . '/db.php';

// POST 요청 확인
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

// 1. 전달받은 로그인 파라미터 수신 및 공백 정리
$id = trim($_POST['id'] ?? '');
$pw = $_POST['pw'] ?? '';

// 2. 필수 값 입력 여부 확인
if (empty($id) || empty($pw)) {
    echo "<script>alert('아이디와 비밀번호를 모두 입력해 주세요.'); history.back();</script>";
    exit;
}

// 3. 사용자 조회 쿼리 실행 (Prepared Statement)
$sql = "SELECT id, pw, name, role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    // 4. 비밀번호 일치 확인 (실습 환경: 평문 비교)
    if ($pw === $user['pw']) {
        // 세션 정보 등록
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role']      = $user['role'];

        $stmt->close();
        $conn->close();

        // 5. 권한에 따른 분기 이동
        if ($user['role'] === 'ADMIN') {
            header("Location: admin/manage_users.php");
        } 
        else {
            header("Location: board/board_list.php");
        }
        exit;
    }
}

// 인증 실패 시 처리
$stmt->close();
$conn->close();

echo "<script>
    alert('아이디 또는 비밀번호가 일치하지 않습니다.');
    history.back();
</script>";
exit;
?>
