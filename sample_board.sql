-- --------------------------------------------------------
-- 호스트:                          192.168.100.20
-- 서버 버전:                        10.3.39-MariaDB-0ubuntu0.20.04.2 - Ubuntu 20.04
-- 서버 OS:                        debian-linux-gnu
-- HeidiSQL 버전:                  12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- sample_board 데이터베이스 구조 내보내기
DROP DATABASE IF EXISTS `sample_board`;
CREATE DATABASE IF NOT EXISTS `sample_board` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `sample_board`;

-- 테이블 sample_board.posts 구조 내보내기
DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '게시글 고유 번호',
  `author_id` varchar(50) NOT NULL COMMENT '작성자 ID (USERS.id 참조)',
  `title` varchar(200) NOT NULL COMMENT '제목',
  `content` text NOT NULL COMMENT '내용',
  `views` int(11) NOT NULL DEFAULT 0 COMMENT '조회수',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '작성 일시',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '게시글 수정 일시',
  PRIMARY KEY (`id`),
  KEY `fk_posts_users` (`author_id`),
  CONSTRAINT `fk_posts_users` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

-- 테이블 sample_board.users 구조 내보내기
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` varchar(50) NOT NULL COMMENT '사용자 식별 ID',
  `pw` varchar(100) NOT NULL COMMENT '비밀번호',
  `name` varchar(50) NOT NULL COMMENT '사용자 이름',
  `cellphone` varchar(20) DEFAULT NULL COMMENT '전화번호',
  `email` varchar(100) DEFAULT NULL COMMENT '이메일',
  `role` varchar(10) NOT NULL DEFAULT 'USER' COMMENT '권한 (USER / ADMIN)',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '가입 일시',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '회원정보 수정 일시',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 내보낼 데이터가 선택되어 있지 않습니다.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
