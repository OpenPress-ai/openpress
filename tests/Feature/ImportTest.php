<?php

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Services\ImportService;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->importService = new ImportService();
});

it('imports json and adds data to the database', function () {
    $this->importService->importJson(base_path('docs/wp_openpress_export.json'));

    // Check if users were imported
    assertDatabaseCount('users', 1);
    assertDatabaseHas('users', [
        'name' => 'chris',
        'email' => 'chris@openagents.com',
    ]);

    // Check if tags were imported
    assertDatabaseCount('tags', 1);
    assertDatabaseHas('tags', [
        'name' => '#wordpress',
        'slug' => 'hash-wordpress',
    ]);

    // Check if posts were imported
    assertDatabaseCount('posts', 4);
    assertDatabaseHas('posts', [
        'title' => 'Hello world!',
        'slug' => 'hello-world',
        'type' => 'post',
        'status' => 'published',
    ]);
    assertDatabaseHas('posts', [
        'title' => 'Sample Page',
        'slug' => 'sample-page',
        'type' => 'page',
        'status' => 'published',
    ]);
    assertDatabaseHas('posts', [
        'title' => 'Privacy Policy',
        'slug' => 'privacy-policy',
        'type' => 'page',
        'status' => 'draft',
    ]);
    assertDatabaseHas('posts', [
        'title' => 'Goodbye WordPress',
        'slug' => 'goodbye-wordpress',
        'type' => 'post',
        'status' => 'published',
    ]);

    // Check if tags are attached to posts
    $tag = Tag::first();
    expect($tag->posts)->toHaveCount(4);

    // Check if authors are attached to posts
    $user = User::first();
    expect($user->posts)->toHaveCount(4);
});

uses()->group('import')->in(__FILE__);