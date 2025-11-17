CREATE DATABASE IF NOT EXISTS `reon`;
USE `reon`;

#System
CREATE TABLE `sys_users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT
  `email` varchar(254) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dion_ppp_id` varchar(10) NOT NULL,
  `dion_email_local` varchar(8) NOT NULL,
  `log_in_password` varchar(8) NOT NULL,
  `money_spent` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `sys_email_change` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `new_email` varchar(254) NOT NULL,
  `secret` varchar(48) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `sys_password_reset` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `secret` varchar(48) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `sys_signup` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT
  `email` varchar(254) NOT NULL,
  `secret` varchar(48) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `sys_inbox` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT
  `sender` varchar(254) NOT NULL,
  `recipient` int(11) UNSIGNED NOT NULL,
  `message` blob NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

#AMKJ
CREATE TABLE `amkj_user_map` (
  `player_id` binary(16) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `amkj_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT
  `file_name` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `next_start_date` date NOT NULL,
  `next_end_date` date NOT NULL,
  `entry_start_date` date NOT NULL,
  `entry_end_date` date NOT NULL,
  `ranking_start_date` date NOT NULL,
  `ranking_end_date` date NOT NULL,
  `coins_enabled` tinyint(1) NOT NULL,
  `items_enabled` tinyint(1) NOT NULL,
  `start_item_triple_shroom_enabled` tinyint(1) NOT NULL,
  `shrooms_only_enabled` tinyint(1) NOT NULL,
  `cpu_enabled` tinyint(1) NOT NULL,
  `character` tinyint(1) NOT NULL,
  `start_coins` tinyint(1) NOT NULL,
  `five_laps_enabled` tinyint(1) NOT NULL,
  `course` tinyint(2) NOT NULL,
  `num_attempts` tinyint(2) NOT NULL,
  `message` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `amkj_ghosts` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT
  `player_id` binary(16) NOT NULL,
  `course_no` tinyint(3) UNSIGNED NOT NULL,
  `driver` tinyint(3) UNSIGNED NOT NULL,
  `name` binary(5) NOT NULL,
  `state` tinyint(3) UNSIGNED NOT NULL,
  `unk18` smallint(5) UNSIGNED NOT NULL,
  `course` tinyint(3) UNSIGNED NOT NULL,
  `time` smallint(5) UNSIGNED NOT NULL,
  `input_data` blob NOT NULL,
  `full_name` binary(16) NOT NULL,
  `phone_number` binary(12) NOT NULL,
  `postal_code` binary(8) NOT NULL,
  `address` binary(128) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `amkj_ghosts_mobilegp` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT
  `gp_id` int(11) UNSIGNED NOT NULL,
  `player_id` binary(16) NOT NULL,
  `course_no` tinyint(3) UNSIGNED NOT NULL,
  `driver` tinyint(3) UNSIGNED NOT NULL,
  `name` binary(5) NOT NULL,
  `state` tinyint(3) UNSIGNED NOT NULL,
  `unk18` smallint(5) UNSIGNED NOT NULL,
  `course` tinyint(3) UNSIGNED NOT NULL,
  `time` smallint(5) UNSIGNED NOT NULL,
  `input_data` blob NOT NULL,
  `full_name` binary(16) NOT NULL,
  `phone_number` binary(12) NOT NULL,
  `postal_code` binary(8) NOT NULL,
  `address` binary(128) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `amoj_ranking` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT
  `name` binary(4) NOT NULL,
  `email` varchar(32) NOT NULL,
  `today` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `points` int(11) UNSIGNED NOT NULL,
  `money` int(11) UNSIGNED NOT NULL,
  `gender` tinyint(3) UNSIGNED NOT NULL,
  `age` tinyint(3) UNSIGNED NOT NULL,
  `state` tinyint(3) UNSIGNED NOT NULL,
  `today2` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `amoj_news` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT
  `text` mediumtext NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

#BXT_
CREATE TABLE `bxt_exchange` (
  `game_region` char(1) NOT NULL,
  `trainer_id` smallint(5) UNSIGNED NOT NULL COMMENT 'Trainer ID',
  `secret_id` smallint(5) UNSIGNED NOT NULL COMMENT 'Secret ID',
  `offer_gender` tinyint(1) UNSIGNED NOT NULL COMMENT 'Gender of Pokémon',
  `offer_species` tinyint(3) UNSIGNED NOT NULL COMMENT 'Decimal Pokémon ID.',
  `request_gender` tinyint(1) UNSIGNED NOT NULL,
  `request_species` tinyint(3) UNSIGNED NOT NULL,
  `player_name` binary(7) NOT NULL COMMENT 'Name of player',
  `pokemon` binary(65) NOT NULL COMMENT 'Pokémon',
  `mail` binary(47) NOT NULL COMMENT 'Held mail of Pokémon',
  `account_id` int(11) UNSIGNED NOT NULL,
  `email` varchar(30) NOT NULL COMMENT 'DION email',
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `UNIQUE` (`account_id`,`trainer_id`,`secret_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Pokémon Trade Corner information';

CREATE TABLE `bxt_battle_tower_records` (
  `game_region` char(1) NOT NULL,
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT
  `room` int(10) UNSIGNED NOT NULL,
  `level` tinyint(1) UNSIGNED NOT NULL,
  `trainer_id` smallint(5) UNSIGNED NOT NULL,
  `secret_id` smallint(5) UNSIGNED NOT NULL,
  `player_name` binary(7) NOT NULL,
  `class` tinyint(1) NOT NULL,
  `pokemon1` binary(59) NOT NULL,
  `pokemon2` binary(59) NOT NULL,
  `pokemon3` binary(59) NOT NULL,
  `message_start` binary(8) NOT NULL,
  `message_win` binary(8) NOT NULL,
  `message_lose` binary(8) NOT NULL,
  `num_trainers_defeated` tinyint(3) UNSIGNED NOT NULL,
  `num_turns_required` smallint(5) UNSIGNED NOT NULL,
  `damage_taken` smallint(5) UNSIGNED NOT NULL,
  `num_fainted_pokemon` tinyint(3) UNSIGNED NOT NULL,
  `account_id` int(11) UNSIGNED NOT NULL,
  `email` varchar(30) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `bxt_battle_tower_trainers` (
  `game_region` char(1) NOT NULL,
  `no` tinyint(1) UNSIGNED NOT NULL,
  `room` int(10) UNSIGNED NOT NULL,
  `level` tinyint(1) UNSIGNED NOT NULL,
  `player_name` binary(7) NOT NULL,
  `class` tinyint(1) NOT NULL,
  `pokemon1` binary(59) NOT NULL,
  `pokemon2` binary(59) NOT NULL,
  `pokemon3` binary(59) NOT NULL,
  `message_start` binary(8) NOT NULL,
  `message_win` binary(8) NOT NULL,
  `message_lose` binary(8) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`no`,`game_region`,`room`,`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `bxt_battle_tower_leaders` (
  `game_region` char(1) NOT NULL,
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT
  `player_name` binary(7) NOT NULL,
  `room` int(11) UNSIGNED NOT NULL,
  `level` tinyint(1) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `bxt_ranking_categories` (
  `id` tinyint(2) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `ram_address` binary(2) NOT NULL,
  `size` tinyint(1) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `bxt_news` (
  `game_region` char(1) NOT NULL,
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT
  `ranking_category_1` tinyint(2) UNSIGNED DEFAULT NULL,
  `ranking_category_2` tinyint(2) UNSIGNED DEFAULT NULL,
  `ranking_category_3` tinyint(2) UNSIGNED DEFAULT NULL,
  `message` varbinary(100) NOT NULL,
  `news_binary` blob NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `bxt_ranking` (
  `game_region` char(1) NOT NULL,
  `news_id` int(11) UNSIGNED NOT NULL,
  `category_id` tinyint(2) UNSIGNED NOT NULL,
  `trainer_id` smallint(5) UNSIGNED NOT NULL,
  `secret_id` smallint(5) UNSIGNED NOT NULL,
  `player_name` binary(7) NOT NULL,
  `player_gender` tinyint(1) UNSIGNED NOT NULL,
  `player_age` tinyint(3) UNSIGNED NOT NULL,
  `player_region` tinyint(3) UNSIGNED NOT NULL,
  `player_zip` binary(3) NOT NULL,
  `player_message` binary(12) NOT NULL,
  `score` int(11) UNSIGNED NOT NULL,
  `account_id` int(11) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`game_region`,`news_id`,`category_id`,`account_id`,`trainer_id`,`secret_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT IGNORE INTO `bxt_ranking_categories` (`id`, `name`, `ram_address`, `size`) VALUES
(0, 'Play time when last entered the Hall of Fame', 0x01A0, 4),
(1, 'Step count when last entered the Hall of Fame', 0x05A0, 4),
(2, 'Number of times the party was healed when last entered the Hall of Fame', 0x09A0, 3),
(3, 'Number of battles when last entered the Hall of Fame', 0x0DA0, 3),
(4, 'Step count', 0x10A0, 4),
(5, 'Number of Battle Tower wins', 0x14A0, 2),
(6, 'Number of times TMs and HMs have been taught', 0x18A0, 3),
(7, 'Number of battles', 0x1BA0, 3),
(8, 'Number of wild Pokémon battles', 0x1EA0, 3),
(9, 'Number of Trainer battles', 0x21A0, 3),
(10, 'Unused', 0x24A0, 3),
(11, 'Number of Hall of Fame inductions', 0x27A0, 3),
(12, 'Number of wild Pokémon caught', 0x2AA0, 3),
(13, 'Number of hooked Pokémon encounters', 0x2DA0, 3),
(14, 'Number of Eggs hatched', 0x30A0, 3),
(15, 'Number of Pokémon evolved', 0x33A0, 3),
(16, 'Number of Berries and Apricorns picked', 0x36A0, 3),
(17, 'Number of times the party is healed', 0x39A0, 3),
(18, 'Number of times Mystery Gift is used', 0x3CA0, 3),
(19, 'Number of trades', 0x3FA0, 3),
(20, 'Number of uses of field move Fly', 0x42A0, 3),
(21, 'Number of uses of field move Surf', 0x45A0, 3),
(22, 'Number of uses of field move Waterfall', 0x48A0, 3),
(23, 'Number of times the player whited out', 0x4BA0, 3),
(24, 'Number of Lucky Number Show prizes won', 0x4EA0, 3),
(25, 'Number of Phone calls made and received', 0x51A0, 3),
(26, 'Unused', 0x54A0, 3),
(27, 'Number of Colosseum battles', 0x57A0, 3),
(28, 'Number of times players Pokémon used Splash', 0x5AA0, 3),
(29, 'Number of tree Pokémon encounters', 0x5DA0, 3),
(30, 'Unused', 0x60A0, 3),
(31, 'Number of Colosseum wins', 0x63A0, 3),
(32, 'Number of Colosseum losses', 0x66A0, 3),
(33, 'Number of Colosseum ties', 0x69A0, 3),
(34, 'Number of times players Pokémon used SelfDestruct or Explosion', 0x6CA0, 3),
(35, 'Current streak of consecutive slot machine wins', 0x6FA0, 2),
(36, 'Longest streak of consecutive slot machine wins', 0x71A0, 2),
(37, 'Total coins won from slot machines', 0x73A0, 4),
(38, 'Total money earned from battles (including Pay Day)', 0x77A0, 4),
(39, 'Largest Magikarp measured', 0x7BA0, 2),
(40, 'Smallest Magikarp measured', 0x7DA0, 2),
(41, 'Bug-Catching Contest high score', 0x7FA0, 2);