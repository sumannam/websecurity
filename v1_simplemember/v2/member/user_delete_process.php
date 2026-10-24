<?php
// SimpleBoard/v2/member/user_delete_process.php
require_once __DIR__ . '/../db.php';

// 1. 로그인 여부 확인
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href = '../login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: user_delete.php");
    exit;
}

// 2. 현재 로그인된 사용자의 ID 수신 (타인 계정 임의 삭제 방지)
$user_id = $_SESSION['user_id'];
$pw = $_POST['pw'] ?? '';

if (empty($pw)) {
    echo "<script>alert('비밀번호를 입력해 주세요.'); history.back();</script>";
    exit;
}

// 3. 비밀번호 확인
$sql = "SELECT pw FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || $user['pw'] !== $pw) {
    echo "<script>alert('비밀번호가 일치하지 않습니다.'); history.back();</script>";
    exit;
}

// 4. 회원 레코드 삭제 (ON DELETE CASCADE로 작성한 게시글도 자동 삭제됨)
$delete_sql = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($delete_sql);
$stmt->bind_param("s", $user_id);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();

    // 5. 탈퇴 완료 후 세션 초기화 및 파기
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();

    echo "<script>
        alert('회원 탈퇴 처리가 완료되었습니다. 이용해 주셔서 감사합니다.');
        location.href = '../login.php';
    </script>";
    exit;
} else {
    echo "<script>
        alert('탈퇴 처리 중 오류가 발생했습니다: " . addslashes($stmt->error) . "');
        history.back();
    </script>";
    $stmt->close();
    $conn->close();
    exit;
}
?>