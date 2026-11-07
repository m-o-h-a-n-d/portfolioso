<?php

namespace App\Services;

use App\Repository\BlogRepository;
use App\Utils\ImageManager;
use Illuminate\Http\UploadedFile;

class BlogService
{
    public function __construct(
        protected BlogRepository $blogRepository,
        protected ImageManager $imageManager
    ) {}

    public function getBlogs()
    {
        return $this->blogRepository->getBlogs();
    }

    public function getBlog($id)
    {
        return $this->blogRepository->getBlog($id) ?? false;
    }

    public function store($data)
    {
        if (isset($data['cover']) && $data['cover'] instanceof UploadedFile) {
            $data['cover'] = $this->imageManager->uploadSingleImage($data['cover'], 'blogs');
        }

        return $this->blogRepository->store($data);
    }

    public function update($data, $id)
    {
        $blog = $this->blogRepository->getBlog($id);
        if (! $blog) {
            return false;
        }

        if (isset($data['cover'])) {
            if ($data['cover'] instanceof UploadedFile) {
                $data['cover'] = $this->imageManager->uploadSingleImage($data['cover'], 'blogs', null, $blog->cover);
            } elseif (is_string($data['cover']) && $data['cover'] !== $blog->cover && $blog->cover) {
                $this->imageManager->deleteImageFromLocal($blog->cover);
            }
        }

        return $this->blogRepository->update($blog, $data);
    }

    public function delete($id)
    {
        $blog = $this->blogRepository->getBlog($id);
        if (! $blog) {
            return false;
        }

        if ($blog->cover) {
            $this->imageManager->deleteImageFromLocal($blog->cover);
        }

        return $this->blogRepository->delete($blog);
    }
}
