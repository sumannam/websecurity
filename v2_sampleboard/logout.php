<?php
// SimpleBoard/v2/logout.php
require_once __DIR__ . '/db.php';

// 1. 모든 세션 변수 초기화
$_SESSION = [];

// 2. 세션 쿠키가 사용 중인 경우 쿠키 삭제
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. 서버 측 세션 파기
session_destroy();

// 4. 알림 메시지 출력 후 로그인 화면으로 이동
echo "<script>
    alert('정상적으로 로그아웃되었습니다.');
    location.href = 'login.php';
</script>";
exit;
?>