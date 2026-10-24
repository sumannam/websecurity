<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원가입</title>
    <style>
        body { font-family: sans-serif; margin: 40px; background-color: #f9f9f9; }
        .form-container { width: 380px; margin: 40px auto; padding: 25px; background: white; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        h2 { margin-top: 0; margin-bottom: 20px; color: #333; text-align: center; }
        .form-group { margin-bottom: 14px; }
        .form-group label { display: block; font-size: 13px; font-weight: bold; margin-bottom: 4px; color: #555; }
        .form-group label span.required { color: #d9534f; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        .help-text { font-size: 11px; color: #888; margin-top: 3px; }
        .btn-submit { width: 100%; padding: 10px; background-color: #337ab7; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 14px; margin-top: 10px; }
        .btn-submit:hover { background-color: #286090; }
        .footer-nav { display: flex; justify-content: space-between; margin-top: 20px; font-size: 13px; border-top: 1px solid #eee; padding-top: 15px; }
        .footer-nav a { color: #555; text-decoration: none; }
        .footer-nav a:hover { color: #337ab7; text-decoration: underline; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>회원가입</h2>
    
    <form action="register_process.php" method="POST" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="id">아이디 <span class="required">*</span></label>
            <input type="text" id="id" name="id" placeholder="영문/숫자 아이디 입력" required>
            <div class="help-text">로그인에 사용할 아이디를 입력하세요.</div>
        </div>

        <div class="form-group">
            <label for="password">비밀번호 <span class="required">*</span></label>
            <input type="password" id="password" name="password" placeholder="비밀번호 입력" required>
        </div>

        <div class="form-group">
            <label for="password_confirm">비밀번호 확인 <span class="required">*</span></label>
            <input type="password" id="password_confirm" name="password_confirm" placeholder="비밀번호 다시 입력" required>
        </div>

        <hr style="border: 0; border-top: 1px dashed #ddd; margin: 18px 0;">

        <div class="form-group">
            <label for="name">이름 <span class="required">*</span></label>
            <input type="text" id="name" name="name" placeholder="이름 입력" required>
        </div>

        <div class="form-group">
            <label for="cellphone">전화번호</label>
            <input type="text" id="cellphone" name="cellphone" placeholder="010-1234-5678">
        </div>

        <div class="form-group">
            <label for="email">이메일</label>
            <input type="email" id="email" name="email" placeholder="test@test.com">
        </div>
        
        <button type="submit" class="btn-submit">가입하기</button>
    </form>

    <div class="footer-nav">
        <a href="../login.php">← 로그인 페이지로</a>
        <a href="../board/board_list.php">게시판 둘러보기</a>
    </div>
</div>

<script>
    function validateForm() {
        var pw = document.getElementById("password").value;
        var pwConfirm = document.getElementById("password_confirm").value;

        if (pw !== pwConfirm) {
            alert("비밀번호와 비밀번호 확인이 일치하지 않습니다.");
            document.getElementById("password_confirm").focus();
            return false;
        }
        return true;
    }
</script>

</body>
</html>