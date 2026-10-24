<?php
// GET 요청 확인
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header("Location: register.php");
    exit;
}

// 1. 전송된 쿼리스트링 데이터 수신 및 공백 제거
$id = trim($_GET['id'] ?? '');
$pw = $_GET['pw'] ?? '';
$pw_confirm = $_GET['pw_confirm'] ?? '';
$name = trim($_GET['name'] ?? '');
$cellphone = trim($_GET['cellphone'] ?? '');
$email = trim($_GET['email'] ?? '');

echo "ID: ".$id."<br>";
echo "PW: ".$pw."<br>";
echo "name: ".$name."<br>";
echo "cellphone: ".$cellphone."<br>";
?>