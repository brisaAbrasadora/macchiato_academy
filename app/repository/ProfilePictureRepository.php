<?php
namespace macchiato_academy\app\repository;

use macchiato_academy\app\entity\Image;
use macchiato_academy\core\database\QueryBuilder;
use macchiato_academy\app\entity\ProfilePicture;
use macchiato_academy\core\App;

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

    // public function getImageUrl(ProfilePicture $profilePicture): Image {
    //     $imageRepository = App::getRepository(ImageRepository::class);
    //     return $imageRepository->
    // }

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

}