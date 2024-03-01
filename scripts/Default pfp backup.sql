INSERT INTO image (id, name) VALUES (1, "default.jpg");
INSERT INTO profilepicture (id) VALUES (1);
UPDATE user SET profilepicture = DEFAULT WHERE id = 1;