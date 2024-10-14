<?php

use App\Models\User;
use App\Models\Page;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::create(['name' => 'admin']);
});

test('admin can access page builder', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $response = $this->actingAs($user)->get('/admin/page-builder');

    $response->assertStatus(200);
});

test('non admin cannot access page builder', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin/page-builder');

    $response->assertStatus(403);
});

test('admin can create new page with page builder', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $response = $this->actingAs($user)->post('/admin/pages', [
        'title' => 'New Test Page',
        'slug' => 'new-test-page',
        'content' => json_encode([
            ['type' => 'text', 'content' => 'This is a test page.']
        ])
    ]);

    $response->assertStatus(302);
    $this->assertDatabaseHas('pages', ['slug' => 'new-test-page']);
});

test('admin can edit existing page with page builder', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    // Create a page
    $page = Page::create([
        'title' => 'Existing Test Page',
        'slug' => 'existing-test-page',
        'content' => json_encode([
            ['type' => 'text', 'content' => 'This is an existing test page.']
        ])
    ]);

    $response = $this->actingAs($user)->get("/admin/pages/{$page->id}/edit");

    $response->assertStatus(200);
    $response->assertSee('Existing Test Page');
});

test('admin can delete page', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $page = Page::factory()->create();

    $response = $this->actingAs($user)->delete("/admin/pages/{$page->id}");

    $response->assertStatus(302);
    $this->assertDatabaseMissing('pages', ['id' => $page->id]);
});