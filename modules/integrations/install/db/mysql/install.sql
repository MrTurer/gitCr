SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE `integration_exchange_type`
(
	`id` INT NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
	`name` VARCHAR(50) NOT NULL COMMENT 'Название',
	`code` VARCHAR(50) NOT NULL COMMENT 'Код',
	CONSTRAINT `PK_integration_exchange_type` PRIMARY KEY (`id` ASC)
)
COMMENT = 'Справочник типов взаимодействия с информационной системой';

CREATE TABLE `integration_external_system`
(
	`id` INT NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
	`name` VARCHAR(50) NOT NULL COMMENT 'Название системы',
    `code` VARCHAR(50) NOT NULL COMMENT 'Код системы',
    `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата и время создания',
    `created_by` INT NOT NULL COMMENT 'Идентификатор пользователя, создавшего запись',
    `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата и время последнего изменения',
    `modified_by` INT NOT NULL COMMENT 'Идентификатор пользователя, изменившего запись',
    `description` TEXT NULL COMMENT 'Описание системы',
	CONSTRAINT `PK_integration_external_system` PRIMARY KEY (`id` ASC)
)
COMMENT = 'Внешние информационные системы';

CREATE TABLE `integration_system_exchange_type`
(
	`id` INT NOT NULL AUTO_INCREMENT,
	`system_id` INT NOT NULL COMMENT 'Идентификатор внешней системы',
	`exchange_type_id` INT NOT NULL COMMENT 'Идентификатор типа обмена данными',
    `direction` INT NOT NULL DEFAULT 0 COMMENT 'Направление взаимодействия. 0 - импорт, 1 - экспорт',
    `schedule` TEXT NULL COMMENT 'Расписание обхода агента',
    `active` CHAR(1) NOT NULL DEFAULT 'Y' COMMENT 'Активность',
    `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата и время создания',
    `created_by` INT NOT NULL COMMENT 'Идентификатор пользователя, создавшего запись',
    `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата и время последнего изменения',
    `modified_by` INT NOT NULL COMMENT 'Идентификатор пользователя, изменившего запись',
    `processor_class_name` VARCHAR(50) NULL COMMENT 'Класс для обработки данных',
    `options` TEXT NULL COMMENT 'Настройки взаимодействия',
    `mapping` TEXT NULL COMMENT 'Настройки сопоставления сущностей, справочников и атрибутов сущностей',
	CONSTRAINT `PK_integration_system_exchange_type` PRIMARY KEY (`id` ASC)
)
COMMENT = 'Настройки типов взаимодействия с внешней системой';

ALTER TABLE `integration_system_exchange_type`
    ADD CONSTRAINT `FK_integration_system_exchange_type_integration_exchange_type`
        FOREIGN KEY (`exchange_type_id`) REFERENCES `integration_exchange_type` (`id`) ON DELETE Restrict ON UPDATE Restrict;

ALTER TABLE `integration_system_exchange_type`
    ADD CONSTRAINT `FK_integration_system_exchange_type_integration_external_system`
        FOREIGN KEY (`system_id`) REFERENCES `integration_external_system` (`id`) ON DELETE Cascade ON UPDATE Restrict;

ALTER TABLE `integration_system_exchange_type`
    ADD UNIQUE INDEX `IDX_system_type_direction` (`system_id` ASC, `exchange_type_id` ASC, `direction` ASC);

SET FOREIGN_KEY_CHECKS=1;