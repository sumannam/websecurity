<?php
// SimpleBoard/v2/admin/user_delete_process.php
require_once __DIR__ . '/../db.php';

// 1. 관리자 권한 확인
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'ADMIN') {
    echo "<script>alert('관리자만 접근 가능합니다.'); location.href = '../login.php';</script>";
    exit;
}

$admin_id = $_SESSION['user_id'];
$target_id = trim($_REQUEST['id'] ?? '');

// 2. 파라미터 유효성 검증
if (empty($target_id)) {
    echo "<script>alert('삭제할 회원 아이디가 지정되지 않았습니다.'); history.back();</script>";
    exit;
}

// 3. 관리자 본인 계정 강제 삭제 방지
if ($target_id === $admin_id) {
    echo "<script>alert('현재 로그인된 관리자 본인 계정은 강제 탈퇴할 수 없습니다.'); history.back();</script>";
    exit;
}

// 4. 대상 회원 존재 여부 확인
$check_sql = "SELECT id FROM users WHERE id = ?";
$stmt_check = $conn->prepare($check_sql);
$stmt_check->bind_param("s", $target_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    echo "<script>alert('존재하지 않거나 이미 탈퇴된 회원입니다.'); location.href = 'manage_users.php';</script>";
    $stmt_check->close();
    exit;
}
$stmt_check->close();

// 5. 회원 삭제 실행 (FK ON DELETE CASCADE 설정으로 작성한 게시글도 자동 삭제)
$delete_sql = "DELETE FROM users WHERE id = ?";
$stmt_del = $conn->prepare($delete_sql);
$stmt_del->bind_param("s", $target_id);

if ($stmt_del->execute()) {
    $stmt_del->close();
    $conn->close();

    echo "<script>
        alert('회원 [ " . addslashes($target_id) . " ] 계정이 정상적으로 강제 탈퇴 처리되었습니다.');
        location.href = 'manage_users.php';
    </script>";
    exit;
} else {
    echo "<script>
        alert('회원 삭제 처리 실패: " . addslashes($stmt_del->error) . "');
        history.back();
    </script>";
    $stmt_del->close();
    $conn->close();
    exit;
}
?>