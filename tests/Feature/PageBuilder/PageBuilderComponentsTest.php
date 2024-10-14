<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::create(['name' => 'admin']);
});

test('page builder routes are registered', function () {
    $response = $this->get('/admin/page-builder');
    $response->assertStatus(302); // Redirects to login if not authenticated
});

test('page builder middleware is applied correctly', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/admin/page-builder');
    $response->assertStatus(403); // Forbidden for non-admin users
});

test('page builder menu item exists in admin panel', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    
    $response = $this->actingAs($user)->get('/admin');
    $response->assertStatus(200);
    $response->assertSee('Page Builder');
});

test('page builder can load required assets', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    
    $response = $this->actingAs($user)->get('/admin/page-builder');
    $response->assertStatus(200);
    $response->assertSee('page-builder.js');
    $response->assertSee('page-builder.css');
});

test('page builder api endpoints are accessible', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    
    $response = $this->actingAs($user)->getJson('/api/page-builder/elements');
    $response->assertStatus(200);
});

test('page builder can create a basic page structure', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    
    $response = $this->actingAs($user)->postJson('/api/page-builder/pages', [
        'title' => 'Test Page',
        'slug' => 'test-page',
        'content' => json_encode([
            'type' => 'section',
            'children' => [
                [
                    'type' => 'text',
                    'content' => 'Hello, World!'
                ]
            ]
        ])
    ]);
    
    $response->assertStatus(201);
    $this->assertDatabaseHas('pages', ['slug' => 'test-page']);
});