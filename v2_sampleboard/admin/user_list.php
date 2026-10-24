<?php
// 1. 세션 시작 및 관리자 권한 검사
session_start();

// DB 연결 파일 include (프로젝트 경로에 맞게 확인)
require_once '../db.php'; 

// 로그인 여부 및 관리자 권한 확인
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'ADMIN') {
    echo "<script>
            alert('관리자 권한이 필요합니다.'); 
            location.href = '../login.php';
          </script>";
    exit;
}

$admin_id   =$_SESSION['user_id'];
$admin_name =$_SESSION['user_name'] ?? '';

// 2. 검색어 수신
$keyword = trim($_GET['keyword'] ?? '');

// 3. 회원 목록 쿼리 실행
if (!empty($keyword)) {
    // 검색어가 있을 경우 LIKE 쿼리 사용
    $search_param = "\%" . $keyword . "%";
    $sql = "SELECT id, name, cellphone, email, role, created_at 
            FROM users 
            WHERE id LIKE ? OR name LIKE ? OR email LIKE ? 
            ORDER BY created_at DESC";
    $stmt =$conn->prepare($sql);$stmt->bind_param("sss", $search_param,$search_param, $search_param);$stmt->execute();
    $result =$stmt->get_result();
} else {
    // 검색어가 없을 경우 전체 조회
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
        body { font-family: sans-serif; margin: 20px; line-height: 1.5; }
        header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ccc; padding-bottom: 10px; margin-bottom: 20px; }
        nav a { margin-right: 12px; text-decoration: none; color: #0056b3; }
        nav a:hover { text-decoration: underline; }
        .search-box { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f4f6f8; }
        tr:nth-child(even) { background-color: #fafafa; }
        .btn-action { padding: 4px 8px; font-size: 12px; cursor: pointer; text-decoration: none; border: 1px solid #999; border-radius: 3px; color: #333; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <header>
        <h1>관리자 대시보드</h1>
        <div>
            접속자: <strong><?= htmlspecialchars($admin_name) ?> (<?= htmlspecialchars($admin_id) ?>)</strong>
        </div>
    </header>

    <nav>
        <a href="manage_users.php"><strong>회원 관리</strong></a>
        <a href="../board/board_list.php">게시글 관리</a>
        <a href="../logout.php">로그아웃</a>
    </nav>

    <hr style="border: none; border-top: 1px solid #eee; margin: 15px 0;">

    <section>
        <h2>회원 관리</h2>
        
        <div class="search-box">
            <form method="GET" action="">
                <input type="text" name="keyword" placeholder="아이디, 이름, 이메일 검색" value="<?= htmlspecialchars($keyword) ?>">
                <button type="submit">검색</button>
                <?php if (!empty($keyword)): ?>
                    <a href="manage_users.php">초기화</a>
                <?php endif; ?>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>아이디</th>
                    <th>이름</th>
                    <th>연락처</th>
                    <th>이메일</th>
                    <th>권한</th>
                    <th>가입일</th>
                    <th>관리</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row =$result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['cellphone'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['email'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['role']) ?></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                            <td>
                                <a href="user_edit.php?id=<?= urlencode($row['id']) ?>" class="btn-action">수정</a>
                                <a href="user_delete.php?id=<?= urlencode($row['id']) ?>" class="btn-action" onclick="return confirm('정말 삭제하시겠습니까?');">삭제</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">검색 조건에 일치하는 회원이 없습니다.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

</body>
</html>
<?php
// DB 자원 정리
if (isset($stmt)) {$stmt->close();
}
$conn->close();
?>