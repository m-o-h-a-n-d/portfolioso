<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Http\Resources\ContactUs\ContactUsCollection;
use App\Http\Resources\ContactUs\ContactUsResource;
use App\Models\User;
use App\Notifications\ContactUsNotification;
use App\Services\ContactUsService;
use Illuminate\Support\Facades\Notification;

class ContactUsController extends Controller
{
    public function __construct(protected ContactUsService $contactUsService) {}

    public function index()
    {
        $data = $this->contactUsService->getAll();

        if ($data->isEmpty()) {
            return apiResponce(404, 'Not Found');
        }

        return apiResponce(200, 'Success', new ContactUsCollection($data));
    }

    public function store(ContactUsRequest $request)
    {
        $data = $request->validated();

        $contactUs = $this->contactUsService->store($data);

        if (! $contactUs) {
            return apiResponce(400, 'Bad Request');
        }

        $recipients = User::query()->get();
        if ($recipients->isNotEmpty()) {
            try {
                Notification::send($recipients, new ContactUsNotification($contactUs));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send contact notification: " . $e->getMessage());
            }
        }

        return apiResponce(200, 'Success', ContactUsResource::make($contactUs));
    }

    public function markRead($id)
    {
        $contactUs = $this->contactUsService->markRead($id);

        if (! $contactUs) {
            return apiResponce(404, 'Not Found');
        }

        return apiResponce(200, 'Success', ContactUsResource::make($contactUs));
    }

    public function markAllRead()
    {
        $this->contactUsService->markAllRead();
        return apiResponce(200, 'Success');
    }

    public function destroy($id)
    {
        if (! $this->contactUsService->delete($id)) {
            return apiResponce(404, 'Not Found');
        }

        return apiResponce(200, 'Success');
    }
}
