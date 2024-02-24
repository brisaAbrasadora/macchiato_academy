# insertar nueva img de perfil
# primero insertar la imagen
INSERT INTO image (
	image_name ) VALUES (
	"personal.jpg"
);
# despues crear la entrada en profilepicture con el id de la imagen creada
# y el id del usuario al que se le asigna
INSERT INTO profilePicture (
	id_image, id_user ) VALUES (
    2, 2);
# actualizar el usuario
UPDATE user
SET profilePicture = 
	(SELECT id 
    FROM profilePicture 
    WHERE id_user = 2)
WHERE id = 2;