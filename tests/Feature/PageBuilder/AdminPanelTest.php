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

    $response = $this->actingAs($user)->get(route('page-builder.index'));

    $response->assertStatus(200);
});

test('non admin cannot access page builder', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('page-builder.index'));

    $response->assertStatus(403);
});

test('admin can create new page with page builder', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $pageData = [
        'title' => 'New Test Page',
        'slug' => 'new-test-page',
        'content' => json_encode([
            ['type' => 'text', 'content' => 'This is a test page.']
        ])
    ];

    $response = $this->actingAs($user)->post(route('page-builder.store'), $pageData);

    $response->assertStatus(302);
    $this->assertDatabaseHas('pages', ['slug' => 'new-test-page']);
});

test('admin can edit existing page with page builder', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $page = Page::factory()->create();

    $response = $this->actingAs($user)->get(route('page-builder.edit', $page));

    $response->assertStatus(200);
    $response->assertSee($page->title);
});

test('admin can delete page', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $page = Page::factory()->create();

    $response = $this->actingAs($user)->delete(route('page-builder.destroy', $page));

    $response->assertStatus(302);
    $this->assertDatabaseMissing('pages', ['id' => $page->id]);
});