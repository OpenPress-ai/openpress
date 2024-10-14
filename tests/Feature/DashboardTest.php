<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_loads_with_posts()
    {
        // Create a user
        $user = User::factory()->create();

        // Create some posts
        Post::factory()->count(5)->create(['user_id' => $user->id]);

        // Act as the user and visit the dashboard
        $response = $this->actingAs($user)->get('/dashboard');

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert that the dashboard view is returned
        $response->assertViewIs('dashboard');

        // Assert that the posts variable is passed to the view
        $response->assertViewHas('posts');

        // Assert that the page contains some expected content
        $response->assertSee('Recent Posts');
        $response->assertSee($user->name);
    }

    public function test_dashboard_loads_without_posts()
    {
        // Create a user
        $user = User::factory()->create();

        // Act as the user and visit the dashboard
        $response = $this->actingAs($user)->get('/dashboard');

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert that the dashboard view is returned
        $response->assertViewIs('dashboard');

        // Assert that the posts variable is passed to the view
        $response->assertViewHas('posts');

        // Assert that the page contains the expected content for no posts
        $response->assertSee('Recent Posts');
        $response->assertSee('No posts found.');
    }
}