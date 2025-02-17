/*
 Navicat Premium Data Transfer

 Source Server         : MYSQL TEST PROJECT
 Source Server Type    : MySQL
 Source Server Version : 90200
 Source Host           : localhost:3306
 Source Schema         : hr_db

 Target Server Type    : MySQL
 Target Server Version : 90200
 File Encoding         : 65001

 Date: 17/02/2025 20:30:06
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `cpf` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `rg` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `lang` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'user',
  `business` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `is_an_admin` tinyint(1) NULL DEFAULT 0,
  `salary` decimal(10, 2) NULL DEFAULT NULL,
  `period` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `work_entry` time NULL DEFAULT NULL,
  `work_exit` time NULL DEFAULT NULL,
  `approved_account` tinyint(1) NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `email`(`email` ASC) USING BTREE,
  UNIQUE INDEX `cpf`(`cpf` ASC) USING BTREE,
  UNIQUE INDEX `rg`(`rg` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (8, 'Kauan Vidigal', 'vidigal@gmail.com', '$2y$12$MvwciIgW3C36nq0iSIgxO.F1yy9Rgqw4IV2JLB/SD7asuQdaIXKbq', '015.857.560-16', '15.069.390-4', NULL, 'Dev - Full Stack', 'Apple', 1, 3900.00, 'Vespertino', '14:00:00', '19:00:00', 1, '2025-02-12 21:37:51');
INSERT INTO `users` VALUES (10, 'Fernando Oliveira', 'fernando.oliveira@gmail.com', '$2y$12$LLRt0SqriDl5UiLdXJ4Y1upo6TZ6luW9vWou7OsXibJg9UBWEZV4G', '015.857.560-24', '15.069.390-8', NULL, 'Back - end', 'Apple', 0, 3900.00, 'Vespertino', '14:00:00', '19:00:00', 0, '2025-02-13 22:01:10');
INSERT INTO `users` VALUES (11, 'Maria', 'Maria@gmail.com', '$2y$12$PUaIWZLqtW3zGm/KbXan7OIJ/lxaJePAEw9PR59l0RpFzkWaC19Qq', '015.857.560-50', '15.069.398-4', NULL, 'Dev - Front-end', 'Apple', 0, 2900.00, 'Vespertino', '14:00:00', '19:00:00', 0, '2025-02-13 22:02:55');

SET FOREIGN_KEY_CHECKS = 1;
