<?php

namespace macchiato_academy\app\controllers;

use macchiato_academy\core\Response;
use macchiato_academy\app\repository\ProfilePictureRepository;
use macchiato_academy\app\repository\LanguageRepository;
use macchiato_academy\app\repository\UserRepository;
use macchiato_academy\core\App;
use macchiato_academy\app\exceptions\ValidationException;
use macchiato_academy\app\utils\Utils;
use macchiato_academy\core\helpers\FlashMessage;
use macchiato_academy\core\Security;
use DateTime;
use macchiato_academy\app\utils\File;
use macchiato_academy\app\entity\CoursePicture;
use macchiato_academy\app\repository\ImageRepository;
use macchiato_academy\app\entity\Image;
use macchiato_academy\app\exceptions\FileException;
use macchiato_academy\app\exceptions\QueryException;
use macchiato_academy\app\repository\CoursePictureRepository;
use macchiato_academy\app\repository\CourseRepository;
use macchiato_academy\app\repository\TeacherRepository;
use macchiato_academy\app\entity\Course;
use macchiato_academy\app\entity\Student;
use macchiato_academy\app\entity\Teacher;
use macchiato_academy\app\exceptions\AppException;
use macchiato_academy\app\repository\StudentJoinsCourseRepository;
use macchiato_academy\app\repository\StudentRepository;

class CoursesController
{
    // public function show()
    // {
    //     $title = "Courses | Macchiato Academy";

    //     Response::renderView(
    //         'courses',
    //         compact('title')
    //     );
    // }

    public function course(int $id)
    {
        $title = "Course | Macchiato Academy";
        $errors = FlashMessage::get('enroll-error', []);
        $message = FlashMessage::get('message');
        $coursePictureRepository = App::getRepository(CoursePictureRepository::class);
        $languageRepository = App::getRepository(LanguageRepository::class);
        $teacherRepository = App::getRepository(UserRepository::class);
        $courseRepository = App::getRepository(CourseRepository::class);
        $studentJoinsCourseRepository = App::getRepository(StudentJoinsCourseRepository::class);

        $course = $courseRepository->find($id);

        $coursePictureObj = App::getRepository(CoursePictureRepository::class)
            ->findInnerJoin(
                [
                    "image.id",
                    "coursepicture.id",
                    "id_course",
                    "name"
                ],
                "image",
                [
                    "coursepicture.id",
                    "image.id"
                ],
                [
                    "id_course" => $id
                ]
            );


        $language = null;
        if ($course->getLanguage())
            $language = $languageRepository->find($course->getLanguage())->getName();

        $teacher = $teacherRepository->find($course->getTeacher())->getUsername();

        $studentsEnrolled = $studentJoinsCourseRepository->countNumOfStudents($id);



        Response::renderView(
            'course-single',
            compact('title', 'course', 'coursePictureObj', 'language', 'studentsEnrolled', 'teacher', 'errors', 'message')
        );
    }

    public function enroll(int $id)
    {
        try {
            $user = App::get('appUser');
            if (!isset($user)) {
                throw new AppException("You must be logged in to enroll into a course");
            }
            if ($user instanceof Teacher) {
                throw new ValidationException("Teachers can't enroll into a course");
            }


            $courseRepository = App::getRepository(CourseRepository::class);
            $studentJoinsCourseRepository = App::getRepository(StudentJoinsCourseRepository::class);

            $course = $courseRepository->find($id);

            if (Utils::isStudentEnrolled($user->getId(), $course->getId())) {
                throw new ValidationException("You have already enrolled this course");
            }
            $studentJoinsCourseRepository->sign($user->getId(), $course->getId());
            FlashMessage::set('message', "You have enrolled the course {$course->getTitle()} succesfully!");
            App::get('router')->redirect("course/$id");
        } catch (AppException $appException) {
            FlashMessage::set('enroll-error', [$appException->getMessage()]);
            App::get('router')->redirect("course/$id");
        } catch (ValidationException $validationException) {
            FlashMessage::set('enroll-error', [$validationException->getMessage()]);
            App::get('router')->redirect("course/$id");
        }
    }

    public function edit(int $id)
    {
        $title = "Edit course | Macchiato Academy";
        $errors = FlashMessage::get('edit-error', []);
        $message = FlashMessage::get('message');
        $languageRepository = App::getRepository(LanguageRepository::class);
        $coursePictureRepository = App::getRepository(CoursePictureRepository::class);
        $teacherRepository = App::getRepository(TeacherRepository::class);
        $languages = $languageRepository->findAll();
        $studentRepository = App::getRepository(StudentRepository::class);
        $studentJoinsCourseRepository = App::getRepository(StudentJoinsCourseRepository::class);

        $course = App::getRepository(CourseRepository::class)->find($id);
        $titleCourse = $course->getTitle();
        $description = $course->getDescription();

        $userKeys = array_map(fn ($key): string => "user.$key", array_keys((new Teacher())->toArray()));

        $teacherKeys = $userKeys;
        array_push($teacherKeys, "teacher.id");
        $teacherIds = array_map(
            function ($teacher) {
                return $teacher->getId();
            },
            App::getRepository(TeacherRepository::class)->findAll()
        );
        $teachers = [];
        foreach ($teacherIds as $teacherId) {
            $teacher = App::getRepository(TeacherRepository::class)->findInnerJoin(
                $userKeys,
                "user",
                [
                    "user.id",
                    "teacher.id"
                ],
                [
                    "teacher__id" => $teacherId
                ]
            );
            array_push($teachers, $teacher);
        }

        $students = $studentRepository->findInCourse([
            "id_course" => $course->getId()
        ]);

        $teacher = $course->getTeacher();
        $language = $course->getLanguage();
        $currentPicture = $coursePictureRepository->findInnerJoin(
            [
                "image.id",
                "coursepicture.id",
                "id_course",
                "name"
            ],
            "image",
            [
                "coursepicture.id",
                "image.id"
            ],
            [
                "id_course" => $id
            ]
        );

        Response::renderView(
            'edit-course',
            compact('title', 'course', 'titleCourse', 'description', 'teachers', 'teacher', 'students', 'language', 'languages', 'currentPicture', 'errors', 'message', 'id')
        );
    }

    public function unsign(int $id) {
        try {
            $courseRepository = App::getRepository(CourseRepository::class);
            $studentRepository = App::getRepository(StudentRepository::class);
            $studentJoinsCourseRepository = App::getRepository(StudentJoinsCourseRepository::class);
            $course = $courseRepository->find($id);
           $user = App::get('appUser');

            $student = $studentRepository->findInCourse([
                "id_course" => $course->getId(),
                "id_student" => $user->getId()
            ])[0];

            $studentJoinsCourseRepository->unsign($student, $course);

            FlashMessage::set('message', "You have unenrolled this course");
        } catch (ValidationException $validationException) {
            FlashMessage::set('enroll-error', [$validationException->getMessage()]);
        } finally {
            App::get('router')->redirect('course/' . $id);
        }
    }

    public function validateTitle(int $id)
    {
        try {
            $courseRepository = App::getRepository(CourseRepository::class);
            $course = $courseRepository->find($id);
            if (!isset($_POST['title']) || empty($_POST['title']))
                throw new ValidationException('Title can\'t be empty');
            $title = htmlspecialchars(trim($_POST['title']));


            if ($course->getTitle() === $title)
                throw new ValidationException('Title is identical');

            $course->setTitle($title);

            $courseRepository->update($course);
            FlashMessage::set('message', "Title changed to $title");
        } catch (ValidationException $validationException) {
            FlashMessage::set('edit-error', [$validationException->getMessage()]);
        } finally {
            App::get('router')->redirect('course/edit/' . $id);
        }
    }

    public function validateDescription(int $id)
    {
        try {
            $courseRepository = App::getRepository(CourseRepository::class);
            $course = $courseRepository->find($id);
            if (!isset($_POST['description']) || empty($_POST['description']))
                throw new ValidationException('Description can\'t be empty');
            $description = htmlspecialchars(trim($_POST['description']));

            if ($course->getDescription() === $description)
                throw new ValidationException('Description is identical');

            $course->setDescription($description);

            $courseRepository->update($course);
            FlashMessage::set('message', "Description updated");
        } catch (ValidationException $validationException) {
            FlashMessage::set('edit-error', [$validationException->getMessage()]);
        } finally {
            App::get('router')->redirect('course/edit/' . $id);
        }
    }

    public function validateTeacher(int $id)
    {
        try {
            $courseRepository = App::getRepository(CourseRepository::class);
            $course = $courseRepository->find($id);

            if (!isset($_POST['teacher']) || empty($_POST['teacher']))
                throw new ValidationException('Teacher can\'t be empty');
            $teacherId = htmlspecialchars(trim($_POST['teacher']));

            $teacherRepository = App::getRepository(TeacherRepository::class);
            $teacherKeys = array_map(fn ($key): string => "user.$key", array_keys((new Teacher())->toArray()));
            array_push($teacherKeys, "teacher.id");
            $teacher = $teacherRepository
                ->findInnerJoin(
                    $teacherKeys,
                    "user",
                    [
                        "user.id",
                        "teacher.id"
                    ],
                    [
                        "teacher__id" => $teacherId
                    ]
                );

            $course->setTeacher($teacher->getId());

            $courseRepository->update($course);
            FlashMessage::set('message', "Teacher is now {$teacher->getUsername()}");
        } catch (ValidationException $validationException) {
            FlashMessage::set('edit-error', [$validationException->getMessage()]);
        } finally {
            App::get('router')->redirect('course/edit/' . $id );
        }
    }

    public function validateCoursePicture(int $id)
    {
        try {
            $courseRepository = App::getRepository(CourseRepository::class);
            $course = $courseRepository->find($id);

            $typeFile = ['image/jpeg', 'image/png'];
            $picFile = new File('coursePicture', $typeFile);
            $picFile->saveUploadFile(CoursePicture::COURSE_PICTURES_ROUTE);

            $image = new Image($picFile->getFileName());
            $imageObj = App::getRepository(ImageRepository::class)->saveAndReturn($image, [
                "name" => $image->getName()
            ]);
            $coursePicture = new CoursePicture(
                $imageObj->getId(),
                $imageObj->getName(),
                $course->getId()
            );
            App::getRepository(CoursePictureRepository::class)->updateCoursePicture($course, $coursePicture, $imageObj);
            FlashMessage::set('message', "Course's picture updated");
        } catch (FileException $fileException) {
            FlashMessage::set('edit-error', [$fileException->getMessage()]);
        } catch (ValidationException $validationException) {
            FlashMessage::set('edit-error', [$validationException->getMessage()]);
        } finally {
            App::get('router')->redirect('course/edit/' . $id );
        }
    }

    public function validateLanguage(int $id)
    {
        try {
            $courseRepository = App::getRepository(CourseRepository::class);
            $course = $courseRepository->find($id);

            if (!isset($_POST['language']) || empty($_POST['language']))
                throw new ValidationException('Language can\'t be empty');
            $language = htmlspecialchars(trim($_POST['language']));

            $course->setLanguage($language);

            $courseRepository->update($course);

            $language = App::getRepository(LanguageRepository::class)->find($language)->getName();
            FlashMessage::set('message', "Language updated to $language");
        } catch (ValidationException $validationException) {
            FlashMessage::set('edit-error', [$validationException->getMessage()]);
        } finally {
            App::get('router')->redirect('course/edit/' . $id);
        }
    }
}
