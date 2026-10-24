<?php
// DB 연결 파일 불러오기
require_once __DIR__ . '/db.php';

// 검색할 대상 아이디 (기본값 CJU)
$search_id = $_GET['id'] ?? 'CJU';

// 1. 특정 1개 데이터만 조회하는 쿼리 작성
$sql = "SELECT idx, id, name, cellphone, created_at FROM sampleusers WHERE id = ? LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $search_id);
$stmt->execute();

// 2. 결과 가져오기
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// 리소스 정리
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>사용자 정보 조회</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f6f9; margin: 40px; }
        .card { 
            max-width: 420px; 
            margin: 0 auto; 
            background: #ffffff; 
            border-radius: 8px; 
            padding: 24px; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08); 
            border: 1px solid #e1e4e8;
        }
        h2 { margin-top: 0; color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; font-size: 20px; }
        .form-group { margin-bottom: 14px; }
        .form-group label { display: block; font-weight: bold; color: #555; font-size: 13px; margin-bottom: 5px; }
        .form-group input { 
            width: 100%; 
            padding: 9px 10px; 
            box-sizing: border-box; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            font-size: 14px; 
            color: #333;
        }
        .form-group input[readonly] { background-color: #e9ecef; color: #6c757d; cursor: not-allowed; }
        .not-found { text-align: center; color: #d9534f; padding: 20px 0; }
        .btn-wrap { text-align: center; margin-top: 20px; }
        .btn-back { display: inline-block; padding: 8px 16px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; font-size: 13px; }
        .btn-back:hover { background-color: #5a6268; }
    </style>
</head>
<body>

<div class="card">
    <h2>회원 상세 정보</h2>

    <?php if ($user): ?>
        <form action="#" method="POST">
            <div class="form-group">
                <label for="idx">번호 (IDX)</label>
                <input type="text" id="idx" name="idx" value="<?php echo htmlspecialchars($user['idx']); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="id">아이디</label>
                <input type="text" id="id" name="id" value="<?php echo htmlspecialchars($user['id']); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="name">이름</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">
            </div>

            <div class="form-group">
                <label for="cellphone">전화번호</label>
                <input type="text" id="cellphone" name="cellphone" value="<?php echo htmlspecialchars($user['cellphone'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="created_at">등록일시</label>
                <input type="text" id="created_at" name="created_at" value="<?php echo htmlspecialchars($user['created_at']); ?>" readonly>
            </div>
        </form>
    <?php else: ?>
        <div class="not-found">
            <p><strong>'<?php echo htmlspecialchars($search_id); ?>'</strong>에 해당하는 회원 정보를 찾을 수 없습니다.</p>
        </div>
    <?php endif; ?>

    <div class="btn-wrap">
        <a href="javascript:history.back()" class="btn-back">뒤로 가기</a>
    </div>
</div>

</body>
</html>