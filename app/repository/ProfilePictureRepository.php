<?php
namespace macchiato_academy\app\repository;

use macchiato_academy\app\entity\Image;
use macchiato_academy\core\database\QueryBuilder;
use macchiato_academy\app\entity\ProfilePicture;
use macchiato_academy\core\App;
use macchiato_academy\app\entity\User;
use macchiato_academy\app\repository\ImageRepository;
use macchiato_academy\app\exceptions\FileException;
use macchiato_academy\app\exceptions\QueryException;

class ProfilePictureRepository extends QueryBuilder {

    /**
     * @param string $table
     * @param string $classEntity
     */
    public function __construct(string $table = 'profilePicture', string $classEntity = ProfilePicture::class)
    {
        parent::__construct($table, $classEntity);
    }

    public function getImageId(ProfilePicture $profilePicture): Image {
        $imageRepository = App::getRepository(ImageRepository::class);
        return $imageRepository->find($profilePicture->getId());
    }

    public function saveProfilePicture(ProfilePicture $profilePicture) {
        // $fnGuardaImagen = function () use ($imagenGaleria) { // Creamos una función anónima que se llama como callable
        //     $categoria = $this->getCategoria($imagenGaleria);
        //     $categoriaRepository = App::getRepository(CategoriaRepository::class);
        //     $categoriaRepository->nuevaImagen($categoria);
        //     $this->save($imagenGaleria);
        // };
        // $this->executeTransaction($fnGuardaImagen); // Se pasa un callable
        $fnSaveProfilePicture = function () use ($profilePicture) {
            $idImage = $this->getImageId($profilePicture);
            $imageRepository = App::getRepository(ImageRepository::class);
            $imageRepository->
            $this->save($profilePicture);
        };
        $this->executeTransaction($fnSaveProfilePicture);
    }

    public function deleteProfilePicture(User $user, ProfilePicture $profilePicture) {
        if ($profilePicture->getId() !== 1 ) {
            if (App::getRepository(ImageRepository::class)->delete([
                "id" => $user->getProfilePicture()
            ])) {
                $user->setProfilePicture(1);
                App::getRepository(UserRepository::class)
                    ->update($user);
                if (!$profilePicture->deleteFile()) {
                    throw new FileException("Couldn't find image");
                }
            } else {
                throw new QueryException("Error at deleting image");
            }
        }
    }

    public function updateProfilePicture(User $user, ProfilePicture $profilePicture, Image $imageObj) {
        if ($user->getProfilePicture() === 1) {
            $this->save($profilePicture);
            $user->setProfilePicture($imageObj->getId());
            App::getRepository(UserRepository::class)
                ->update($user);
        } else {
            $oldPfp = $this->findInnerJoin([
                "image.id",
                "profilepicture.id",
                "id_user",
                "name",
            ],
            "image",
            [
                "profilepicture.id",
                "image.id",
            ],
            [
                "id_user" => $user->getId()
            ]);
            if (App::getRepository(ImageRepository::class)->delete([
                "id" => $oldPfp->getId()
            ])) {
                $this->save($profilePicture);
                $user->setProfilePicture($imageObj->getId());
                App::getRepository(UserRepository::class)
                    ->update($user);
                if (!$oldPfp->deleteFile()) {
                    throw new FileException("Couldn't find image");
                }
            } else {
                throw new QueryException("Error at deleting image");
            }
        }
    }

}