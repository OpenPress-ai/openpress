<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Services\ImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_importing_json_adds_data_to_the_database()
    {
        $importService = new ImportService();
        $importService->importJson(base_path('docs/wp_openpress_export.json'));

        // Check if users were imported
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'name' => 'chris',
            'email' => 'chris@openagents.com',
        ]);

        // Check if tags were imported
        $this->assertDatabaseCount('tags', 1);
        $this->assertDatabaseHas('tags', [
            'name' => '#wordpress',
            'slug' => 'hash-wordpress',
        ]);

        // Check if posts were imported
        $this->assertDatabaseCount('posts', 4);
        $this->assertDatabaseHas('posts', [
            'title' => 'Hello world!',
            'slug' => 'hello-world',
            'type' => 'post',
            'status' => 'published',
        ]);
        $this->assertDatabaseHas('posts', [
            'title' => 'Sample Page',
            'slug' => 'sample-page',
            'type' => 'page',
            'status' => 'published',
        ]);
        $this->assertDatabaseHas('posts', [
            'title' => 'Privacy Policy',
            'slug' => 'privacy-policy',
            'type' => 'page',
            'status' => 'draft',
        ]);
        $this->assertDatabaseHas('posts', [
            'title' => 'Goodbye WordPress',
            'slug' => 'goodbye-wordpress',
            'type' => 'post',
            'status' => 'published',
        ]);

        // Check if tags are attached to posts
        $tag = Tag::first();
        $this->assertCount(4, $tag->posts);

        // Check if authors are attached to posts
        $user = User::first();
        $this->assertCount(4, $user->posts);
    }
}