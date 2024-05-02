
/*-----------10/04/2024--------*/

CREATE TABLE `users` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`first_name` VARCHAR(256) NOT NULL , 
	`last_name` VARCHAR(256) NOT NULL , 
	`gender` VARCHAR(100) NOT NULL , 
	`age` INT NOT NULL , 
	`location` VARCHAR(256) NULL DEFAULT 'location' , 
	`device_type` VARCHAR(256) NULL DEFAULT 'mac' , 
	`ip` VARCHAR(256) NULL DEFAULT '192.254.21.23' , 
	`app_usage` VARCHAR(256) NOT NULL DEFAULT 'usage' , 
	`browser` VARCHAR(256) NOT NULL DEFAULT 'safari' , 
	`last_accessed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`email` VARCHAR(256) NOT NULL , `password` VARCHAR(512) NOT NULL , 
	`status` INT NOT NULL DEFAULT '1' , 
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`deleted_at` TIMESTAMP NULL DEFAULT NULL , PRIMARY KEY (`id`));

/*-----------11/04/2024--------*/

CREATE TABLE `notifications` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`title` VARCHAR(256) NOT NULL , 
	`content` TEXT NULL DEFAULT NULL , 
	`redirection` VARCHAR(256) NULL DEFAULT NULL , 
	`image` VARCHAR(256) NULL DEFAULT NULL , 
	`status` INT NOT NULL DEFAULT '1' , 
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));

/*-----------16/04/2024--------*/

CREATE TABLE `courses` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`course_name` VARCHAR(256) NOT NULL , 
	`course_creator` VARCHAR(256) NOT NULL , 
	`no_of_days` INT NOT NULL , 
	`description` TEXT NULL DEFAULT NULL , 
	`thumbnail` VARCHAR(256) NULL DEFAULT NULL , 
	`status` INT NOT NULL , 
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));

/*-----------16/04/2024--------*/

CREATE TABLE `course_contents` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`course_id` INT NOT NULL , 
	`day` INT NOT NULL , 
	`book` INT NOT NULL , 
	`chapter` INT NOT NULL , 
	`verse_from` INT NOT NULL , 
	`verse_to` INT NOT NULL , 
	`text_description` TEXT NULL DEFAULT NULL , 
	`video_link` VARCHAR(256) NULL DEFAULT NULL , 
	`audio_file` VARCHAR(256) NULL DEFAULT NULL , 
	`spotify_link` VARCHAR(256) NULL DEFAULT NULL , 
	`website_link` VARCHAR(256) NULL DEFAULT NULL , 
	`image` VARCHAR(256) NULL DEFAULT NULL , 
	`documents` VARCHAR(256) NULL DEFAULT NULL , 
	`status` INT NOT NULL DEFAULT '1' , 
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`update_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));

ALTER TABLE `course_contents` CHANGE `update_at` `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

INSERT INTO `course_contents` (`id`, `course_id`, `day`, `book`, `chapter`, `verse_from`, `verse_to`, `text_description`, `video_link`, `audio_file`, `spotify_link`, `website_link`, `image`, `documents`, `status`, `created_at`, `update_at`) VALUES (NULL, '1', '1', '1', '1', '1', '10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), (NULL, '1', '2', '1', '1', '11', '12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
