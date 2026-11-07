<?php

namespace App\Services;

use App\Repository\SettingRepository;
use App\Utils\ImageManager;
use Illuminate\Http\UploadedFile;

class SettingService
{
    public function __construct(
        protected SettingRepository $settingRepository,
        protected ImageManager $imageManager
    ) {}

    public function getSettings()
    {
        return $this->settingRepository->getSettings();
    }

    public function getFirstSetting()
    {
        return $this->settingRepository->getFirstSetting() ?? false;
    }

    public function store($data)
    {
        if (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
            $data['icon'] = $this->imageManager->uploadSingleImage($data['icon'], 'settings', 'public');
        }

        if (isset($data['favicon']) && $data['favicon'] instanceof UploadedFile) {
            $data['favicon'] = $this->imageManager->uploadSingleImage($data['favicon'], 'settings', 'public');
        }

        return $this->settingRepository->store($data);
    }

    public function update($data)
    {
        $setting = $this->settingRepository->getFirstSetting();
        if (! $setting) {
            return $this->store($data);
        }

        if (isset($data['icon'])) {
            if ($data['icon'] instanceof UploadedFile) {
                $data['icon'] = $this->imageManager->uploadSingleImage($data['icon'], 'settings', null, $setting->icon);
            } elseif (is_string($data['icon']) && $data['icon'] !== $setting->icon && $setting->icon) {
                $this->imageManager->deleteImageFromLocal($setting->icon);
            }
        }

        if (isset($data['favicon'])) {
            if ($data['favicon'] instanceof UploadedFile) {
                $data['favicon'] = $this->imageManager->uploadSingleImage($data['favicon'], 'settings', null, $setting->favicon);
            } elseif (is_string($data['favicon']) && $data['favicon'] !== $setting->favicon && $setting->favicon) {
                $this->imageManager->deleteImageFromLocal($setting->favicon);
            }
        }

        return $this->settingRepository->update($setting, $data);
    }
}
