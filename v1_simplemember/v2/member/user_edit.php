<?php
// SimpleBoard/v2/member/user_edit.php
require_once __DIR__ . '/../db.php';

// 1. 로그인 여부 확인
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href = '../login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// 2. 현재 로그인한 사용자의 기존 정보 조회
$sql = "SELECT id, name, cellphone, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<script>alert('존재하지 않는 회원 정보입니다.'); location.href = '../logout.php';</script>";
    exit;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>내 정보 수정</title>
    <style>
        body { font-family: sans-serif; margin: 40px; background-color: #f9f9f9; }
        .form-container { width: 380px; margin: 40px auto; padding: 25px; background: white; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        h2 { margin-top: 0; margin-bottom: 20px; color: #333; text-align: center; }
        .form-group { margin-bottom: 14px; }
        .form-group label { display: block; font-size: 13px; font-weight: bold; margin-bottom: 4px; color: #555; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        .form-group input[readonly] { background-color: #f1f2f6; cursor: not-allowed; color: #777; }
        .help-text { font-size: 11px; color: #888; margin-top: 3px; }
        .btn-submit { width: 100%; padding: 10px; background-color: #337ab7; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 14px; margin-top: 10px; }
        .btn-submit:hover { background-color: #286090; }
        .footer-nav { display: flex; justify-content: space-between; margin-top: 20px; font-size: 13px; border-top: 1px solid #eee; padding-top: 15px; }
        .footer-nav a { color: #555; text-decoration: none; }
        .footer-nav a:hover { color: #337ab7; text-decoration: underline; }
        .footer-nav .btn-delete { color: #d9534f; font-weight: bold; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>내 정보 수정</h2>
    
    <form action="user_edit_process.php" method="POST" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="id">아이디</label>
            <input type="text" id="id" name="id" value="<?= htmlspecialchars($user['id']) ?>" readonly>
            <div class="help-text">아이디는 변경할 수 없습니다.</div>
        </div>

        <div class="form-group">
            <label for="name">이름</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="cellphone">전화번호</label>
            <input type="text" id="cellphone" name="cellphone" value="<?= htmlspecialchars($user['cellphone'] ?? '') ?>" placeholder="010-1234-5678">
        </div>

        <div class="form-group">
            <label for="email">이메일</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" placeholder="test@test.com">
        </div>

        <hr style="border: 0; border-top: 1px dashed #ddd; margin: 18px 0;">

        <div class="form-group">
            <label for="current_pw">현재 비밀번호 <span style="color: red;">*</span></label>
            <input type="password" id="current_pw" name="current_pw" placeholder="본인 확인용 현재 비밀번호" required>
        </div>

        <div class="form-group">
            <label for="new_pw">새 비밀번호</label>
            <input type="password" id="new_pw" name="new_pw" placeholder="변경할 때만 입력하세요">
        </div>

        <div class="form-group">
            <label for="new_pw_confirm">새 비밀번호 확인</label>
            <input type="password" id="new_pw_confirm" name="new_pw_confirm" placeholder="새 비밀번호 확인">
        </div>
        
        <button type="submit" class="btn-submit">정보 수정</button>
    </form>

    <div class="footer-nav">
        <a href="../board/board_list.php">← 게시판으로 돌아가기</a>
        <a href="user_delete.php" class="btn-delete">회원 탈퇴</a>
    </div>
</div>

<script>
    function validateForm() {
        var newPw = document.getElementById("new_pw").value;
        var newPwConfirm = document.getElementById("new_pw_confirm").value;

        if (newPw !== "" && newPw !== newPwConfirm) {
            alert("새 비밀번호와 비밀번호 확인이 일치하지 않습니다.");
            return false;
        }
        return true;
    }
</script>

</body>
</html>