<?php
// SimpleBoard/v2/login.php
require_once __DIR__ . '/db.php';

// 이미 로그인된 상태라면 권한에 따라 메인 페이지로 이동
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN') {
        header("Location: admin/user_list.php");
    } else {
        header("Location: board/board_list.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>로그인</title>
    <style>
        body { font-family: sans-serif; margin: 40px; background-color: #f5f5f5; }
        .login-container { width: 340px; margin: 80px auto; padding: 25px; background: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); }
        h2 { text-align: center; margin-top: 0; margin-bottom: 20px; color: #333; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px; color: #555; }
        .form-group input { width: 100%; padding: 9px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        .btn-login { width: 100%; padding: 10px; background-color: #337ab7; color: white; border: none; border-radius: 4px; font-size: 15px; font-weight: bold; cursor: pointer; margin-top: 5px; }
        .btn-login:hover { background-color: #286090; }
        .footer-links { display: flex; justify-content: space-between; margin-top: 15px; font-size: 13px; }
        .footer-links a { color: #555; text-decoration: none; }
        .footer-links a:hover { color: #337ab7; text-decoration: underline; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>SimpleBoard 로그인</h2>
    
    <form action="login_process.php" method="POST">
        <div class="form-group">
            <label for="id">아이디</label>
            <input type="text" id="id" name="id" placeholder="아이디를 입력하세요" required autofocus>
        </div>

        <div class="form-group">
            <label for="pw">비밀번호</label>
            <input type="password" id="pw" name="pw" placeholder="비밀번호를 입력하세요" required>
        </div>

        <button type="submit" class="btn-login">로그인</button>
    </form>

    <div class="footer-links">
        <span>계정이 없으신가요?</span>
        <a href="member/register.php">회원가입</a>
    </div>
</div>

</body>
</html>