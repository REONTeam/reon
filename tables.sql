CREATE SCHEMA if NOT EXISTS `db`;
USE `db`;

# System
CREATE TABLE `users` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `dion_id` varchar(10) NOT NULL,
 `email_id` varchar(8) NOT NULL,
 `password` varchar(8) NOT NULL,
 `money_spent` int(11) NOT NULL,
 PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `mail` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `sender` text NOT NULL,
 `recipient` text NOT NULL,
 `date` timestamp NOT NULL DEFAULT current_timestamp(),
 `content` blob NOT NULL,
 PRIMARY KEY (`id`)
);

# Pokemon Crystal (BXTJ)
CREATE TABLE `pkm_trades` (
 `tradeid` VARCHAR(11) NOT NULL COMMENT 'Per-trade UUID.',
 `entry_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Current time at entry.',
 `email` VARCHAR(25) NOT NULL COMMENT 'DION email',
 `trainer_id` VARCHAR(4) NOT NULL COMMENT 'Trainer ID',
 `secret_id` VARCHAR(4) NOT NULL COMMENT 'Secret ID',
 `offer_gender` ENUM('00','01','02','03') NOT NULL DEFAULT '00' COMMENT 'Gender of Pokémon',
 `offer_species` INT(3) NOT NULL DEFAULT '0' COMMENT 'Decimal Pokémon ID.',
 `request_gender` ENUM('00','01','02','03') NOT NULL DEFAULT '00',
 `request_species` INT(3) NOT NULL DEFAULT '0',
 `file` TEXT NOT NULL COMMENT 'Pokémon in B64',
 UNIQUE INDEX `UNIQUE` (`tradeid`, `email`)
)
COMMENT='Pokémon Trade Corner information'
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS `bxtj_battle_tower_records` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `room` int(10) unsigned NOT NULL,
 `level` tinyint(1) unsigned NOT NULL,
 `email` varchar(30) NOT NULL,
 `trainer_id` smallint(5) unsigned NOT NULL,
 `secret_id` smallint(5) unsigned NOT NULL,
 `name` binary(5) NOT NULL,
 `class` tinyint(1) NOT NULL,
 `pokemon1` binary(54) NOT NULL,
 `pokemon2` binary(54) NOT NULL,
 `pokemon3` binary(54) NOT NULL,
 `message_start` binary(12) NOT NULL,
 `message_win` binary(12) NOT NULL,
 `message_lose` binary(12) NOT NULL,
 `num_trainers_defeated` tinyint(3) unsigned NOT NULL,
 `num_turns_required` smallint(5) unsigned NOT NULL,
 `damage_taken` smallint(5) unsigned NOT NULL,
 `num_fainted_pokemon` tinyint(3) unsigned NOT NULL,
 PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `bxtj_battle_tower_trainers` (
 `no` tinyint(1) unsigned NOT NULL,
 `room` int(10) unsigned NOT NULL,
 `level` tinyint(1) unsigned NOT NULL,
 `name` binary(5) NOT NULL,
 `class` tinyint(1) NOT NULL,
 `pokemon1` binary(54) NOT NULL,
 `pokemon2` binary(54) NOT NULL,
 `pokemon3` binary(54) NOT NULL,
 `message_start` binary(12) NOT NULL,
 `message_win` binary(12) NOT NULL,
 `message_lose` binary(12) NOT NULL,
 PRIMARY KEY (`no`,`room`,`level`)
);
CREATE TABLE IF NOT EXISTS `bxtj_battle_tower_leaders` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `name` binary(5) NOT NULL,
 `room` int(11) unsigned NOT NULL,
 `level` tinyint(1) unsigned NOT NULL,
 PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `bxtj_ranking` (
 `news_id` int(11) unsigned NOT NULL,
 `category_id` tinyint(2) unsigned NOT NULL,
 `account_id` int(11) unsigned NOT NULL,
 `trainer_id` smallint(5) unsigned NOT NULL,
 `secret_id` smallint(5) unsigned NOT NULL,
 `player_name` binary(5) NOT NULL,
 `player_gender` tinyint(1) unsigned  NOT NULL,
 `player_age` tinyint(3) unsigned  NOT NULL,
 `player_region` tinyint(3) unsigned  NOT NULL,
 `player_zip` smallint(3) NOT NULL,
 `player_message` binary(12) NOT NULL,
 `score` int(11) unsigned NOT NULL,
 `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`news_id`, `category_id`, `account_id`, `trainer_id`, `secret_id`)
);

# Pokemon Crystal (BXTE)
CREATE TABLE IF NOT EXISTS `bxte_battle_tower_records` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `room` int(10) unsigned NOT NULL,
 `level` tinyint(1) unsigned NOT NULL,
 `email` varchar(30) NOT NULL,
 `trainer_id` smallint(5) unsigned NOT NULL,
 `secret_id` smallint(5) unsigned NOT NULL,
 `name` binary(7) NOT NULL,
 `class` tinyint(1) NOT NULL,
 `pokemon1` binary(59) NOT NULL,
 `pokemon2` binary(59) NOT NULL,
 `pokemon3` binary(59) NOT NULL,
 `message_start` binary(8) NOT NULL,
 `message_win` binary(8) NOT NULL,
 `message_lose` binary(8) NOT NULL,
 `num_trainers_defeated` tinyint(3) unsigned NOT NULL,
 `num_turns_required` smallint(5) unsigned NOT NULL,
 `damage_taken` smallint(5) unsigned NOT NULL,
 `num_fainted_pokemon` tinyint(3) unsigned NOT NULL,
 PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `bxte_battle_tower_trainers` (
 `no` tinyint(1) unsigned NOT NULL,
 `room` int(10) unsigned NOT NULL,
 `level` tinyint(1) unsigned NOT NULL,
 `name` binary(7) NOT NULL,
 `class` tinyint(1) NOT NULL,
 `pokemon1` binary(59) NOT NULL,
 `pokemon2` binary(59) NOT NULL,
 `pokemon3` binary(59) NOT NULL,
 `message_start` binary(8) NOT NULL,
 `message_win` binary(8) NOT NULL,
 `message_lose` binary(8) NOT NULL,
 PRIMARY KEY (`no`,`room`,`level`)
);
CREATE TABLE IF NOT EXISTS `bxte_battle_tower_leaders` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `name` binary(7) NOT NULL,
 `room` int(11) unsigned NOT NULL,
 `level` tinyint(1) unsigned NOT NULL,
 PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `bxte_ranking` (
 `news_id` int(11) unsigned NOT NULL,
 `category_id` tinyint(2) unsigned NOT NULL,
 `account_id` int(11) unsigned NOT NULL,
 `trainer_id` smallint(5) unsigned NOT NULL,
 `secret_id` smallint(5) unsigned NOT NULL,
 `player_name` binary(7) NOT NULL,
 `player_gender` tinyint(1) unsigned NOT NULL,
 `player_age` tinyint(3) unsigned NOT NULL,
 `player_region` tinyint(3) unsigned NOT NULL,
 `player_zip` binary(3) NOT NULL,
 `player_message` binary(8) NOT NULL,
 `score` int(11) unsigned NOT NULL,
 `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`news_id`, `category_id`, `account_id`, `trainer_id`, `secret_id`)
);

# Pokemon Crystal general
CREATE TABLE IF NOT EXISTS `bxt_ranking_categories` (
 `id` tinyint(2) unsigned NOT NULL,
 `name` varchar(80) NOT NULL,
 `ram_address` binary(2) NOT NULL,
 `size` tinyint(1) unsigned NOT NULL,
 PRIMARY KEY (`id`)
);
# values by hacky
INSERT IGNORE INTO `bxt_ranking_categories`
(`id`, `name`, `ram_address`, `size`)
VALUES
(0, "Play time when last entered the Hall of Fame", 0x01A0, 4),
(1, "Step count when last entered the Hall of Fame", 0x05A0, 4),
(2, "Number of times the party was healed when last entered the Hall of Fame", 0x09A0, 3),
(3, "Number of battles when last entered the Hall of Fame", 0x0DA0, 3),
(4, "Step count", 0x10A0, 4),
(5, "Number of Battle Tower wins", 0x14A0, 2),
(6, "Number of times TMs and HMs have been taught", 0x18A0, 3),
(7, "Number of battles", 0x1BA0, 3),
(8, "Number of wild Pokémon battles", 0x1EA0, 3),
(9, "Number of Trainer battles", 0x21A0, 3),
(10, "Unused", 0x24A0, 3),
(11, "Number of Hall of Fame inductions", 0x27A0, 3),
(12, "Number of wild Pokémon caught", 0x2AA0, 3),
(13, "Number of hooked Pokémon encounters", 0x2DA0, 3),
(14, "Number of Eggs hatched", 0x30A0, 3),
(15, "Number of Pokémon evolved", 0x33A0, 3),
(16, "Number of Berries and Apricorns picked", 0x36A0, 3),
(17, "Number of times the party is healed", 0x39A0, 3),
(18, "Number of times Mystery Gift is used", 0x3CA0, 3),
(19, "Number of trades", 0x3FA0, 3),
(20, "Number of uses of field move Fly", 0x42A0, 3),
(21, "Number of uses of field move Surf", 0x45A0, 3),
(22, "Number of uses of field move Waterfall", 0x48A0, 3),
(23, "Number of times the player whited out", 0x4BA0, 3),
(24, "Number of Lucky Number Show prizes won", 0x4EA0, 3),
(25, "Number of Phone calls made and received", 0x51A0, 3),
(26, "Unused", 0x54A0, 3),
(27, "Number of Colosseum battles", 0x57A0, 3),
(28, "Number of times players Pokémon used Splash", 0x5AA0, 3),
(29, "Number of tree Pokémon encounters", 0x5DA0, 3),
(30, "Unused", 0x60A0, 3),
(31, "Number of Colosseum wins", 0x63A0, 3),
(32, "Number of Colosseum losses", 0x66A0, 3),
(33, "Number of Colosseum ties", 0x69A0, 3),
(34, "Number of times players Pokémon used SelfDestruct or Explosion", 0x6CA0, 3),
(35, "Current streak of consecutive slot machine wins", 0x6FA0, 2),
(36, "Longest streak of consecutive slot machine wins", 0x71A0, 2),
(37, "Total coins won from slot machines", 0x73A0, 4),
(38, "Total money earned from battles (including Pay Day)", 0x77A0, 4),
(39, "Largest Magikarp measured", 0x7BA0, 2),
(40, "Smallest Magikarp measured", 0x7DA0, 2),
(41, "Bug-Catching Contest high score", 0x7FA0, 2);
CREATE TABLE IF NOT EXISTS `bxt_news` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `ranking_category_1` tinyint(2) unsigned,
 `ranking_category_2` tinyint(2) unsigned,
 `ranking_category_3` tinyint(2) unsigned,
 `message_j` varbinary(100) NOT NULL,
 `message_e` varbinary(100) NOT NULL,
 `news_binary_j` blob NOT NULL,
 `news_binary_e` blob NOT NULL,
 PRIMARY KEY (`id`)
);

# Mario Kart Advance (AMKJ)
CREATE TABLE `amkj_user_map` (
 `player_id` binary(16) NOT NULL,
 `user_id` int(11) NOT NULL,
 PRIMARY KEY (`player_id`)
);
CREATE TABLE IF NOT EXISTS `amkj_rule` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
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
 PRIMARY KEY (`id`)
);
CREATE TABLE `amkj_ghosts` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `player_id` binary(16) NOT NULL,
 `name` binary(5) NOT NULL,
 `state` tinyint(3) unsigned NOT NULL,
 `course` tinyint(3) unsigned NOT NULL,
 `driver` tinyint(3) unsigned NOT NULL,
 `time` smallint(5) unsigned NOT NULL,
 `input_data` blob NOT NULL,
 PRIMARY KEY (`id`)
);
CREATE TABLE `amkj_ghosts_mobilegp` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `player_id` binary(16) NOT NULL,
 `name` binary(5) NOT NULL,
 `state` tinyint(3) unsigned NOT NULL,
 `driver` tinyint(3) unsigned NOT NULL,
 `time` smallint(5) unsigned NOT NULL,
 `input_data` blob NOT NULL,
 PRIMARY KEY (`id`)
);
