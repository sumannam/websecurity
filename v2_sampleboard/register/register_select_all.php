<?php
// DB 연결
require_once __DIR__ . '/db.php';

// 1. 전체 데이터 조회 쿼리 (최신 등록순 정렬)
$sql = "SELECT idx, id, name, cellphone, created_at FROM sampleusers ORDER BY idx DESC";
$result = $conn->query($sql);

if (!$result) {
    die("쿼리 실행 오류: " . $conn->error);
}

// 2. 전체 건수 확인
$total_count = $result->num_rows;

echo "<h2>전체 회원 목록 (총 " . $total_count . "명)</h2>";
echo "<hr>";

// 3. 데이터 유무에 따른 분기 및 출력
if ($total_count > 0) {
    // 결과 집합(Result Set)을 한 행씩 순회
    while ($row = $result->fetch_assoc()) {
        echo "번호: " . htmlspecialchars($row['idx'], ENT_QUOTES, 'UTF-8') . " | ";
        echo "아이디: " . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . " | ";
        echo "이름: " . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . " | ";
        echo "전화번호: " . htmlspecialchars($row['cellphone'] ?? '-', ENT_QUOTES, 'UTF-8') . " | ";
        echo "가입일: " . htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8');
        echo "<br><hr>";
    }
} else {
    echo "등록된 회원이 없습니다.<br>";
}

// 4. 자원 정리
$result->free();
$conn->close();
?>