-- Delete the database if it exists
DROP DATABASE IF EXISTS `omladinska-zadruga`;

-- Create a new database
CREATE DATABASE `omladinska-zadruga`;

-- Use the newly created database
USE `omladinska-zadruga`;

-- Create the users table
CREATE TABLE `users`
(
    `id`                  INT AUTO_INCREMENT PRIMARY KEY,
    `username`            VARCHAR(255)                                                      NOT NULL UNIQUE,
    `email`               VARCHAR(255)                                                      NOT NULL UNIQUE,
    `password`            VARCHAR(255)                                                      NOT NULL,
    `first_name`          VARCHAR(255)                                                      NOT NULL,
    `last_name`           VARCHAR(255)                                                      NOT NULL,
    `birthday`            DATE                                                              NOT NULL,
    `address`             VARCHAR(255)                                                      NOT NULL,
    `phone`               VARCHAR(20)                                                       NOT NULL,
    `role`                ENUM ('super_administrator', 'administrator', 'employer', 'user') NOT NULL,
    `confirmation_status` ENUM ('not_confirmed', 'confirmed')                               NOT NULL DEFAULT 'not_confirmed',
    `available_status`    ENUM ('active', 'inactive', 'deleted')                            NOT NULL DEFAULT 'active',
    `created_at`          TIMESTAMP                                                                  DEFAULT CURRENT_TIMESTAMP,
    `updated_at`          TIMESTAMP                                                                  DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data into the users table
INSERT INTO `users` (`username`, `email`, `password`, `first_name`, `last_name`, `birthday`, `address`, `phone`, `role`,
                     `confirmation_status`, `available_status`)
VALUES ('super_admin', 'superadmin@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'John',
        'Doe', '1990-01-01', '123 Main St', '1234567890', 'super_administrator', 'confirmed', 'active'),
       ('admin1', 'admin1@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Alice',
        'Smith', '1985-05-15', '456 Oak St', '9876543210', 'administrator', 'confirmed', 'active'),
       ('admin2', 'admin2@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Bob',
        'Johnson', '1988-08-25', '789 Pine St', '8765432109', 'administrator', 'confirmed', 'active'),
       ('employer1', 'employer1@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Emma',
        'Davis', '1980-12-10', '101 Cedar St', '7654321098', 'employer', 'confirmed', 'active'),
       ('employer2', 'employer2@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Sam',
        'Jones', '1975-06-20', '202 Elm St', '6543210987', 'employer', 'confirmed', 'deleted'),
       ('user1', 'user1@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Linda', 'Miller',
        '1993-03-05', '303 Birch St', '5432109876', 'user', 'confirmed', 'active'),
       ('user2', 'user2@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Tom', 'Williams',
        '1997-09-12', '404 Maple St', '4321098765', 'user', 'confirmed', 'deleted'),
       ('employee1', 'employee1@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Sophie',
        'Brown', '1982-07-08', '505 Walnut St', '3210987654', 'user', 'confirmed', 'active'),
       ('employee2', 'employee2@example.com', '$2y$10$a/HUkayQvAN7jPPZILcw2O5cyYjuYC6bjIO/89EGM/BHabhwvfaGK', 'Mike',
        'White', '1995-11-18', '606 Pine St', '2109876543', 'user', 'confirmed', 'deleted');

CREATE TABLE confirmation_tokens
(
    id              INT PRIMARY KEY AUTO_INCREMENT,
    user_id         INT,
    token           VARCHAR(255) NOT NULL,
    expiration_date DATETIME     NOT NULL,
    `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE password_reset
(
    id              INT PRIMARY KEY AUTO_INCREMENT,
    user_email      VARCHAR(255) NOT NULL,
    token           VARCHAR(64)  NOT NULL,
    expiration_date DATETIME     NOT NULL,
    `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE employer_profile
(
    employer_profile_id INT PRIMARY KEY AUTO_INCREMENT,
    employer_user_id    INT UNIQUE NOT NULL,
    company_title       VARCHAR(255) NOT NULL,
    company_pib         VARCHAR(20) NOT NULL UNIQUE,
    company_mb          VARCHAR(20) NOT NULL UNIQUE,
    company_email       VARCHAR(255) NOT NULL UNIQUE,
    company_phone       VARCHAR(20) NOT NULL UNIQUE,
    company_work_field  VARCHAR(255) NOT NULL,
    company_address     VARCHAR(255) NOT NULL,
    `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employer_user_id) REFERENCES users (id) ON DELETE CASCADE
);

CREATE TABLE employee_profile
(
    employee_profile_id     INT PRIMARY KEY AUTO_INCREMENT,
    employee_user_id        INT NOT NULL,
    employment_status       ENUM ('unemployed', 'pupil', 'student', 'other'),
    employee_id_card_number VARCHAR(20) NOT NULL UNIQUE, #broj licne karte
    employee_mb             VARCHAR(20) NOT NULL UNIQUE, #jmbg
    employee_resume         VARCHAR(255),
    `created_at`            TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`            TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_user_id) REFERENCES users (id) ON DELETE CASCADE
);