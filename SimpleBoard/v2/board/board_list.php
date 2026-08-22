<?php
// SimpleBoard/v2/board/board_list.php
require_once __DIR__ . '/../db.php';

// 1. 로그인 여부 확인
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href = '../login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? '';
$user_role = $_SESSION['role'] ?? 'USER';

// 2. 검색어 처리
$keyword = trim($_GET['keyword'] ?? '');

// 3. 게시글 목록 쿼리 실행
if (!empty($keyword)) {
    $search_param = "%{$keyword}%";
    $sql = "SELECT id, title, author_id, views, created_at 
            FROM posts 
            WHERE title LIKE ? OR content LIKE ? OR author_id LIKE ? 
            ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT id, title, author_id, views, created_at 
            FROM posts 
            ORDER BY id DESC";
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>게시판 목록</title>
    <style>
        body { font-family: sans-serif; margin: 40px; background-color: #f5f5f5; }
        .board-container { max-width: 850px; margin: auto; background: white; padding: 25px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .user-nav { display: flex; justify-content: flex-end; align-items: center; gap: 10px; margin-bottom: 15px; font-size: 13px; color: #555; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .search-box { display: flex; gap: 8px; margin-bottom: 20px; }
        .search-box input { flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .search-box button { padding: 8px 16px; background-color: #337ab7; color: white; border: none; border-radius: 4px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border-bottom: 1px solid #ddd; padding: 12px; text-align: left; font-size: 14px; }
        th { background-color: #f8f9fa; }
        td a { color: #333; text-decoration: none; }
        td a:hover { text-decoration: underline; color: #337ab7; }
        .btn { padding: 8px 14px; border: none; border-radius: 4px; text-decoration: none; font-size: 14px; font-weight: bold; cursor: pointer; display: inline-block; }
        .btn-user { padding: 5px 10px; font-size: 12px; background-color: #4b6584; color: white; border-radius: 3px; }
        .btn-user:hover { background-color: #34495e; }
        .btn-logout { padding: 5px 10px; font-size: 12px; background-color: #e74c3c; color: white; border-radius: 3px; }
        .btn-logout:hover { background-color: #c0392b; }
        .btn-admin { padding: 5px 10px; font-size: 12px; background-color: #27ae60; color: white; border-radius: 3px; }
        .btn-admin:hover { background-color: #219150; }
        .btn-write { background-color: #5cb85c; color: white; }
        .btn-write:hover { background-color: #4cae4c; }
        .btn-footer { display: flex; justify-content: flex-end; }
        .empty-row { text-align: center; color: #888; padding: 30px; }
    </style>
</head>
<body>

<div class="board-container">
    <!-- 상단 로그인 정보 및 네비게이션 바 -->
    <div class="user-nav">
        <span>접속 계정: <b><?= htmlspecialchars($user_name) ?> (<?= htmlspecialchars($user_id) ?>)</b></span>
        
        <?php if ($user_role === 'ADMIN'): ?>
            <a href="../admin/user_list.php" class="btn btn-admin">관리자 페이지</a>
        <?php endif; ?>

        <a href="../member/user_edit.php" class="btn btn-user">내 정보 수정</a>
        <a href="../logout.php" class="btn btn-logout">로그아웃</a>
    </div>

    <div class="header">
        <h2 style="margin: 0;">자유게시판</h2>
    </div>

    <!-- 검색 영역 -->
    <form class="search-box" action="board_list.php" method="GET">
        <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="제목, 내용, 작성자로 검색">
        <button type="submit">검색</button>
    </form>

    <!-- 게시글 테이블 -->
    <table>
        <thead>
            <tr>
                <th style="width: 8%;">번호</th>
                <th style="width: 50%;">제목</th>
                <th style="width: 15%;">작성자</th>
                <th style="width: 17%;">작성일시</th>
                <th style="width: 10%;">조회수</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td>
                            <a href="board_view.php?id=<?= htmlspecialchars($row['id']) ?>">
                                <?= htmlspecialchars($row['title']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($row['author_id']) ?></td>
                        <td><?= substr($row['created_at'], 0, 10) ?></td>
                        <td><?= htmlspecialchars($row['views']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="empty-row">등록된 게시글이 없습니다.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="btn-footer">
        <a href="board_write.php" class="btn btn-write">글쓰기</a>
    </div>
</div>

</body>
</html>
<?php
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>