DROP DATABASE IF EXISTS macchiato_academy;
CREATE DATABASE macchiato_academy CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;

USE macchiato_academy;

CREATE TABLE IF NOT EXISTS user (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(20) NOT NULL,
	email VARCHAR(30) NOT NULL UNIQUE KEY,
	password VARCHAR(255) NOT NULL,
	profilePicture INT UNSIGNED NULL DEFAULT 1,
	role VARCHAR(30) NOT NULL,
	dateOfBirth DATE NULL,
	dateOfJoin DATE DEFAULT CURRENT_TIMESTAMP,
	favoriteLanguage VARCHAR(10) NULL,
    biography VARCHAR(500) NULL,
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
);

ALTER TABLE user
ADD CONSTRAINT fk_profilePicture
FOREIGN KEY (profilePicture) REFERENCES profilePicture(id)
ON DELETE set null;

CREATE TABLE IF NOT EXISTS student (
	id INT UNSIGNED NOT NULL,
    FOREIGN KEY (id) REFERENCES user(id)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS teacher (
	id INT UNSIGNED NOT NULL,
    FOREIGN KEY (id) REFERENCES user(id)
);




INSERT INTO image (
	name ) VALUES (
    "default.jpg"
);


INSERT INTO profilePicture (
	id ) VALUES (
    1
);

INSERT INTO user (
	username, 
	email, 
	password,
	role,
    biography ) VALUES (
	'Admin',
	'admin@macchiato-academy.com',
	'$2y$10$6wyVygwpII0dDZ7BSs8k0.DvTZkaiZ13I9wV0n3n23FyeY2JO8c0y',
	'ROLE_ADMIN',
    'Admin of this site. Always working on new stuff!'
);

/* INSERT INTO course (
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


INSERT INTO student_joins_course (
	id_student,
	id_course) VALUES (
	4,
	1
);
*/

CREATE TABLE IF NOT EXISTS language (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(20) NOT NULL UNIQUE,
    INDEX(id)
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

CREATE TABLE IF NOT EXISTS course (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(50) NOT NULL,
    description VARCHAR(255) NOT NULL,
	teacher INT UNSIGNED NOT NULL,
    language INT UNSIGNED NOT NULL,
    picture INT UNSIGNED NULL,
	FOREIGN KEY (teacher) REFERENCES teacher(id),
    FOREIGN KEY (language) REFERENCES language(id)
);

CREATE TABLE IF NOT EXISTS student_joins_course (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_student INT UNSIGNED NOT NULL,
	id_course INT UNSIGNED NOT NULL,
	FOREIGN KEY (id_student) REFERENCES student(id),
	FOREIGN KEY (id_course) REFERENCES course(id)
);

CREATE TABLE IF NOT EXISTS coursePicture (
	id INT UNSIGNED NOT NULL,
    id_course INT UNSIGNED,
    FOREIGN KEY (id) REFERENCES image(id)
    ON DELETE CASCADE,
    FOREIGN KEY (id_course) REFERENCES course(id)
);
