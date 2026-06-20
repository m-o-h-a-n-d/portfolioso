<?php

namespace App\Http\Controllers;

use App\Http\Requests\AchievementRequest;
use App\Http\Resources\Achievement\AchievementCollection;
use App\Http\Resources\Achievement\AchievementResource;
use App\Services\AchievementService;

class AchievementController extends Controller
{
    public function __construct(protected AchievementService $achievementService) {}

    public function index()
    {
        $data = $this->achievementService->getAchievements();

        if ($data->isEmpty()) {
            return apiResponce(404, 'Not Found');
        }

        return apiResponce(200, 'Success', new AchievementCollection($data));
    }

    public function store(AchievementRequest $request)
    {

        $data = $request->validated();

        $achievement = $this->achievementService->store($data);

        if (! $achievement) {
            return apiResponce(400, 'Bad Request');
        }

        return apiResponce(200, 'Success', AchievementResource::make($achievement));
    }

    public function show($id)
    {
        $achievement = $this->achievementService->getAchievement($id);

        if (! $achievement) {
            return apiResponce(404, 'Not Found');
        }

        return apiResponce(200, 'Success', AchievementResource::make($achievement));
    }

    public function update(AchievementRequest $request, $id)
    {
        $data = $request->validated();

        if (! $this->achievementService->update($data, $id)) {
            return apiResponce(400, 'Bad Request');
        }

        $achievement = $this->achievementService->getAchievement($id);

        if (! $achievement) {
            return apiResponce(404, 'Not Found');
        }

        return apiResponce(200, 'Success', AchievementResource::make($achievement));
    }

    public function destroy($id)
    {
        if (! $this->achievementService->delete($id)) {
            return apiResponce(400, 'Bad Request');
        }

        return apiResponce(200, 'Success');
    }
}
