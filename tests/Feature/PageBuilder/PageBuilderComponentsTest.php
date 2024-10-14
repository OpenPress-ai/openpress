<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::create(['name' => 'admin']);
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
});

test('page builder can load required assets', function () {
    $response = $this->actingAs($this->admin)->get(route('page-builder.create'));

    $response->assertStatus(200);
    $response->assertSee('Page Builder');
    // Add assertions for required assets (CSS, JS) here
});

test('page builder can load available components', function () {
    $response = $this->actingAs($this->admin)->get(route('page-builder.create'));

    $response->assertStatus(200);
    $response->assertSee('Text Block');
    $response->assertSee('Image');
    $response->assertSee('Button');
    // Add more component assertions as needed
});

test('page builder can create a basic page structure', function () {
    $response = $this->actingAs($this->admin)->post(route('page-builder.store'), [
        'title' => 'Test Page',
        'slug' => 'test-page',
        'content' => json_encode([
            [
                'type' => 'text',
                'content' => 'This is a test page content.'
            ],
            [
                'type' => 'image',
                'src' => 'https://example.com/image.jpg',
                'alt' => 'Test Image'
            ]
        ])
    ]);

    $response->assertStatus(302);
    $this->assertDatabaseHas('pages', ['slug' => 'test-page']);
});

// Add more tests for editing, updating, and deleting pages