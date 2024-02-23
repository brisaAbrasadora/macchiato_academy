<?php
namespace macchiato_academy\app\repository;

use macchiato_academy\core\database\QueryBuilder;
use macchiato_academy\app\entity\Image;


class ImageRepository extends QueryBuilder {

    /**
     * @param string $table
     * @param string $classEntity
     */
    public function __construct(string $table = 'image', string $classEntity = Image::class)
    {
        parent::__construct($table, $classEntity);
    }

    public function saveImage(Image $image) {
        $fnSaveImage = function () use ($image) {
            $this->save($image);
        };
        $this->executeTransaction($fnSaveImage);
    }

}