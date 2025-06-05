 
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


/*-----------17/06/2024--------*/

CREATE TABLE `course_day_verses` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`course_content_id` INT NOT NULL , 
	`testament` INT NOT NULL , 
	`book` INT NOT NULL , 
	`chapter` INT NOT NULL , 
	`verse_from` INT NOT NULL , 
	`verse_to` INT NOT NULL , 
	`status` INT NOT NULL DEFAULT '1' , PRIMARY KEY (`id`));

ALTER TABLE `course_day_verses` ADD `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `status`,
 ADD `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_at`;


/*-----------24/06/2024--------*/

CREATE TABLE `bible_verse_themes` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`name` VARCHAR(256) NOT NULL , 
	`status` INT NOT NULL DEFAULT '1' , 
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));

INSERT INTO `bible_verse_themes` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES 
(NULL, 'General', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), (NULL, 'Christmas', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), 
(NULL, 'Easter', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), (NULL, 'Period of Lent', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);


CREATE TABLE `daily_bible_verses` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`bible_id` INT NOT NULL , 
	`book_id` INT NOT NULL , 
	`chapter_id` INT NOT NULL , 
	`verse_id` INT NOT NULL , 
	`date` DATE NOT NULL , 
	`theme_id` INT NOT NULL , 
	`status` INT NOT NULL DEFAULT '1' , 
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));
ALTER TABLE `daily_bible_verses` ADD `testament_id` INT NOT NULL AFTER `bible_id`;
ALTER TABLE `daily_bible_verses` CHANGE `theme_id` `theme_id` INT NOT NULL DEFAULT '1';
ALTER TABLE `daily_bible_verses` CHANGE `date` `date` DATE NULL;

ALTER TABLE `user_notes` CHANGE `chapter_id` `chapter_id` INT(11) NULL;
ALTER TABLE `user_notes` CHANGE `verse_id` `verse_id` INT(11) NULL;
ALTER TABLE `user_notes` CHANGE `sub_category` `sub_category` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;

/*-----------24/06/2024--------*/


CREATE TABLE `user_notes` (
	`id` INT NOT NULL AUTO_INCREMENT , `user_id` INT NOT NULL , 
	`bible_id` INT NOT NULL , `testament_id` INT NOT NULL , `book_id` INT NOT NULL , 
	`chapter_id` INT NOT NULL , `verse_id` INT NOT NULL , `note` LONGTEXT NOT NULL , 
	`category` TEXT NOT NULL , `sub_category` TEXT NOT NULL , `status` INT NOT NULL DEFAULT '1' , 
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));

INSERT INTO `user_notes` (`id`, `user_id`, `bible_id`, `testament_id`, `book_id`, `chapter_id`, `verse_id`, `note`, `category`, `sub_category`, `status`, `created_at`, `updated_at`) VALUES
 (NULL, '1', '1', '1', '4', '135', '4161', 'VPC integrates seamlessly with other AWS services such as AWS Direct Connect (for dedicated network connections), AWS VPN (for secure access to your VPC from on-premises), Amazon Route 53 (for DNS management), and AWS CloudWatch (for monitoring VPC flow logs and metrics).', 'family', 'family love', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), 
 (NULL, '2', '1', '1', '3', '94', '2770', 'Hosting Web Applications: You can deploy web servers in a public subnet with internet access through an Internet Gateway, while database servers are placed in a private subnet without direct internet access.', 'relations', 'marital relations', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);


CREATE TABLE `user_l_m_s` (`id` INT NOT NULL AUTO_INCREMENT , `user_id` INT NOT NULL , 
	`course_id` INT NOT NULL , `batch_id` INT NOT NULL , `start_date` DATE NOT NULL , 
	`end_date` DATE NOT NULL , `progress` VARCHAR(256) NOT NULL , 
	`completed_status` INT NOT NULL DEFAULT '1' COMMENT '1-Not started,2-Ongoing,3-Completed' , 
	`status` INT NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));

INSERT INTO `user_l_m_s` (`id`, `user_id`, `course_id`, `batch_id`, `start_date`, `end_date`, `progress`, `completed_status`, `status`, `created_at`, `updated_at`) VALUES 
(NULL, '1', '1', '1', '2024-07-10', '2024-08-10', '10%', '2', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), 
(NULL, '2', '1', '1', '2024-07-10', '2024-08-10', ' 20%', '2', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);



-- 02/07/24----

CREATE TABLE `email_verifications` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`email` VARCHAR(256) NOT NULL , 
	`otp` VARCHAR(4) NOT NULL , 
	`otp_expiry` TIMESTAMP NOT NULL , 
	`otp_used` INT NOT NULL DEFAULT '0' , 
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));

ALTER TABLE `users` ADD `device_id` VARCHAR(256) NULL DEFAULT NULL AFTER `ip`, 
ADD `refresh_token` VARCHAR(256) NULL DEFAULT NULL AFTER `device_id`;


composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate

ALTER TABLE `users` ADD `remember_token` VARCHAR(100) NULL DEFAULT NULL AFTER `status`;

ALTER TABLE `users` CHANGE `last_name` `last_name` VARCHAR(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL;
ALTER TABLE `users` CHANGE `age` `age` INT NULL DEFAULT NULL;
ALTER TABLE `users` CHANGE `password` `password` VARCHAR(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL;


-- 09/08/24----

ALTER TABLE `courses` ADD `creator_designation` VARCHAR(256) NULL DEFAULT NULL AFTER `course_creator`, 
	ADD `creator_image` VARCHAR(256) NULL DEFAULT NULL AFTER `creator_designation`;

ALTER TABLE `courses` ADD `intro_commentary` LONGTEXT NULL DEFAULT NULL AFTER `thumbnail`, 
ADD `intro_video` VARCHAR(256) NULL DEFAULT NULL AFTER `intro_commentary`, 
ADD `intro_audio` VARCHAR(256) NULL DEFAULT NULL AFTER `intro_video`;

-- 09/08/24----

ALTER TABLE `courses` ADD `intro_video_thumb` VARCHAR(256) NOT NULL DEFAULT 'storage/course_intro_thumb/video_thumb.png' AFTER `intro_audio`;

-- 09/08/24----

UPDATE `05_chapter` SET `chapter_id` = '0' WHERE `05_chapter`.`chapter_id` = 1413;
UPDATE `06_holy_statement` SET `chapter_id` = '0' WHERE `06_holy_statement`.`statement_id` = 1;

-- 29/08/24----

CREATE TABLE `encounter_db`.`user_daily_readings` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`user_lms_id` INT NOT NULL , 
	`day` INT NOT NULL , 
	`date_of_reading` DATE NOT NULL , 
	`status` INT NOT NULL DEFAULT '1' , 
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));

ALTER TABLE `user_l_m_s` CHANGE `end_date` `end_date` DATE NULL DEFAULT NULL;

ALTER TABLE `user_l_m_s` CHANGE `progress` `progress` VARCHAR(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '0';

ALTER TABLE `user_l_m_s` CHANGE `progress` `progress` INT NULL DEFAULT '0';

ALTER TABLE `user_l_m_s` CHANGE `progress` `progress` INT NOT NULL DEFAULT '0';


-- 25/09/24----

ALTER TABLE `users` ADD `image` VARCHAR(256) NULL DEFAULT NULL AFTER `location`;

ALTER TABLE `users` CHANGE `gender` `gender` VARCHAR(100)  NULL DEFAULT NULL;
ALTER TABLE `users` CHANGE `location` `location` VARCHAR(256)  NULL DEFAULT NULL;
ALTER TABLE `users` CHANGE `device_type` `device_type` VARCHAR(256)  NULL DEFAULT NULL;
ALTER TABLE `users` CHANGE `ip` `ip` VARCHAR(256)  NULL DEFAULT NULL;
ALTER TABLE `users` CHANGE `app_usage` `app_usage` VARCHAR(256)  NULL DEFAULT NULL;
ALTER TABLE `users` CHANGE `browser` `browser` VARCHAR(256)  NULL DEFAULT NULL;

UPDATE `users` SET `last_name` = NULL, `gender` = NULL, `age` = NULL, `location`=NULL, `device_type`=NULL, `ip`=NULL, `device_id`=NULL, `refresh_token`=NULL, `app_usage`=NULL, `browser`=NULL;


-- 26/09/24----

CREATE TABLE `book_images` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`book_id` INT NOT NULL , `image` VARCHAR(256) NOT NULL , 
	`status` INT NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));

ALTER TABLE `book_images` ADD `bible_id` INT NOT NULL AFTER `id`;
ALTER TABLE `book_images` ADD `testament_id` INT NOT NULL AFTER `bible_id`;


-- 28/09/24----

ALTER TABLE `course_contents` DROP `video_link`;
ALTER TABLE `course_contents` DROP `spotify_link`;

CREATE TABLE `course_content_links` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`course_content_id` INT NOT NULL , 
	`type` INT NOT NULL COMMENT '1-video link,2-spotify link' , 
	`video_spotify_link` VARCHAR(256) NOT NULL , 
	`status` INT NOT NULL DEFAULT '1' , 
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));


-- 03/10/24----

ALTER TABLE `courses` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
ALTER TABLE `course_contents` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
ALTER TABLE `course_content_links` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
ALTER TABLE `course_day_verses` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
ALTER TABLE `daily_bible_verses` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
ALTER TABLE `got_questions` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
ALTER TABLE `g_q_categories` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
ALTER TABLE `g_q_subcategories` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
ALTER TABLE `notifications` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
ALTER TABLE `user_daily_readings` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
ALTER TABLE `user_l_m_s` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
ALTER TABLE `user_notes` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);

ALTER TABLE `user_notes` CHANGE `category` `category` TEXT NULL DEFAULT NULL;
ALTER TABLE `user_notes` CHANGE `sub_category` `sub_category` TEXT NULL DEFAULT NULL;

-- 05/10/24----

CREATE TABLE `tags` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`tag_name` VARCHAR(256) NOT NULL , 
	`status` INT NOT NULL DEFAULT '1' , 
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));

ALTER TABLE `tags` ADD `user_id` INT NOT NULL AFTER `id`;


CREATE TABLE `user_bible_markings` (
	`id` INT NOT NULL , `user_id` INT NOT NULL , `statement_id` INT NOT NULL , 
	`data` INT NOT NULL , `status` INT NOT NULL , `created_at` INT NOT NULL , 
	`updated_at` INT NOT NULL );

ALTER TABLE `user_bible_markings` ADD `type` INT NOT NULL COMMENT '1-note,2-bookmark,3-color' AFTER `user_id`;

ALTER TABLE `user_bible_markings` CHANGE `id` `id` INT NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);

ALTER TABLE `user_bible_markings` CHANGE `data` `data` LONGTEXT NOT NULL;

ALTER TABLE `user_bible_markings` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `user_bible_markings` CHANGE `updated_at` `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `user_bible_markings` CHANGE `status` `status` INT NOT NULL DEFAULT '1';


-- 19/10/24----

ALTER TABLE `users` ADD `country_code` VARCHAR(5) NULL DEFAULT NULL AFTER `password`, ADD `phone` VARCHAR(25) NULL DEFAULT NULL AFTER `country_code`;

-- 04/11/24----

ALTER TABLE `course_content_links` ADD `title` VARCHAR(256) NULL DEFAULT NULL AFTER `type`;
ALTER TABLE `course_content_links` ADD `description` VARCHAR(256) NULL DEFAULT NULL AFTER `title`;


-- 17/11/24----

CREATE TABLE `user_custom_notes` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`user_id` INT NOT NULL , 
	`note_text` LONGTEXT NOT NULL , 
	`tag_id` INT NOT NULL , 
	`status` INT NOT NULL DEFAULT '1' , 
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));


-- 25/11/24----

CREATE TABLE `notification_types` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`type_name` VARCHAR(256) NOT NULL ,
	 `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	 `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));

INSERT INTO `notification_types` (`id`, `type_name`, `created_at`, `updated_at`) 
VALUES (NULL, 'Image ', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
 (NULL, 'File', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), 
 (NULL, 'Text', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), 
 (NULL, 'Audio Link', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), 
 (NULL, 'Video Link', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), 
 (NULL, 'Document Link', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);


ALTER TABLE `notifications` CHANGE `content` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `notifications` ADD `type` INT NOT NULL AFTER `redirection`;

ALTER TABLE `notifications` CHANGE `image` `data` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;


-- 06/12/24----

CREATE TABLE `app_banners` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`title` VARCHAR(256) NOT NULL , 
	`path` VARCHAR(256) NOT NULL , 
	`status` INT NOT NULL DEFAULT '1' , 
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));

-- 31/12/24----

ALTER TABLE `users` ADD `timezone` VARCHAR(256) NULL DEFAULT NULL AFTER `password`;

-- 13/01/24----

CREATE TABLE `bible_verse_images` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`title` VARCHAR(256) NOT NULL , 
	`path` VARCHAR(256) NOT NULL , 
	`status` INT NOT NULL DEFAULT '1' , 
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));


-- 19/01/24----

ALTER TABLE `course_content_links` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;


-- 01/02/25 Indexing----

ALTER TABLE courses 
ADD INDEX idx_status (status);


ALTER TABLE course_contents 
ADD INDEX idx_course_id (course_id), 
ADD INDEX idx_day (day), 
ADD INDEX idx_status (status);


ALTER TABLE batches 
ADD INDEX idx_course_id (course_id), 
ADD INDEX idx_start_date (start_date), 
ADD INDEX idx_end_date (end_date), 
ADD INDEX idx_last_date (last_date), 
ADD INDEX idx_status (status);


ALTER TABLE course_day_verses 
ADD INDEX idx_course_content_id (course_content_id), 
ADD INDEX idx_testament (testament), 
ADD INDEX idx_book_chapter (book, chapter);

ALTER TABLE course_content_links 
ADD INDEX idx_course_content_id (course_content_id), 
ADD INDEX idx_type (type);


ALTER TABLE user_l_m_s 
ADD INDEX idx_user_id (user_id), 
ADD INDEX idx_course_id (course_id), 
ADD INDEX idx_batch_id (batch_id), 
ADD INDEX idx_start_date (start_date), 
ADD INDEX idx_end_date (end_date), 
ADD INDEX idx_completed_status (completed_status), 
ADD INDEX idx_status (status);


ALTER TABLE user_qna 
ADD INDEX idx_user_id (user_id), 
ADD INDEX idx_status (status);


ALTER TABLE user_bible_markings 
ADD INDEX idx_user_id (user_id), 
ADD INDEX idx_statement_id (statement_id), 
ADD INDEX idx_type (type), 
ADD INDEX idx_status (status);


ALTER TABLE user_custom_notes 
ADD INDEX idx_user_id (user_id), 
ADD INDEX idx_tag_id (tag_id), 
ADD INDEX idx_status (status);

ALTER TABLE tags 
ADD INDEX idx_user_id (user_id), 
ADD INDEX idx_status (status);


-- 22/03/25 Indexing----

ALTER TABLE `app_banners` ADD `link` VARCHAR(256) NULL DEFAULT NULL AFTER `title`;

-- 30/03/25 ----

CREATE TABLE `bible_changes` (
	`id` INT NOT NULL AUTO_INCREMENT , 
	`bible_id` INT NOT NULL , 
	`statement_id` INT NOT NULL , `sync_time` BIGINT NOT NULL , 
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`));

-- 05/06/25 ----

ALTER TABLE `courses` ADD `course_order` INT NULL DEFAULT NULL AFTER `no_of_days`;
