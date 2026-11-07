<?php

namespace App\Services;

use App\Repository\CourseRepository;
use App\Utils\ImageManager;
use Illuminate\Http\UploadedFile;

class CourseService
{
    public function __construct(
        protected CourseRepository $courseRepository,
        protected ImageManager $imageManager
    ) {}

    public function getCourses()
    {
        return $this->courseRepository->getCourses();
    }

    public function getCourse($id)
    {
        return $this->courseRepository->getCourse($id) ?? false;
    }

    public function store($data)
    {
        if (isset($data['cover']) && $data['cover'] instanceof UploadedFile) {
            $data['cover'] = $this->imageManager->uploadSingleImage($data['cover'], 'courses');
        }

        $course = $this->courseRepository->store($data);

        if (! $course) {
            return false;
        }

        return $course->fresh('lectures');
    }

    public function update($data, $id)
    {
        $course = $this->courseRepository->getCourse($id);
        if (! $course) {
            return false;
        }

        if (isset($data['cover'])) {
            if ($data['cover'] instanceof UploadedFile) {
                $data['cover'] = $this->imageManager->uploadSingleImage($data['cover'], 'courses', null, $course->cover);
            } elseif (is_string($data['cover']) && $data['cover'] !== $course->cover && $course->cover) {
                $this->imageManager->deleteImageFromLocal($course->cover);
            }
        }

        return $this->courseRepository->update($course, $data);
    }

    public function delete($id)
    {
        $course = $this->courseRepository->getCourse($id);
        if (! $course) {
            return false;
        }

        return $this->courseRepository->delete($course);
    }
}
