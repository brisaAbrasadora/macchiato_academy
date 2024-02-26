DESCRIBE user;

DESCRIBE profilepicture;

SELECT * FROM profilepicture;
SELECT * FROM image;
DELETE FROM user WHERE id = 2;

SELECT * FROM user;

# BORRAR FOTO DE PERFIL

DELETE FROM profilepicture WHERE id_user = 2;
UPDATE user SET profilePicture = 1 WHERE id = 2;