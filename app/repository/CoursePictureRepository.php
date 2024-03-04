<?php
namespace macchiato_academy\app\repository;

use macchiato_academy\app\entity\CoursePicture;
use macchiato_academy\app\entity\Image;
use macchiato_academy\core\database\QueryBuilder;
use macchiato_academy\app\entity\ProfilePicture;
use macchiato_academy\core\App;
use macchiato_academy\app\entity\User;
use macchiato_academy\app\repository\ImageRepository;
use macchiato_academy\app\exceptions\FileException;
use macchiato_academy\app\exceptions\QueryException;

class CoursePictureRepository extends QueryBuilder {

    /**
     * @param string $table
     * @param string $classEntity
     */
    public function __construct(string $table = 'coursePicture', string $classEntity = CoursePicture::class)
    {
        parent::__construct($table, $classEntity);
    }


}