<?php
// SimpleBoard/v2/board/board_edit.php
require_once __DIR__ . '/../db.php';

// 1. 로그인 여부 확인
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href = '../login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? 'USER';

// 2. 게시글 ID 파라미터 유효성 검증
$post_id = $_GET['id'] ?? null;

if (!$post_id || !is_numeric($post_id)) {
    echo "<script>alert('올바르지 않은 게시글 번호입니다.'); location.href = 'board_list.php';</script>";
    exit;
}

$post_id = (int)$post_id;

// 3. 기존 게시글 조회
$sql = "SELECT id, title, content, author_id FROM posts WHERE id = ?";
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

// 4. 수정 권한 확인 (작성자 본인만 허용)
if ($post['author_id'] !== $user_id) {
    echo "<script>alert('게시글 수정 권한이 없습니다.'); history.back();</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>게시글 수정</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 40px; background-color: #f5f5f5; }
        .board-container { max-width: 850px; margin: auto; background: white; padding: 30px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .header { border-bottom: 2px solid #337ab7; padding-bottom: 12px; margin-bottom: 25px; }
        .header h2 { margin: 0; color: #333; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 14px; font-weight: bold; margin-bottom: 6px; color: #555; }
        .form-group input[type="text"] { width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        .form-group input[readonly] { background-color: #f1f2f6; cursor: not-allowed; color: #777; }
        .form-group textarea { width: 100%; height: 260px; padding: 12px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; font-family: inherit; resize: vertical; }
        .btn-group { display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; border-top: 1px solid #eee; padding-top: 20px; }
        .btn { padding: 9px 18px; border: none; border-radius: 4px; font-size: 14px; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-cancel { background-color: #e9ecef; color: #333; }
        .btn-cancel:hover { background-color: #dee2e6; }
        .btn-submit { background-color: #337ab7; color: white; }
        .btn-submit:hover { background-color: #286090; }
    </style>
</head>
<body>

<div class="board-container">
    <div class="header">
        <h2>게시글 수정</h2>
    </div>

    <form action="board_edit_process.php" method="POST">
        <!-- 수정 대상 게시글 번호 전달 -->
        <input type="hidden" name="id" value="<?= htmlspecialchars($post['id']) ?>">

        <div class="form-group">
            <label for="author">작성자</label>
            <input type="text" id="author" value="<?= htmlspecialchars($post['author_id']) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="title">제목</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required autofocus>
        </div>

        <div class="form-group">
            <label for="content">내용</label>
            <textarea id="content" name="content" required><?= htmlspecialchars($post['content']) ?></textarea>
        </div>

        <div class="btn-group">
            <a href="board_view.php?id=<?= $post['id'] ?>" class="btn btn-cancel">취소</a>
            <button type="submit" class="btn btn-submit">수정 완료</button>
        </div>
    </form>
</div>

</body>
</html>
<?php
$conn->close();
?>