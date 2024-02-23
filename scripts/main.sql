DROP DATABASE IF EXISTS macchiato_academy;
CREATE DATABASE macchiato_academy CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;

USE macchiato_academy;

CREATE TABLE IF NOT EXISTS user (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(20) NOT NULL,
	email VARCHAR(30) NOT NULL UNIQUE KEY,
	password VARCHAR(255) NOT NULL,
	profilePicture INT UNSIGNED NOT NULL DEFAULT 1,
	role VARCHAR(30) NOT NULL ,
	dateOfBirth DATE NULL,
	dateOfJoin DATE DEFAULT CURRENT_TIMESTAMP,
	favoriteLanguage VARCHAR(10) NULL,
    INDEX(id)
);

CREATE TABLE IF NOT EXISTS image (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);


CREATE TABLE IF NOT EXISTS profilePicture (
	id INT UNSIGNED NOT NULL,
    id_user INT UNSIGNED,
    FOREIGN KEY (id) REFERENCES image(id)
    ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES user(id)
    ON DELETE CASCADE
);

ALTER TABLE user
ADD CONSTRAINT fk_profilePicture
FOREIGN KEY (profilePicture) REFERENCES profilePicture(id)
ON DELETE CASCADE;

CREATE TABLE IF NOT EXISTS course (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(50) NOT NULL,
	id_teacher INT UNSIGNED NULL,
	FOREIGN KEY (id_teacher) REFERENCES user(id)
	ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS student_joins_course (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_student INT UNSIGNED NOT NULL,
	id_course INT UNSIGNED NOT NULL,
	FOREIGN KEY (id_student) REFERENCES user(id)
	ON DELETE CASCADE,
	FOREIGN KEY (id_course) REFERENCES course(id)
	ON DELETE CASCADE
);

INSERT INTO image (
	name ) VALUES (
    "amigo.jpg"
);


INSERT INTO profilePicture (
	id ) VALUES (
    1
);

INSERT INTO user (
	username, 
	email, 
	password,
	role) VALUES (
	'Admin',
	'admin@admin.com',
	'admin',
	'ROLE_ADMIN'
);

INSERT INTO user (
	username,
	email,
	password,
	role,
	dateOfBirth,
	favoriteLanguage) VALUES (
	'Daniel',
	'dani@mail.com',
	'dani11',
	'ROLE_STUDENT',
	'2000-11-02',
	'Java'
);

INSERT INTO user (
	username,
	email,
	password,
	role,
	favoriteLanguage) VALUES (
	'Dario',
	'dario@mail.com',
	'dario00',
	'ROLE_TEACHER',
	'PHP'
);

INSERT INTO course (
	title,
	id_teacher) VALUES (
	'PHP Course',
	1
);

INSERT INTO student_joins_course (
	id_student,
	id_course) VALUES (
	3,
	1
);

INSERT INTO user (
	username,
	email,
	password,
	role,
	dateOfBirth,
	favoriteLanguage) VALUES (
	'Sofia',
	'vivi@mail.com',
	'vivi12',
	'ROLE_STUDENT',
	'2005-12-03',
	'HTML'
);

INSERT INTO student_joins_course (
	id_student,
	id_course) VALUES (
	4,
	1
);

CREATE TABLE IF NOT EXISTS image (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS language (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(20) NOT NULL UNIQUE
);

INSERT INTO language (
	name) VALUES (
	'PHP'
);

INSERT INTO language (
	name) VALUES (
	'HTML + CSS'
);

INSERT INTO language (
	name) VALUES (
	'Java'
);

INSERT INTO language (
	name) VALUES (
	'JavaScript'
);

INSERT INTO language (
	name) VALUES (
	'Phyton'
);
