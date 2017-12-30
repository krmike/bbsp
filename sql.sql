ALTER TABLE `training_log` ADD `training_type` ENUM('Assessment','Training') NOT NULL DEFAULT 'Training' AFTER `type_id`;
INSERT INTO `user_types` (`user_type_id`, `user_type_name`) VALUES (NULL, 'Trainee');
ALTER TABLE `incident` ADD `ws` BLOB NULL DEFAULT NULL AFTER `unable_reason`, ADD `wss` BLOB NULL DEFAULT NULL AFTER `ws`, ADD `ps` BLOB NULL DEFAULT NULL AFTER `wss`, ADD `drs` BLOB NULL DEFAULT NULL AFTER `ps`, ADD `cs` BLOB NULL DEFAULT NULL AFTER `drs`;
INSERT INTO `resources` (`resource_id`, `resource_name`) VALUES (NULL, 'Penthrane');
INSERT INTO `menu_items` (`item_id`, `item_name`, `resource_id`, `link`, `sort`) VALUES (NULL, 'Penthrane', '21', '/penthrane.php', '8');
CREATE TABLE `penthrane_stock` ( `id` INT NOT NULL AUTO_INCREMENT , `operation` INT NOT NULL DEFAULT '1' , `qty` INT NOT NULL DEFAULT '1' , `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() , `user_id` INT NOT NULL , `comment` VARCHAR(255) NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `penthrane_stock` ADD `incident_id` INT NULL DEFAULT NULL AFTER `comment`;