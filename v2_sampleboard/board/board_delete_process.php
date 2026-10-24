<?php
// SimpleBoard/v2/board/board_delete_process.php
require_once __DIR__ . '/../db.php';

// 1. 로그인 여부 확인
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href = '../login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? 'USER';

// 2. 게시글 ID 파라미터 수신 및 유효성 검증 (GET/POST 모두 허용)
$post_id = $_REQUEST['id'] ?? null;

if (!$post_id || !is_numeric($post_id)) {
    echo "<script>alert('올바르지 않은 게시글 번호입니다.'); location.href = 'board_list.php';</script>";
    exit;
}

$post_id = (int)$post_id;

// 3. 게시글 존재 여부 및 작성자 확인
$check_sql = "SELECT id, author_id FROM posts WHERE id = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

if (!$post) {
    echo "<script>alert('존재하지 않거나 이미 삭제된 게시글입니다.'); location.href = 'board_list.php';</script>";
    exit;
}

// 4. 삭제 권한 확인 (작성자 본인 또는 관리자만 허용)
$is_author = ($post['author_id'] === $user_id);
$is_admin = ($user_role === 'ADMIN');

if (!$is_author && !$is_admin) {
    echo "<script>alert('게시글 삭제 권한이 없습니다.'); history.back();</script>";
    exit;
}

// 5. 게시글 삭제 실행
$delete_sql = "DELETE FROM posts WHERE id = ?";
$stmt_del = $conn->prepare($delete_sql);
$stmt_del->bind_param("i", $post_id);

if ($stmt_del->execute()) {
    $stmt_del->close();
    $conn->close();

    echo "<script>
        alert('게시글이 성공적으로 삭제되었습니다.');
        location.href = 'board_list.php';
    </script>";
    exit;
} else {
    echo "<script>
        alert('게시글 삭제 중 오류가 발생했습니다: " . addslashes($stmt_del->error) . "');
        history.back();
    </script>";
    $stmt_del->close();
    $conn->close();
    exit;
}
?>