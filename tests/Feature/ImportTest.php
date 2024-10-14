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
    
    $posts = Post::all();
    expect($posts)->toHaveCount(4);

    $helloWorld = $posts->firstWhere('slug', 'hello-world');
    expect($helloWorld)->not->toBeNull();
    expect($helloWorld->title)->toBe('Hello world!');
    expect($helloWorld->type)->toBe('post');
    expect($helloWorld->status)->toBe('published');

    $samplePage = $posts->firstWhere('slug', 'sample-page');
    expect($samplePage)->not->toBeNull();
    expect($samplePage->title)->toBe('Sample Page');
    expect($samplePage->type)->toBe('page');
    expect($samplePage->status)->toBe('published');

    $privacyPolicy = $posts->firstWhere('slug', 'privacy-policy');
    expect($privacyPolicy)->not->toBeNull();
    expect($privacyPolicy->title)->toBe('Privacy Policy');
    expect($privacyPolicy->type)->toBe('page');
    expect($privacyPolicy->status)->toBe('draft');

    $goodbyeWordPress = $posts->firstWhere('slug', 'goodbye-wordpress');
    expect($goodbyeWordPress)->not->toBeNull();
    expect($goodbyeWordPress->title)->toBe('Goodbye WordPress');
    expect($goodbyeWordPress->type)->toBe('post');
    expect($goodbyeWordPress->status)->toBe('published');

    // Check if tags are attached to posts
    $tag = Tag::first();
    expect($tag)->not->toBeNull();
    expect($tag->posts)->toHaveCount(4);

    // Check if each post has the tag
    foreach ($posts as $post) {
        expect($post->tags)->toHaveCount(1);
        expect($post->tags->first()->name)->toBe('#wordpress');
    }

    // Check if authors are attached to posts
    $user = User::first();
    expect($user)->not->toBeNull();
    expect($user->posts)->toHaveCount(4);

    // Check if each post has the author
    foreach ($posts as $post) {
        expect($post->author)->not->toBeNull();
        expect($post->author->name)->toBe('chris');
    }
});

uses()->group('import')->in(__FILE__);