/*
 Navicat Premium Data Transfer

 Source Server         : LOCALHOST
 Source Server Type    : MySQL
 Source Server Version : 90100
 Source Host           : localhost:3306
 Source Schema         : fractal

 Target Server Type    : MySQL
 Target Server Version : 90100
 File Encoding         : 65001

 Date: 13/02/2025 02:28:52
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

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
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of confirm_point_exit
-- ----------------------------
INSERT INTO `confirm_point_exit` VALUES (22, 8, 'Kauan Vidigal', 'vidigal@gmail.com', '$2y$12$UIeF3bPl1DYsItkiMzJBtubbwfSNZz.Ss7AwU/S9tjjpyfeBInezC', '015.857.560-16', '15.069.390-4', NULL, 'Dev - Full Stack', 'Apple-IOS', 1, 3900.00, 'Vespertino', '2025-02-13 19:00:00', 'Saída no prazo');

SET FOREIGN_KEY_CHECKS = 1;
