<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원가입 실습 v0.1</title>
    <style>
        body { font-family: sans-serif; margin: 40px; background-color: #f9f9f9; }
        .form-container { width: 360px; margin: 40px auto; padding: 25px; background: white; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        h2 { margin-top: 0; margin-bottom: 20px; color: #333; text-align: center; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; font-size: 14px; margin-bottom: 4px; font-weight: bold; color: #555; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn-submit { width: 100%; padding: 10px; background-color: #d9534f; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; margin-top: 10px; }
        .btn-submit:hover { background-color: #c9302c; }
        .link-group { text-align: center; margin-top: 15px; font-size: 13px; }
        .link-group a { color: #337ab7; text-decoration: none; }
        .link-group a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>회원가입</h2>
    
    <form action="register_get_process.php" method="GET" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="id">아이디</label>
            <input type="text" id="id" name="id" required>
        </div>

        <div class="form-group">
            <label for="name">이름</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="cellphone">전화번호</label>
            <input type="text" id="cellphone" name="cellphone" placeholder="010-1234-5678">
        </div>

        <button type="submit" class="btn-submit">가입하기</button>
    </form>

    <div class="link-group">
        이미 계정이 있으신가요? <a href="../login.html">로그인하기</a>
    </div>
</div>

</body>
</html>