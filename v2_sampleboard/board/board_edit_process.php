<?php
// SimpleBoard/v2/board/board_edit_process.php
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

$user_id = $_SESSION['user_id'];
$post_id = $_POST['id'] ?? null;
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');

// 3. 파라미터 유효성 검증
if (!$post_id || !is_numeric($post_id)) {
    echo "<script>alert('올바르지 않은 게시글 번호입니다.'); location.href = 'board_list.php';</script>";
    exit;
}

$post_id = (int)$post_id;

if (empty($title) || empty($content)) {
    echo "<script>alert('제목과 내용을 모두 입력해 주세요.'); history.back();</script>";
    exit;
}

// 4. 작성자 본인 확인
$check_sql = "SELECT author_id FROM posts WHERE id = ?";
$stmt_check = $conn->prepare($check_sql);
$stmt_check->bind_param("i", $post_id);
$stmt_check->execute();
$result = $stmt_check->get_result();
$post = $result->fetch_assoc();
$stmt_check->close();

if (!$post) {
    echo "<script>alert('존재하지 않거나 삭제된 게시글입니다.'); location.href = 'board_list.php';</script>";
    exit;
}

if ($post['author_id'] !== $user_id) {
    echo "<script>alert('게시글 수정 권한이 없습니다.'); history.back();</script>";
    exit;
}

// 5. 게시글 수정 쿼리 실행
$sql = "UPDATE posts SET title = ?, content = ?, updated_at = NOW() WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $title, $content, $post_id);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();

    echo "<script>
        alert('게시글이 성공적으로 수정되었습니다.');
        location.href = 'board_view.php?id={$post_id}';
    </script>";
    exit;
} else {
    echo "<script>
        alert('게시글 수정 중 오류가 발생했습니다: " . addslashes($stmt->error) . "');
        history.back();
    </script>";
    $stmt->close();
    $conn->close();
    exit;
}
?>