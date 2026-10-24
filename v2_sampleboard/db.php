<?php
// SimpleBoard/v2/db.php

// 1. 세션 시작 (모든 페이지에서 로그인 상태 유지용)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. DB 접속 설정 정보
$db_host = '192.168.100.20';
$db_user = 'root';
$db_pass = 'security';
$db_name = 'sample_board';
$db_port = 3306;

// 3. MySQLi 연결 생성
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);

// 4. 연결 에러 체크
if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

// 5. 한글 인코딩 설정
$conn->set_charset("utf8mb4");
?>