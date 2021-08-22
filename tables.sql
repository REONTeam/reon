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
 `level` int(1) unsigned NOT NULL,
 `email` varchar(30) NOT NULL,
 `trainer_id` int(10) unsigned NOT NULL,
 `secret_id` int(10) unsigned NOT NULL,
 `name` binary(5) NOT NULL,
 `class` binary(1) NOT NULL,
 `pokemon1` binary(54) NOT NULL,
 `pokemon2` binary(54) NOT NULL,
 `pokemon3` binary(54) NOT NULL,
 `message_start` binary(12) NOT NULL,
 `message_win` binary(12) NOT NULL,
 `message_lose` binary(12) NOT NULL,
 `num_trainers_defeated` int(10) unsigned NOT NULL,
 `num_turns_required` int(10) unsigned NOT NULL,
 `damage_taken` int(10) unsigned NOT NULL,
 `num_fainted_pokemon` int(10) unsigned NOT NULL,
 PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `bxtj_battle_tower_trainers` (
 `room` int(10) unsigned NOT NULL,
 `level` int(1) unsigned NOT NULL,
 `no` int(1) unsigned NOT NULL,
 `name` binary(5) NOT NULL,
 `class` binary(1) NOT NULL,
 `pokemon1` binary(54) NOT NULL,
 `pokemon2` binary(54) NOT NULL,
 `pokemon3` binary(54) NOT NULL,
 `message_start` binary(12) NOT NULL,
 `message_win` binary(12) NOT NULL,
 `message_lose` binary(12) NOT NULL,
 PRIMARY KEY (`room`,`level`,`no`)
);
CREATE TABLE IF NOT EXISTS `bxtj_battle_tower_leaders` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `name` binary(5) NOT NULL,
 `room` int(11) unsigned NOT NULL,
 `level` int(1) unsigned NOT NULL,
 PRIMARY KEY (`id`)
);

# Pokemon Crystal (BXTE)
CREATE TABLE IF NOT EXISTS `bxte_battle_tower_records` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `room` int(10) unsigned NOT NULL,
 `level` int(1) unsigned NOT NULL,
 `email` varchar(30) NOT NULL,
 `trainer_id` int(10) unsigned NOT NULL,
 `secret_id` int(10) unsigned NOT NULL,
 `name` binary(7) NOT NULL,
 `class` binary(1) NOT NULL,
 `pokemon1` binary(59) NOT NULL,
 `pokemon2` binary(59) NOT NULL,
 `pokemon3` binary(59) NOT NULL,
 `message_start` binary(12) NOT NULL,
 `message_win` binary(12) NOT NULL,
 `message_lose` binary(12) NOT NULL,
 `num_trainers_defeated` int(10) unsigned NOT NULL,
 `num_turns_required` int(10) unsigned NOT NULL,
 `damage_taken` int(10) unsigned NOT NULL,
 `num_fainted_pokemon` int(10) unsigned NOT NULL,
 PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `bxte_battle_tower_trainers` (
 `room` int(10) unsigned NOT NULL,
 `level` int(1) unsigned NOT NULL,
 `no` int(1) unsigned NOT NULL,
 `name` binary(10) NOT NULL,
 `class` binary(1) NOT NULL,
 `pokemon1` binary(59) NOT NULL,
 `pokemon2` binary(59) NOT NULL,
 `pokemon3` binary(59) NOT NULL,
 `message_start` binary(12) NOT NULL,
 `message_win` binary(12) NOT NULL,
 `message_lose` binary(12) NOT NULL,
 PRIMARY KEY (`room`,`level`,`no`)
);
CREATE TABLE IF NOT EXISTS `bxte_battle_tower_leaders` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `name` binary(7) NOT NULL,
 `room` int(11) unsigned NOT NULL,
 `level` int(11) unsigned NOT NULL,
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