<?php

namespace macchiato_academy\app\repository;

use macchiato_academy\app\entity\User;
use macchiato_academy\core\database\QueryBuilder;
use macchiato_academy\app\entity\IEntity;
use PDOException;
use macchiato_academy\app\exceptions\QueryException;
use macchiato_academy\core\App;
use macchiato_academy\app\exceptions\FileException;

class UserRepository extends QueryBuilder
{
    public function __construct(string $table = 'user', string $classEntity = User::class)
    {
        parent::__construct($table, $classEntity);
    }

    public function emailExists(string $email): bool
    {
        $user = $this->findOneBy([
            "email" => $email
        ]);

        return isset($user);
    }

    public function deleteUser(User $user)
    {
        if ($user->getProfilePicture() !== 1) {
            $profilePicture = App::getRepository(ProfilePictureRepository::class)
                ->findInnerJoin(
                    [
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
                    ]
                );
            App::getRepository(ImageRepository::class)->delete([
                "id" => $profilePicture->getId()
            ]);
            if (!$profilePicture->deleteFile()) {
                throw new FileException("Couldn't find image");
            }
        }
        App::getRepository(UserRepository::class)->delete([
            "id" => $user->getId()
        ]);
    }
}
