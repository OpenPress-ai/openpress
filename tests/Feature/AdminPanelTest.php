<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::create(['name' => 'admin']);
});

test('admin can see admin panel', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $response = $this->actingAs($user)->get('/admin');

    $response->assertStatus(200);
    $response->assertSee('Admin Panel');
});

test('non-admin cannot see admin panel', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin');

    $response->assertStatus(403);
});
