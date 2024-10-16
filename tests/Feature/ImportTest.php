<?php

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Services\ImportService;
use Illuminate\Support\Facades\Log;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->importService = new ImportService();
});

it('imports json and adds data to the database', function () {
    $jsonPath = base_path('docs/wp_openpress_export.json');
    $jsonContents = file_get_contents($jsonPath);
    
    if ($jsonContents === false) {
        Log::error("Failed to read JSON file: $jsonPath");
        $this->fail("Failed to read JSON file");
    }

    Log::info("JSON file contents (first 100 characters): " . substr($jsonContents, 0, 100));

    try {
        $decodedJson = json_decode($jsonContents, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("JSON decode error: " . json_last_error_msg());
            $this->fail("JSON decode error: " . json_last_error_msg());
        }
    } catch (\Exception $e) {
        Log::error("Exception during JSON decode: " . $e->getMessage());
        $this->fail("Exception during JSON decode: " . $e->getMessage());
    }

    Log::info("Decoded JSON structure: " . print_r(array_keys($decodedJson), true));

    $this->importService->importJson($jsonContents);

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

    $helloWorld = $posts->firstWhere('wordpress_id', 1);
    expect($helloWorld)->not->toBeNull();
    expect($helloWorld->title)->toBe('Hello world!');
    expect($helloWorld->type)->toBe('post');
    expect($helloWorld->status)->toBe('published');

    $samplePage = $posts->firstWhere('wordpress_id', 2);
    expect($samplePage)->not->toBeNull();
    expect($samplePage->title)->toBe('Sample Page');
    expect($samplePage->type)->toBe('page');
    expect($samplePage->status)->toBe('published');

    $privacyPolicy = $posts->firstWhere('wordpress_id', 3);
    expect($privacyPolicy)->not->toBeNull();
    expect($privacyPolicy->title)->toBe('Privacy Policy');
    expect($privacyPolicy->type)->toBe('page');
    expect($privacyPolicy->status)->toBe('draft');

    $goodbyeWordPress = $posts->firstWhere('wordpress_id', 6);
    expect($goodbyeWordPress)->not->toBeNull();
    expect($goodbyeWordPress->title)->toBe('Goodbye WordPress');
    expect($goodbyeWordPress->type)->toBe('post');
    expect($goodbyeWordPress->status)->toBe('published');

    // Check if tags are attached to posts
    $tag = Tag::first();
    expect($tag)->not->toBeNull();
    $taggedPosts = $tag->posts;
    expect($taggedPosts)->toHaveCount(4, "Expected 4 posts to be tagged, but found " . $taggedPosts->count() . ". Tagged post WordPress IDs: " . $taggedPosts->pluck('wordpress_id')->implode(', '));

    // Check if each post has the tag
    foreach ($posts as $post) {
        expect($post->tags)->toHaveCount(1, "Post WordPress ID {$post->wordpress_id} ({$post->title}) should have 1 tag, but has " . $post->tags->count());
        expect($post->tags->first()->name)->toBe('#wordpress', "Post WordPress ID {$post->wordpress_id} ({$post->title}) should have the '#wordpress' tag");
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