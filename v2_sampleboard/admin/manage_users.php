<?php
// SimpleBoard/v2/admin/manage_users.php
require_once __DIR__ . '/../db.php';

// 1. 관리자 권한 확인
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'ADMIN') {
    echo "<script>alert('관리자 권한이 필요합니다.'); location.href = '../login.php';</script>";
    exit;
}

$admin_id = $_SESSION['user_id'];
$admin_name = $_SESSION['user_name'] ?? '';

// 2. 검색어 수신
$keyword = trim($_GET['keyword'] ?? '');

// 3. 회원 목록 쿼리 실행
if (!empty($keyword)) {
    $search_param = "%" . $keyword . "%";
    $sql = "SELECT id, name, cellphone, email, role, created_at 
            FROM users 
            WHERE id LIKE ? OR name LIKE ? OR email LIKE ? 
            ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT id, name, cellphone, email, role, created_at 
            FROM users 
            ORDER BY created_at DESC";
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>관리자 - 회원 관리</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 30px; background-color: #f4f6f9; }
        .admin-container { max-width: 1000px; margin: auto; background: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.06); }
        .top-nav { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #337ab7; padding-bottom: 12px; margin-bottom: 20px; }
        .nav-links { display: flex; align-items: center; gap: 8px; }
        .nav-links a { text-decoration: none; font-size: 13px; padding: 6px 12px; border-radius: 4px; font-weight: bold; }
        .btn-tab { background-color: #337ab7; color: #ffffff; }
        .btn-tab-sub { background-color: #e9ecef; color: #333333; }
        .btn-tab-sub:hover { background-color: #dee2e6; }
        .btn-logout { background-color: #e74c3c; color: #ffffff; }
        .btn-logout:hover { background-color: #c0392b; }
        .search-area { display: flex; gap: 8px; margin-bottom: 20px; }
        .search-area input { flex: 1; padding: 9px 12px; border: 1px solid #cccccc; border-radius: 4px; font-size: 14px; }
        .search-area button { padding: 9px 20px; background-color: #337ab7; color: #ffffff; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: bold; }
        .search-area button:hover { background-color: #286090; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e9ecef; padding: 12px 14px; text-align: left; font-size: 13px; }
        th { background-color: #f8f9fa; color: #495057; font-weight: 600; }
        tbody tr:hover { background-color: #fcfcfc; }
        .user-link { color: #337ab7; text-decoration: none; font-weight: bold; }
        .user-link:hover { text-decoration: underline; color: #23527c; }
        .badge-admin { background-color: #28a745; color: #ffffff; padding: 3px 8px; border-radius: 3px; font-size: 11px; font-weight: bold; }
        .badge-user { background-color: #6c757d; color: #ffffff; padding: 3px 8px; border-radius: 3px; font-size: 11px; }
        .btn-del { padding: 5px 10px; background-color: #dc3545; color: #ffffff; border: none; border-radius: 3px; cursor: pointer; font-size: 12px; text-decoration: none; display: inline-block; font-weight: bold; }
        .btn-del:hover { background-color: #bd2130; }
        .empty-td { text-align: center; color: #888888; padding: 40px; }
    </style>
</head>
<body>

<div class="admin-container">
    <div class="top-nav">
        <h2 style="margin: 0; color: #333333;">관리자 대시보드</h2>
        <div class="nav-links">
            <span style="font-size: 13px; color: #555555; margin-right: 6px;">접속자: <b><?= htmlspecialchars($admin_name) ?> (<?= htmlspecialchars($admin_id) ?>)</b></span>
            <a href="manage_users.php" class="btn-tab">회원 관리</a>
            <!-- <a href="board_posts.php" class="btn-tab-sub">게시글 관리</a> -->
            <a href="../board/board_list.php" class="btn-tab-sub">게시판 가기</a>
            <a href="../logout.php" class="btn-logout">로그아웃</a>
        </div>
    </div>

    <form class="search-area" action="manage_users.php" method="GET">
        <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="아이디, 이름, 이메일로 검색">
        <button type="submit">검색</button>
    </form>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">아이디 (클릭 시 수정)</th>
                <th style="width: 14%;">이름</th>
                <th style="width: 18%;">연락처</th>
                <th style="width: 21%;">이메일</th>
                <th style="width: 10%;">권한</th>
                <th style="width: 12%;">가입일</th>
                <th style="width: 10%; text-align: center;">관리</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <a href="admin_user_edit.php?id=<?= urlencode($row['id']) ?>" class="user-link">
                                <?= htmlspecialchars($row['id']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['cellphone'] ?: '-') ?></td>
                        <td><?= htmlspecialchars($row['email'] ?: '-') ?></td>
                        <td>
                            <?php if ($row['role'] === 'ADMIN'): ?>
                                <span class="badge-admin">ADMIN</span>
                            <?php else: ?>
                                <span class="badge-user">USER</span>
                            <?php endif; ?>
                        </td>
                        <td><?= substr($row['created_at'], 0, 10) ?></td>
                        <td style="text-align: center;">
                            <?php if ($row['id'] !== $admin_id): ?>
                                <a href="user_delete_process.php?id=<?= urlencode($row['id']) ?>" 
                                   class="btn-del" 
                                   onclick="return confirm('해당 회원 [ <?= htmlspecialchars($row['id']) ?> ] 을(를) 강제 탈퇴 처리하시겠습니까?\n작성한 모든 게시글도 함께 영구 삭제됩니다.');">강제탈퇴</a>
                            <?php else: ?>
                                <span style="font-size: 12px; color: #999999;">본인</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="empty-td">검색 조건에 일치하는 회원이 없습니다.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
<?php
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>