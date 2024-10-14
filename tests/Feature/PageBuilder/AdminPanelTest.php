<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::create(['name' => 'admin']);
});

test('admin can see page builder in admin panel', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $response = $this->actingAs($user)->get('/admin');

    $response->assertStatus(200);
    $response->assertSee('Page Builder');
});

test('admin can access page builder section', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $response = $this->actingAs($user)->get('/admin/page-builder');

    $response->assertStatus(200);
    $response->assertSee('Page Builder Dashboard');
});

test('admin can see list of pages in page builder', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $response = $this->actingAs($user)->get('/admin/page-builder/pages');

    $response->assertStatus(200);
    $response->assertSee('Pages');
});

test('admin can create new page with page builder', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $response = $this->actingAs($user)->get('/admin/page-builder/pages/create');

    $response->assertStatus(200);
    $response->assertSee('Create New Page');
});

test('admin can edit existing page with page builder', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    // Assume we have a page with ID 1
    $response = $this->actingAs($user)->get('/admin/page-builder/pages/1/edit');

    $response->assertStatus(200);
    $response->assertSee('Edit Page');
});

test('non admin cannot access page builder section', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin/page-builder');

    $response->assertStatus(403);
});