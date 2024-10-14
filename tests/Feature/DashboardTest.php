<?php

use App\Models\User;
use App\Models\Post;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('dashboard loads with posts', function () {
    // Create some posts
    Post::factory()->count(5)->create(['author_id' => $this->user->id]);

    // Act as the user and visit the dashboard
    $response = actingAs($this->user)->get('/dashboard');

    // Assert the response is successful
    $response->assertStatus(200);

    // Assert that the dashboard view is returned
    $response->assertViewIs('dashboard');

    // Assert that the posts variable is passed to the view
    $response->assertViewHas('posts');

    // Assert that the page contains some expected content
    $response->assertSee('Recent Posts');
    $response->assertSee($this->user->name);
});

test('dashboard loads without posts', function () {
    // Act as the user and visit the dashboard
    $response = actingAs($this->user)->get('/dashboard');

    // Assert the response is successful
    $response->assertStatus(200);

    // Assert that the dashboard view is returned
    $response->assertViewIs('dashboard');

    // Assert that the posts variable is passed to the view
    $response->assertViewHas('posts');

    // Assert that the page contains the expected content for no posts
    $response->assertSee('Recent Posts');
    $response->assertSee('No posts found.');
});