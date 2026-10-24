<?php
// db.php 연결 (경로는 프로젝트 구조에 맞게 유지)
require_once __DIR__ . '/../db.php';

// 등록할 데이터 정의
$id = "CJU3";
$name = "디지털보안";
$cellphone = "010-1234-5678";
$age = 30;

// 1. 화면 출력 확인
echo "ID: " . htmlspecialchars($id) . "<br>";
echo "name: " . htmlspecialchars($name) . "<br>";
echo "cellphone: " . htmlspecialchars($cellphone) . "<br>";
echo "age: " . htmlspecialchars($age) . "<br><hr>";

// 2. SQL 쿼리 준비 (SQL Injection 방지를 위해 Prepared Statement 사용)
$sql = "INSERT INTO sampleusers (id, name, cellphone, age) VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("쿼리 준비 실패: " . $conn->error);
}

// 3. 파라미터 바인딩 ("sssi" -> 문자열(string) 3개, 정수(int) 1개)
$stmt->bind_param("sssi", $id, $name, $cellphone, $age);

// 4. 실행 및 결과 확인
if ($stmt->execute()) {
    echo "데이터가 성공적으로 등록되었습니다. (등록 번호: " . $stmt->insert_id . ")<br>";
} else {
    // 중복 아이디 에러 등 처리
    echo "데이터 등록 실패: " . htmlspecialchars($stmt->error) . "<br>";
}

// 5. 연결 정리
$stmt->close();
$conn->close();
?>