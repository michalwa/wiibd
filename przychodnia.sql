CREATE TABLE `doctors` (
    `id`         INT          NOT NULL AUTO_INCREMENT,
    `title`      VARCHAR(100) NOT NULL,
    `speciality` VARCHAR(100) NOT NULL,

    PRIMARY KEY (`id`)
);

CREATE TABLE `patients` (
    `id`          INT          NOT NULL AUTO_INCREMENT,
    `pesel`       CHAR(11)     NOT NULL,
    `birth`       DATE         NOT NULL,
    `address`     VARCHAR(200) NOT NULL,
    `city`        VARCHAR(100) NOT NULL,
    `postal_code` CHAR(6)      NOT NULL,

    PRIMARY KEY (`id`)
);

CREATE TABLE `users` (
    `id`         INT          NOT NULL AUTO_INCREMENT,
    `username`   VARCHAR(100) NOT NULL,
    `password`   CHAR(128)    NOT NULL,
    `first_name` VARCHAR(100) NOT NULL,
    `last_name`  VARCHAR(100) NOT NULL,
    `role`       INT(8)       NOT NULL,
    `doctor`     INT              NULL,
    `patient`    INT              NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`doctor`)  REFERENCES `doctors`  (`id`),
    FOREIGN KEY (`patient`) REFERENCES `patients` (`id`)
);

CREATE TABLE `appointments` (
    `id`          INT  NOT NULL AUTO_INCREMENT,
    `doctor`      INT  NOT NULL,
    `patient`     INT  NOT NULL,
    `starts`      DATE NOT NULL,
    `ends`        DATE NOT NULL,
    `description` TEXT NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`doctor`)  REFERENCES `doctors`  (`id`),
    FOREIGN KEY (`patient`) REFERENCES `patients` (`id`)
);

CREATE TABLE `diagnoses` (
    `id`          INT     NOT NULL AUTO_INCREMENT,
    `appointment` INT     NOT NULL,
    `body`        TEXT    NOT NULL,
    `icd10`       CHAR(4)     NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`appointment`) REFERENCES `appointments` (`id`)
);
