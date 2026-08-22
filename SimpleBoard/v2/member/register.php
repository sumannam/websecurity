<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원가입 실습</title>
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
    
    <form action="register_process.php" method="POST" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="id">아이디</label>
            <input type="text" id="id" name="id" required>
        </div>

        <div class="form-group">
            <label for="pw">비밀번호</label>
            <input type="password" id="pw" name="pw" required>
        </div>

        <div class="form-group">
            <label for="pw_confirm">비밀번호 확인</label>
            <input type="password" id="pw_confirm" name="pw_confirm" required>
        </div>

        <div class="form-group">
            <label for="name">이름</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="cellphone">전화번호</label>
            <input type="text" id="cellphone" name="cellphone" placeholder="010-1234-5678">
        </div>

        <div class="form-group">
            <label for="email">이메일</label>
            <input type="text" id="email" name="email" placeholder="test@test.com">
        </div>

        <button type="submit" class="btn-submit">가입하기</button>
    </form>

    <div class="link-group">
        이미 계정이 있으신가요? <a href="../login.html">로그인하기</a>
    </div>
</div>

<script>
    function validateForm() {
        var pw = document.getElementById("pw").value;
        var pwConfirm = document.getElementById("pw_confirm").value;

        if (pw !== pwConfirm) {
            alert("비밀번호 확인이 일치하지 않습니다.");
            return false;
        }
        return true;
    }
</script>

</body>
</html>