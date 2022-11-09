/*
Navicat MySQL Data Transfer

Source Server         : LOCALHOST
Source Server Version : 100119
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 100119
File Encoding         : 65001

Date: 2017-08-11 22:30:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for levels
-- ----------------------------
DROP TABLE IF EXISTS `levels`;
CREATE TABLE `levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of levels
-- ----------------------------
INSERT INTO `levels` VALUES ('1', 'superadmin', 'Superadmin', '2016-12-03 13:11:34', '2016-12-03 13:11:37', null);
INSERT INTO `levels` VALUES ('2', 'admin', 'Admin', '2016-12-03 13:11:57', '2016-12-03 13:12:00', null);
INSERT INTO `levels` VALUES ('3', 'user', 'User', '2016-12-03 13:12:07', '2016-12-03 13:12:10', null);

-- ----------------------------
-- Table structure for menus
-- ----------------------------
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `link` varchar(50) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `icon` varchar(50) DEFAULT NULL,
  `no_urut` int(11) NOT NULL,
  `is_heading` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of menus
-- ----------------------------
INSERT INTO `menus` VALUES ('1', 'Home', 'home', '0', 'icon-home', '1', '0');
INSERT INTO `menus` VALUES ('2', 'Pengaturan', 'pengaturan', '0', 'icon-settings', '3', '0');
INSERT INTO `menus` VALUES ('3', 'Menu', 'pengaturan/menu', '2', 'icon-grid', '1', '0');
INSERT INTO `menus` VALUES ('4', 'Level', 'pengaturan/level', '2', 'icon-layers', '2', '0');
INSERT INTO `menus` VALUES ('5', 'Hak Akses', 'pengaturan/hak-akses', '2', 'icon-note', '3', '0');
INSERT INTO `menus` VALUES ('6', 'User', 'pengaturan/user', '2', 'icon-users', '4', '0');
INSERT INTO `menus` VALUES ('7', 'Main Menu', '', '0', null, '2', '1');

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menu_id` (`menu_id`,`level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES ('1', '1', '1');
INSERT INTO `permissions` VALUES ('7', '1', '2');
INSERT INTO `permissions` VALUES ('11', '1', '3');
INSERT INTO `permissions` VALUES ('2', '2', '1');
INSERT INTO `permissions` VALUES ('8', '2', '2');
INSERT INTO `permissions` VALUES ('3', '3', '1');
INSERT INTO `permissions` VALUES ('4', '4', '1');
INSERT INTO `permissions` VALUES ('6', '5', '1');
INSERT INTO `permissions` VALUES ('9', '5', '2');
INSERT INTO `permissions` VALUES ('5', '6', '1');
INSERT INTO `permissions` VALUES ('10', '6', '2');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `username` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_id` int(11) NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('admin', 'Admin', 'admin@gmail.com', '$2y$10$uICAZyiBZzyDEc4SUSVfJeWsFL6X/4oQvZE1lH0pjY/66pqWn4rHe', '2', null, '1', '2017-08-11 15:14:44', '2017-08-11 15:14:44');
INSERT INTO `users` VALUES ('pandudud', 'Pandu Yudhantara', 'pandudud@gmail.com', '$2y$10$vTw47ajbTfgfdUTbODRal.S1/CNHOl7l76eEkbeMiLp/SqqzUdpsG', '1', null, '1', '2016-12-04 04:39:07', '2017-04-13 15:58:39');
INSERT INTO `users` VALUES ('user', 'User', 'user@gmail.com', '$2y$10$ALvIRVXSLPMYsw4OUlCseOALP5OEsa/p8mNLe4HndD4vaDYYoS5Au', '3', null, '1', '2017-08-11 15:18:01', '2017-08-11 15:22:00');
SET FOREIGN_KEY_CHECKS=1;
