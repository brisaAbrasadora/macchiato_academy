<?php

namespace macchiato_academy\app\repository;

use macchiato_academy\app\entity\Course;
use macchiato_academy\app\entity\CoursePicture;
use macchiato_academy\app\entity\Image;
use macchiato_academy\core\database\QueryBuilder;
use macchiato_academy\app\entity\ProfilePicture;
use macchiato_academy\core\App;
use macchiato_academy\app\entity\User;
use macchiato_academy\app\repository\ImageRepository;
use macchiato_academy\app\exceptions\FileException;
use macchiato_academy\app\exceptions\QueryException;

class CoursePictureRepository extends QueryBuilder
{

    /**
     * @param string $table
     * @param string $classEntity
     */
    public function __construct(string $table = 'coursePicture', string $classEntity = CoursePicture::class)
    {
        parent::__construct($table, $classEntity);
    }

    public function updateCoursePicture(Course $course, CoursePicture $coursePicture, Image $imageObj)
    {

        $oldPic = $this->findInnerJoin(
            [
                "image.id",
                "coursepicture.id",
                "id_course",
                "name",
            ],
            "image",
            [
                "coursepicture.id",
                "image.id",
            ],
            [
                "id_course" => $course->getId()
            ]
        );
        if (App::getRepository(ImageRepository::class)->delete([
            "id" => $oldPic->getId()
        ])) {
            $this->save($coursePicture);
            $course->setPicture($imageObj->getId());
            App::getRepository(CourseRepository::class)
                ->update($course);
            if (!$oldPic->deleteFile()) {
                throw new FileException("Couldn't find image");
            }
        } else {
            throw new QueryException("Error at deleting image");
        }
    }
}
