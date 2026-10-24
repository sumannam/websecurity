<?php
// DB 연결
require_once __DIR__ . '/db.php';

// 검색할 대상 아이디 지정
$search_id = "CJU";

// 1. 데이터 1건 조회 쿼리 준비
$sql = "SELECT idx, id, name, cellphone, created_at FROM sampleusers WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $search_id);
$stmt->execute();

// 2. 결과 가져오기
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// 3. echo로 화면 출력
// htmlspecialchars() 함수를 사용하여 XSS 공격 방지(예: <script>alert('해킹');</script>)
if ($row) {
    echo "<h3>회원 정보 조회 결과</h3>";
    echo "번호: " . htmlspecialchars($row['idx']) . "<br>";
    echo "아이디: " . htmlspecialchars($row['id']) . "<br>";
    echo "이름: " . htmlspecialchars($row['name']) . "<br>";
    echo "전화번호: " . htmlspecialchars($row['cellphone']) . "<br>";
    echo "등록일: " . htmlspecialchars($row['created_at']) . "<br>";
} else {
    echo "<h3>조회 실패</h3>";
    echo "'" . htmlspecialchars($search_id) . "' 아이디를 가진 회원이 존재하지 않습니다.<br>";
}

// 4. 자원 정리
$stmt->close();
$conn->close();
?>