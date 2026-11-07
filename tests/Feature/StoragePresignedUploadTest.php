<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoragePresignedUploadTest extends TestCase
{
    public function test_guest_cannot_request_presigned_upload_url(): void
    {
        $response = $this->postJson('/api/admin/storage/upload-url', [
            'file_name' => 'test.png',
            'file_type' => 'image/png',
        ]);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_request_presigned_upload_url(): void
    {
        $user = User::factory()->make(['id' => 1]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/admin/storage/upload-url', [
            'file_name' => 'cover-image.jpg',
            'file_type' => 'image/jpeg',
            'folder' => 'blogs',
            'expires_in' => 600,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'upload_url',
                'method',
                'file_path',
                'file_url',
                'headers',
                'expires_at',
                'expires_in',
                'folder',
            ],
        ]);

        $this->assertEquals(600, $response->json('data.expires_in'));
        $this->assertEquals('blogs', $response->json('data.folder'));
        $this->assertStringContainsString('uploads/blogs/', $response->json('data.file_path'));
    }

    public function test_dangerous_file_extensions_are_rejected(): void
    {
        $user = User::factory()->make(['id' => 1]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/admin/storage/upload-url', [
            'file_name' => 'malicious.php',
            'file_type' => 'application/x-php',
            'folder' => 'blogs',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['file_name']);
    }
}

