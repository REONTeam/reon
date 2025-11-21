CREATE DATABASE IF NOT EXISTS `reon`;
USE `reon`;

# System
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

# Pokemon Crystal
CREATE TABLE `bxt_battle_tower_records` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `game_region` char(1) NOT NULL,
  `room` int(10) UNSIGNED NOT NULL,
  `level` tinyint(2) UNSIGNED NOT NULL COMMENT 'Battle tower level',
  `level_decode` varchar(16) DEFAULT NULL,
  `trainer_id` smallint(5) UNSIGNED NOT NULL COMMENT 'Trainer ID',
  `secret_id` smallint(5) UNSIGNED NOT NULL COMMENT 'Secret ID',
  `player_name` binary(7) NOT NULL COMMENT 'Name of trainer',
  `player_name_decode` varchar(32) DEFAULT NULL,
  `class` tinyint(3) UNSIGNED NOT NULL COMMENT 'Class of trainer',
  `class_decode` varchar(32) DEFAULT NULL,
  `pokemon1` binary(59) NOT NULL COMMENT 'Pokémon',
  `pokemon1_decode` text DEFAULT NULL,
  `pokemon2` binary(59) NOT NULL COMMENT 'Pokémon',
  `pokemon2_decode` text DEFAULT NULL,
  `pokemon3` binary(59) NOT NULL COMMENT 'Pokémon',
  `pokemon3_decode` text DEFAULT NULL,
  `message_start` binary(12) NOT NULL,
  `message_start_decode` text DEFAULT NULL,
  `message_win` binary(12) NOT NULL,
  `message_win_decode` text DEFAULT NULL,
  `message_lose` binary(12) NOT NULL,
  `message_lose_decode` text DEFAULT NULL,
  `num_trainers_defeated` tinyint(3) UNSIGNED NOT NULL,
  `num_turns_required` smallint(5) UNSIGNED NOT NULL,
  `damage_taken` smallint(5) UNSIGNED NOT NULL,
  `num_fainted_pokemon` tinyint(3) UNSIGNED NOT NULL,  
  `account_id` int(11) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `bxt_battle_tower_trainers` (
  `game_region` char(1) NOT NULL,
  `trainer_id` smallint(5) UNSIGNED NOT NULL COMMENT 'Trainer ID',
  `secret_id` smallint(5) UNSIGNED NOT NULL COMMENT 'Secret ID',
  `player_name` binary(7) NOT NULL COMMENT 'Name of trainer',
  `player_name_decode` varchar(32) DEFAULT NULL,
  `class` tinyint(3) UNSIGNED NOT NULL COMMENT 'Class of trainer',
  `class_decode` varchar(32) DEFAULT NULL,
  `pokemon1` binary(59) NOT NULL COMMENT 'Pokémon',
  `pokemon1_decode` text DEFAULT NULL,
  `pokemon2` binary(59) NOT NULL COMMENT 'Pokémon',
  `pokemon2_decode` text DEFAULT NULL,
  `pokemon3` binary(59) NOT NULL COMMENT 'Pokémon',
  `pokemon3_decode` text DEFAULT NULL,
  `message_start` binary(12) NOT NULL,
  `message_start_decode` text DEFAULT NULL,
  `message_win` binary(12) NOT NULL,
  `message_win_decode` text DEFAULT NULL,
  `message_lose` binary(12) NOT NULL,
  `message_lose_decode` text DEFAULT NULL,
  `account_id` int(11) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `bxt_battle_tower_leaders` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `game_region` char(1) NOT NULL,
  `player_name` binary(7) NOT NULL,
  `player_name_decode` varchar(32) DEFAULT NULL,
  `room` int(11) UNSIGNED NOT NULL,
  `level` tinyint(1) UNSIGNED NOT NULL,
  `level_decode` varchar(16) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `bxt_exchange` (
  `game_region` char(1) NOT NULL,
  `trainer_id` smallint(5) UNSIGNED NOT NULL COMMENT 'Trainer ID',
  `secret_id` smallint(5) UNSIGNED NOT NULL COMMENT 'Secret ID',
  `offer_gender` tinyint(1) UNSIGNED NOT NULL COMMENT 'Gender of Pokémon',
  `offer_gender_decode` varchar(16) DEFAULT NULL,
  `offer_species` tinyint(3) UNSIGNED NOT NULL COMMENT 'Decimal Pokémon ID.',
  `offer_species_decode` varchar(32) DEFAULT NULL,
  `request_gender` tinyint(1) UNSIGNED NOT NULL,
  `request_gender_decode` varchar(16) DEFAULT NULL,
  `request_species` tinyint(3) UNSIGNED NOT NULL,
  `request_species_decode` varchar(32) DEFAULT NULL,
  `player_name` binary(7) NOT NULL COMMENT 'Name of player',
  `player_name_decode` varchar(32) DEFAULT NULL,
  `pokemon` binary(65) NOT NULL COMMENT 'Pokémon',
  `pokemon_decode` text DEFAULT NULL,
  `mail` binary(47) NOT NULL COMMENT 'Held mail of Pokémon',
  `mail_decode` text DEFAULT NULL,
  `account_id` int(11) UNSIGNED NOT NULL,
  `email` varchar(30) NOT NULL COMMENT 'DION email',
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
  UNIQUE INDEX `UNIQUE` (`account_id`, `trainer_id`, `secret_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `bxt_news` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `game_region` char(1) NOT NULL,
  `ranking_category_1` tinyint(2) UNSIGNED DEFAULT NULL,
  `ranking_category_1_decode` varchar(80) DEFAULT NULL,
  `ranking_category_2` tinyint(2) UNSIGNED DEFAULT NULL,
  `ranking_category_2_decode` varchar(80) DEFAULT NULL,
  `ranking_category_3` tinyint(2) UNSIGNED DEFAULT NULL,
  `ranking_category_3_decode` varchar(80) DEFAULT NULL,
  `message` binary(12) NOT NULL,
  `message_decode` text DEFAULT NULL,
  `news_binary` blob NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `bxt_ranking` (
  `game_region` char(1) NOT NULL,
  `news_id` int(11) UNSIGNED NOT NULL,
  `category_id` tinyint(2) UNSIGNED NOT NULL,
  `category_id_decode` varchar(80) DEFAULT NULL,
  `score` int(11) UNSIGNED NOT NULL,
  `trainer_id` smallint(5) UNSIGNED NOT NULL,
  `secret_id` smallint(5) UNSIGNED NOT NULL,
  `player_name` binary(7) NOT NULL,
  `player_name_decode` varchar(32) DEFAULT NULL,
  `player_gender` tinyint(1) UNSIGNED NOT NULL,
  `player_gender_decode` varchar(16) DEFAULT NULL,
  `player_age` tinyint(3) UNSIGNED NOT NULL,
  `player_region` tinyint(3) UNSIGNED NOT NULL,
  `player_region_decode` varchar(64) DEFAULT NULL,
  `player_zip` varbinary(3) NOT NULL,
  `player_zip_decode` varchar(16) DEFAULT NULL,
  `player_message` binary(12) NOT NULL,
  `player_message_decode` text DEFAULT NULL,
  `account_id` int(11) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `bxt_ranking_categories` (
  `id` tinyint(2) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `ram_address` binary(2) NOT NULL,
  `size` tinyint(1) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT IGNORE INTO `bxt_ranking_categories` (`id`, `name`, `ram_address`, `size`) VALUES
(0, 'LAST HOF RECORD', 0x01A0, 4),
(1, 'LAST HOF STEPS', 0x05A0, 4),
(2, 'LAST HOF HEALED', 0x09A0, 3),
(3, 'LAST HOF BATTLES', 0x0DA0, 3),
(4, 'STEPS WALKED', 0x10A0, 4),
(5, 'BATTLE TOWER WINS', 0x14A0, 2),
(6, 'TMs/HMs TAUGHT', 0x18A0, 3),
(7, 'POKéMON BATTLES', 0x1BA0, 3),
(8, 'POKéMON ENCOUNTER', 0x1EA0, 3),
(9, 'TRAINER BATTLES', 0x21A0, 3),
(10, 'UNUSED', 0x24A0, 3),
(11, 'HOF ENTRIES', 0x27A0, 3),
(12, 'POKéMON CAUGHT', 0x2AA0, 3),
(13, 'POKéMON HOOKED', 0x2DA0, 3),
(14, 'EGGS HATCHED', 0x30A0, 3),
(15, 'POKéMON EVOLVED', 0x33A0, 3),
(16, 'FRUIT PICKED', 0x36A0, 3),
(17, 'PARTY HEALED', 0x39A0, 3),
(18, 'MYSTERY GIFT USED', 0x3CA0, 3),
(19, 'TRADES COMPLETED', 0x3FA0, 3),
(20, 'FLY USED', 0x42A0, 3),
(21, 'SURF USED', 0x45A0, 3),
(22, 'WATERFALL USED', 0x48A0, 3),
(23, 'TIMES WHITED OUT', 0x4BA0, 3),
(24, 'LUCKY NUMBER WINS', 0x4EA0, 3),
(25, 'TOTAL PHONE CALLS', 0x51A0, 3),
(26, 'UNUSED', 0x54A0, 3),
(27, 'COLOSSEUM BATTLES', 0x57A0, 3),
(28, 'SPLASH USED', 0x5AA0, 3),
(29, 'HEADBUTT USED', 0x5DA0, 3),
(30, 'UNUSED', 0x60A0, 3),
(31, 'COLOSSEUM WINS', 0x63A0, 3),
(32, 'COLOSSEUM LOSSES', 0x66A0, 3),
(33, 'COLOSSEUM DRAWS', 0x69A0, 3),
(34, 'SELF-KO MOVE USED', 0x6CA0, 3),
(35, 'SLOT WIN STREAK', 0x6FA0, 2),
(36, 'BEST SLOT STREAK', 0x71A0, 2),
(37, 'SLOT COINS WON', 0x73A0, 4),
(38, 'TOTAL MONEY', 0x77A0, 4),
(39, 'LARGEST MAGIKARP', 0x7BA0, 2),
(40, 'SMALLEST MAGIKARP', 0x7DA0, 2),
(41, 'BUG CONTEST SCORE', 0x7FA0, 2);

# Zen Nihon GT Senshuken (AGTJ)
CREATE TABLE `agtj_ghosts` (
 `course` tinyint(3) unsigned NOT NULL,
 `weather` tinyint(3) unsigned NOT NULL,
 `car` tinyint(3) unsigned NOT NULL,
 `trans` tinyint(3) unsigned NOT NULL,
 `gear` tinyint(3) unsigned NOT NULL,
 `steer` tinyint(3) unsigned NOT NULL,
 `brake` tinyint(3) unsigned NOT NULL,
 `tire` tinyint(3) unsigned NOT NULL,
 `aero` tinyint(3) unsigned NOT NULL,
 `excrs` tinyint(3) unsigned NOT NULL,
 `handicap` smallint(5) unsigned NOT NULL,
 `name` binary(22) NOT NULL,
 `time` int(11) unsigned NOT NULL,
 `date` datetime NOT NULL,
 `id` int(11) unsigned NOT NULL,
 `input_data` blob(12124),
 `dl_ok` datetime,
 PRIMARY KEY (`id`)
);

# Exciting Bass (AMGJ)
CREATE TABLE `amgj_rankings` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `ident` text(46) NOT NULL,
 `name` binary(16) NOT NULL,
 `blood` tinyint(3) unsigned NOT NULL,
 `gender` tinyint(3) unsigned NOT NULL,
 `age` tinyint(3) unsigned NOT NULL,
 `weight` int(11) unsigned NOT NULL,
 PRIMARY KEY (`id`)
);

# Yu-Gi-Oh! Duel Monsters 5 Expert I (AY5J)
CREATE TABLE `ay5j_rankings` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `ident` text(64) NOT NULL,
 `name` binary(32) NOT NULL,
 `phone_no` binary(20) NOT NULL,
 `score` int(11) unsigned NOT NULL,
 PRIMARY KEY (`id`)
);
