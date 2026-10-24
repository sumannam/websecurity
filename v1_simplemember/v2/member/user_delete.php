<?php
// SimpleBoard/v2/member/user_delete.php
require_once __DIR__ . '/../db.php';

// 1. 로그인 여부 확인 (일반 회원 누구나 접근 가능)
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href = '../login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원 탈퇴</title>
    <style>
        body { font-family: sans-serif; margin: 40px; background-color: #f9f9f9; }
        .form-container { width: 380px; margin: 50px auto; padding: 25px; background: white; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        h2 { margin-top: 0; margin-bottom: 15px; color: #d9534f; text-align: center; }
        .warning-box { background-color: #fff3cd; border: 1px solid #ffeeba; color: #856404; padding: 12px; border-radius: 4px; font-size: 13px; line-height: 1.5; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px; color: #555; }
        .form-group input { width: 100%; padding: 9px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        .form-group input[readonly] { background-color: #f1f2f6; cursor: not-allowed; color: #777; }
        .btn-delete { width: 100%; padding: 10px; background-color: #d9534f; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 14px; margin-top: 5px; }
        .btn-delete:hover { background-color: #c9302c; }
        .footer-nav { text-align: center; margin-top: 18px; font-size: 13px; }
        .footer-nav a { color: #555; text-decoration: none; }
        .footer-nav a:hover { color: #337ab7; text-decoration: underline; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>회원 탈퇴</h2>

    <div class="warning-box">
        <strong>주의:</strong> 탈퇴 시 회원 정보와 작성하신 모든 게시글이 영구적으로 삭제되며, 복구할 수 없습니다.
    </div>
    
    <form action="user_delete_process.php" method="POST" onsubmit="return confirmDelete()">
        <div class="form-group">
            <label for="id">탈퇴 대상 아이디</label>
            <input type="text" id="id" name="id" value="<?= htmlspecialchars($user_id) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="pw">비밀번호 확인</label>
            <input type="password" id="pw" name="pw" placeholder="본인 확인용 비밀번호를 입력하세요" required autofocus>
        </div>

        <button type="submit" class="btn-delete">탈퇴 처리하기</button>
    </form>

    <div class="footer-nav">
        <a href="user_edit.php">← 내 정보 수정으로 돌아가기</a>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm("정말로 회원 탈퇴를 진행하시겠습니까?\n작성된 모든 게시글이 함께 삭제됩니다.");
    }
</script>

</body>
</html>