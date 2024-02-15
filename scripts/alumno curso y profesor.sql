SELECT u.username AS student_name, c.title AS course_title, t.username AS teacher_name
FROM user AS u INNER JOIN student_joins_course AS sjc
ON u.id = sjc.id_student
INNER JOIN course AS c
ON c.id = sjc.id_course
LEFT JOIN user AS t
ON c.id_teacher = t.id;
