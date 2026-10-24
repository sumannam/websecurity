<?php
// SimpleBoard/v2/board/board_write.php
require_once __DIR__ . '/../db.php';

// 1. 로그인 여부 확인
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
    <title>게시글 작성</title>
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
        <h2>새 게시글 작성</h2>
    </div>

    <form action="board_write_process.php" method="POST">
        <div class="form-group">
            <label for="author">작성자</label>
            <input type="text" id="author" value="<?= htmlspecialchars($user_name) ?> (<?= htmlspecialchars($user_id) ?>)" readonly>
        </div>

        <div class="form-group">
            <label for="title">제목</label>
            <input type="text" id="title" name="title" placeholder="게시글 제목을 입력하세요" required autofocus>
        </div>

        <div class="form-group">
            <label for="content">내용</label>
            <textarea id="content" name="content" placeholder="내용을 자유롭게 입력하세요" required></textarea>
        </div>

        <div class="btn-group">
            <a href="board_list.php" class="btn btn-cancel">취소</a>
            <button type="submit" class="btn btn-submit">등록하기</button>
        </div>
    </form>
</div>

</body>
</html>