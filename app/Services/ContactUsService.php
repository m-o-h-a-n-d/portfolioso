<?php

namespace App\Services;

use App\Repository\ContactUsRepository;

class ContactUsService
{
    public function __construct(protected ContactUsRepository $contactUsRepository) {}

    public function getAll()
    {
        return $this->contactUsRepository->getAll();
    }

    public function getContactUs($id)
    {
        return $this->contactUsRepository->getContactUs($id) ?? false;
    }

    public function store($request)
    {
        return $this->contactUsRepository->store($request);
    }

    public function markRead($id)
    {
        $contactUs = $this->contactUsRepository->getContactUs($id);
        if (! $contactUs) {
            return false;
        }

        if ($contactUs->read) {
            return $contactUs;
        }

        if (! $this->contactUsRepository->markRead($contactUs)) {
            return false;
        }

        return $contactUs->fresh();
    }

    public function markAllRead()
    {
        return $this->contactUsRepository->markAllRead();
    }

    public function delete($id)
    {
        $contactUs = $this->contactUsRepository->getContactUs($id);
        if (! $contactUs) {
            return false;
        }

        return $this->contactUsRepository->delete($contactUs);
    }
}
