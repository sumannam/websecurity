<?php
// SimpleBoard/v2/admin/admin_user_edit.php
require_once __DIR__ . '/../db.php';

// 1. 관리자 권한 확인
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'ADMIN') {
    echo "<script>alert('관리자 권한이 필요합니다.'); location.href = '../login.php';</script>";
    exit;
}

// 2. 수정 대상 회원 ID 검증
$target_id = trim($_GET['id'] ?? '');

if (empty($target_id)) {
    echo "<script>alert('대상 회원 아이디가 지정되지 않았습니다.'); location.href = 'manage_users.php';</script>";
    exit;
}

// 3. 회원 정보 조회
$sql = "SELECT id, name, cellphone, email, role, created_at, updated_at FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $target_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "<script>alert('존재하지 않는 회원입니다.'); location.href = 'manage_users.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>관리자 - 회원 정보 임의 수정</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 40px; background-color: #f4f6f9; }
        .edit-container { max-width: 480px; margin: 30px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); border: 1px solid #e1e4e8; }
        h2 { margin-top: 0; margin-bottom: 20px; font-size: 20px; color: #333; text-align: center; border-bottom: 2px solid #337ab7; padding-bottom: 12px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px; color: #555; }
        .form-group input, .form-group select { width: 100%; padding: 9px 10px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        .form-group input[readonly] { background-color: #f1f2f6; cursor: not-allowed; color: #666; }
        .help-text { font-size: 11px; color: #888; margin-top: 4px; }
        .btn-submit { width: 100%; padding: 11px; background-color: #337ab7; color: white; border: none; border-radius: 4px; font-size: 14px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .btn-submit:hover { background-color: #286090; }
        .footer-nav { margin-top: 20px; text-align: center; }
        .footer-nav a { font-size: 13px; color: #666; text-decoration: none; }
        .footer-nav a:hover { text-decoration: underline; color: #337ab7; }
    </style>
</head>
<body>

<div class="edit-container">
    <h2>회원 정보 수정 (관리자 모드)</h2>

    <form action="admin_user_edit_process.php" method="POST">
        <div class="form-group">
            <label for="id">회원 아이디</label>
            <input type="text" id="id" name="id" value="<?= htmlspecialchars($user['id']) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="role">계정 권한</label>
            <select id="role" name="role">
                <option value="USER" <?= ($user['role'] === 'USER') ? 'selected' : '' ?>>일반 회원 (USER)</option>
                <option value="ADMIN" <?= ($user['role'] === 'ADMIN') ? 'selected' : '' ?>>관리자 (ADMIN)</option>
            </select>
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
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" placeholder="user@domain.com">
        </div>

        <div class="form-group">
            <label for="new_pw">비밀번호 강제 변경</label>
            <input type="password" id="new_pw" name="new_pw" placeholder="비밀번호를 재설정할 때만 입력">
            <div class="help-text">공백으로 둘 경우 기존 비밀번호가 그대로 유지됩니다.</div>
        </div>

        <button type="submit" class="btn-submit">정보 저장하기</button>
    </form>

    <div class="footer-nav">
        <a href="manage_users.php">← 회원 관리 목록으로 돌아가기</a>
    </div>
</div>

</body>
</html>
<?php
$conn->close();
?>