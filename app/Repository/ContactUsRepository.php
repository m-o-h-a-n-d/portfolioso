<?php

namespace App\Repository;

use App\Models\ContactUs;

class ContactUsRepository
{
    public function store($request)
    {
        return ContactUs::create($request);
    }

    public function getAll()
    {
        return ContactUs::orderByDesc('id')->get();
    }

    public function getContactUs($id)
    {
        return ContactUs::find($id);
    }

    public function markRead($contactUs)
    {
        return $contactUs->update(['read' => true]);
    }

    public function markAllRead()
    {
        return ContactUs::where('read', false)->update(['read' => true]);
    }

    public function delete($contactUs)
    {
        return $contactUs->delete();
    }
}
