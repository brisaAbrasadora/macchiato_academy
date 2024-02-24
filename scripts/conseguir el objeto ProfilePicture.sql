SELECT id, id_image, id_user, image_name 
FROM profilePicture 
INNER JOIN image 
ON id_image = id 
WHERE id_image = 1;