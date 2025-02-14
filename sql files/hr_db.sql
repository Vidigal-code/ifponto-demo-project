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

 Date: 13/02/2025 23:40:56
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for confirm_point_entry
-- ----------------------------
DROP TABLE IF EXISTS `confirm_point_entry`;
CREATE TABLE `confirm_point_entry`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
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
  `work_entry` datetime NULL DEFAULT NULL,
  `status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  CONSTRAINT `confirm_point_entry_code` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of confirm_point_entry
-- ----------------------------
INSERT INTO `confirm_point_entry` VALUES (19, 8, 'Kauan Vidigal', 'vidigal@gmail.com', '$2y$12$UIeF3bPl1DYsItkiMzJBtubbwfSNZz.Ss7AwU/S9tjjpyfeBInezC', '015.857.560-16', '15.069.390-4', NULL, 'Dev - Full Stack', 'Apple-IOS', 1, 3900.00, 'Vespertino', '2025-02-13 14:00:00', 'Entrada no prazo');

-- ----------------------------
-- Table structure for confirm_point_exit
-- ----------------------------
DROP TABLE IF EXISTS `confirm_point_exit`;
CREATE TABLE `confirm_point_exit`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
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
  `work_exit` datetime NULL DEFAULT NULL,
  `status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  CONSTRAINT `confirm_point_exit_code` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of confirm_point_exit
-- ----------------------------
INSERT INTO `confirm_point_exit` VALUES (22, 8, 'Kauan Vidigal', 'vidigal@gmail.com', '$2y$12$UIeF3bPl1DYsItkiMzJBtubbwfSNZz.Ss7AwU/S9tjjpyfeBInezC', '015.857.560-16', '15.069.390-4', NULL, 'Dev - Full Stack', 'Apple-IOS', 1, 3900.00, 'Vespertino', '2025-02-13 19:00:00', 'Saída no prazo');

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
  `work_entry` datetime NULL DEFAULT NULL,
  `work_exit` datetime NULL DEFAULT NULL,
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
INSERT INTO `users` VALUES (8, 'Kauan Vidigal', 'vidigal@gmail.com', '$2y$12$qG8AgFvVRxRTUSuoGIKxa.SHmQYJjVWK5wPAPS.nfgWAy4nEl/9xi', '015.857.560-16', '15.069.390-4', NULL, 'Dev - Full Stack', 'Apple', 1, 3900.00, 'Vespertino', '2025-02-13 14:00:00', '2025-02-13 19:00:00', 1, '2025-02-12 21:37:51');
INSERT INTO `users` VALUES (10, 'Fernando Oliveira', 'fernando.oliveira@gmail.com', '$2y$12$LLRt0SqriDl5UiLdXJ4Y1upo6TZ6luW9vWou7OsXibJg9UBWEZV4G', '015.857.560-24', '15.069.390-8', NULL, 'Back - end', 'Apple', 0, 3900.00, 'Vespertino', '2025-02-13 14:00:00', '2025-02-13 19:00:00', 0, '2025-02-13 22:01:10');
INSERT INTO `users` VALUES (11, 'Maria', 'Maria@gmail.com', '$2y$12$PUaIWZLqtW3zGm/KbXan7OIJ/lxaJePAEw9PR59l0RpFzkWaC19Qq', '015.857.560-50', '15.069.398-4', NULL, 'Dev - Front-end', 'Apple', 0, 2900.00, 'Vespertino', '2025-02-13 14:00:00', '2025-02-13 19:00:00', 0, '2025-02-13 22:02:55');

SET FOREIGN_KEY_CHECKS = 1;
