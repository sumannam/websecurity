<?php
// SimpleBoard/v2/board/board_view.php
require_once __DIR__ . '/../db.php';

// 1. 로그인 여부 확인
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href = '../login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? 'USER';

// 2. 게시글 ID 파라미터 검증
$post_id = $_GET['id'] ?? null;

if (!$post_id || !is_numeric($post_id)) {
    echo "<script>alert('유효하지 않은 게시글 번호입니다.'); location.href = 'board_list.php';</script>";
    exit;
}

$post_id = (int)$post_id;

// 3. 조회수 증가 (단순 카운트 증가 처리)
$update_views_sql = "UPDATE posts SET views = views + 1 WHERE id = ?";
$stmt_views = $conn->prepare($update_views_sql);
$stmt_views->bind_param("i", $post_id);
$stmt_views->execute();
$stmt_views->close();

// 4. 게시글 상세 조회
$sql = "SELECT id, title, content, author_id, views, created_at, updated_at 
        FROM posts 
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

if (!$post) {
    echo "<script>alert('존재하지 않거나 삭제된 게시글입니다.'); location.href = 'board_list.php';</script>";
    exit;
}

// 5. 수정/삭제 권한 확인 (작성자 본인 또는 관리자)
$is_author = ($post['author_id'] === $user_id);
$is_admin = ($user_role === 'ADMIN');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title']) ?></title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 40px; background-color: #f5f5f5; }
        .view-container { max-width: 850px; margin: auto; background: white; padding: 30px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .header { border-bottom: 2px solid #337ab7; padding-bottom: 15px; margin-bottom: 20px; }
        .post-title { font-size: 22px; font-weight: bold; color: #333; margin: 0 0 10px 0; }
        .post-meta { display: flex; justify-content: space-between; font-size: 13px; color: #777; }
        .meta-group span { margin-right: 15px; }
        .post-content { min-height: 250px; padding: 20px 5px; font-size: 15px; line-height: 1.6; color: #333; white-space: pre-wrap; word-break: break-all; border-bottom: 1px solid #eee; margin-bottom: 25px; }
        .btn-group { display: flex; justify-content: space-between; align-items: center; }
        .btn-left { display: flex; gap: 8px; }
        .btn-right { display: flex; gap: 8px; }
        .btn { padding: 8px 16px; border: none; border-radius: 4px; font-size: 14px; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-list { background-color: #6c757d; color: white; }
        .btn-list:hover { background-color: #5a6268; }
        .btn-edit { background-color: #337ab7; color: white; }
        .btn-edit:hover { background-color: #286090; }
        .btn-delete { background-color: #d9534f; color: white; }
        .btn-delete:hover { background-color: #c9302c; }
    </style>
</head>
<body>

<div class="view-container">
    <div class="header">
        <h1 class="post-title"><?= htmlspecialchars($post['title']) ?></h1>
        <div class="post-meta">
            <div class="meta-group">
                <span>작성자: <b><?= htmlspecialchars($post['author_id']) ?></b></span>
                <span>작성일시: <?= substr($post['created_at'], 0, 16) ?></span>
            </div>
            <div class="meta-group">
                <span>조회수: <?= htmlspecialchars($post['views']) ?></span>
            </div>
        </div>
    </div>

    <!-- 게시글 본문 (개행 문자 유지) -->
    <div class="post-content"><?= htmlspecialchars($post['content']) ?></div>

    <div class="btn-group">
        <div class="btn-left">
            <a href="board_list.php" class="btn btn-list">목록</a>
        </div>

        <div class="btn-right">
            <?php if ($is_author || $is_admin): ?>
                <?php if ($is_author): ?>
                    <a href="board_edit.php?id=<?= $post['id'] ?>" class="btn btn-edit">수정</a>
                <?php endif; ?>
                <a href="board_delete_process.php?id=<?= $post['id'] ?>" 
                   class="btn btn-delete" 
                   onclick="return confirm('정말로 이 게시글을 삭제하시겠습니까?');">삭제</a>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
<?php
$conn->close();
?>