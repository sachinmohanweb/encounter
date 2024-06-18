
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

/*-----------02/05/2024--------*/

CREATE TABLE `batches` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`batch_name` VARCHAR(256) NOT NULL , 
	`start_date` DATE NOT NULL , 
	`end_date` DATE NOT NULL , 
	`last_date` DATE NOT NULL , 
	`status` INT NOT NULL DEFAULT '1' , 
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));

ALTER TABLE `batches` ADD `course_id` INT NOT NULL AFTER `id`;


/*-----------11/06/2024--------*/

INSERT INTO `02_bible` (`bible_id`, `bible_name`, `bible_language`, `bible_desc`, `enabled`, `church_id`, `user_id`, `date_added`)
VALUES (NULL, 'English Bible', 'English', NULL, b'1', '1', NULL, CURRENT_TIMESTAMP),
(NULL, 'Hindi Bible', 'Hindi', NULL, b'1', '1', NULL, CURRENT_TIMESTAMP);


INSERT INTO `03_testament` (`testament_id`, `testament_name`, `testament_no`, `bible_id`, `user_id`, `date_added`) 
VALUES (NULL, 'English testament 1', '1', '2', NULL, CURRENT_TIMESTAMP), 
(NULL, 'English testment 2', '2', '2', NULL, CURRENT_TIMESTAMP);

INSERT INTO `03_testament` (`testament_id`, `testament_name`, `testament_no`, `bible_id`, `user_id`, `date_added`) 
VALUES (NULL, 'Hindi testament 1', '1', '3', NULL, CURRENT_TIMESTAMP);


ALTER TABLE `courses` ADD `bible_id` INT NOT NULL AFTER `course_name`;

/*-----------13/06/2024--------*/

ALTER TABLE `course_contents` ADD `testament` INT NULL DEFAULT NULL AFTER `day`;


/*-----------15/06/2024--------*/

CREATE TABLE `got_questions` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`question` TEXT NOT NULL , 
	`category_id` INT NOT NULL , 
	`sub_category_id` INT NOT NULL , 
	`answer` TEXT NOT NULL , 
	`status` INT NOT NULL DEFAULT '1' , 
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));


CREATE TABLE `g_q_categories` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`name` VARCHAR(256) NOT NULL , 
	`status` INT NOT NULL DEFAULT '1' , 
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));

CREATE TABLE `g_q_subcategories` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`cat_id` INT NOT NULL , 
	`name` VARCHAR(256) NOT NULL , 
	`status` INT NOT NULL DEFAULT '1' , 
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));


INSERT INTO `g_q_categories` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES 
(NULL, 'category 1', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), (NULL, 'category 2', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

INSERT INTO `g_q_subcategories` (`id`, `cat_id`, `name`, `status`, `created_at`, `updated_at`) VALUES 
(NULL, '1', 'sub category 1', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), (NULL, '1', 'sub category 2', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), 
(NULL, '2', 'sub category 3', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), (NULL, '2', 'sub category 4', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);


/*-----------17/06/2024--------*/

CREATE TABLE `user_qna` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`question` TEXT NOT NULL , 
	`answer` TEXT NOT NULL , 
	`status` INT NOT NULL DEFAULT '1' , 
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));

ALTER TABLE `user_qna` ADD `user_id` INT NOT NULL AFTER `id`;


ALTER TABLE `user_qna` CHANGE `answer` `answer` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL;

