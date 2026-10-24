<?php
// SimpleBoard/v2/board/board_write_process.php
require_once __DIR__ . '/../db.php';

// 1. 로그인 여부 확인
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href = '../login.php';</script>";
    exit;
}

// 2. POST 요청 확인
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: board_list.php");
    exit;
}

$author_id = $_SESSION['user_id'];
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');

// 3. 필수 입력값 검증
if (empty($title) || empty($content)) {
    echo "<script>alert('제목과 내용을 모두 입력해 주세요.'); history.back();</script>";
    exit;
}

// 4. 게시글 등록 쿼리 실행 (Prepared Statement)
$sql = "INSERT INTO posts (title, content, author_id, views, created_at, updated_at) 
        VALUES (?, ?, ?, 0, NOW(), NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $title, $content, $author_id);

if ($stmt->execute()) {
    $new_post_id = $stmt->insert_id;
    $stmt->close();
    $conn->close();

    echo "<script>
        alert('게시글이 성공적으로 등록되었습니다.');
        location.href = 'board_list.php';
    </script>";
    exit;
} else {
    echo "<script>
        alert('게시글 등록 중 오류가 발생했습니다: " . addslashes($stmt->error) . "');
        history.back();
    </script>";
    $stmt->close();
    $conn->close();
    exit;
}
?>