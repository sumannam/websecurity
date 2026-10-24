<?php
// POST 요청 확인
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.php");
    exit;
}

// 1. 전송된 폼 데이터 수신 및 공백 제거
$id = trim($_POST['id'] ?? '');
$pw = $_POST['pw'] ?? '';
$pw_confirm = $_POST['pw_confirm'] ?? '';
$name = trim($_POST['name'] ?? '');
$cellphone = trim($_POST['cellphone'] ?? '');
$email = trim($_POST['email'] ?? '');

echo "ID: ".$id."<br>";
echo "PW: ".$pw."<br>";
echo "name: ".$name."<br>";
echo "cellphone: ".$cellphone."<br>";
?>