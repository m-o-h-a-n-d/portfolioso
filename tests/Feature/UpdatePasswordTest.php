<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('authenticated admin can update password from settings', function () {
    $user = User::factory()->create([
        'password' => Hash::make('OldPassword123!'),
    ]);

    Sanctum::actingAs($user);

    $response = $this->putJson('/api/admin/user/password', [
        'current_password' => 'OldPassword123!',
        'password' => 'NewPassword456!',
        'password_confirmation' => 'NewPassword456!',
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('status', 200)
        ->assertJsonPath('message', 'Password updated successfully');

    expect(Hash::check('NewPassword456!', $user->fresh()->password))->toBeTrue();
});

test('password update fails when current password is incorrect', function () {
    $user = User::factory()->create([
        'password' => Hash::make('OldPassword123!'),
    ]);

    Sanctum::actingAs($user);

    $response = $this->putJson('/api/admin/user/password', [
        'current_password' => 'WrongPassword!',
        'password' => 'NewPassword456!',
        'password_confirmation' => 'NewPassword456!',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['current_password']);

    expect(Hash::check('OldPassword123!', $user->fresh()->password))->toBeTrue();
});

