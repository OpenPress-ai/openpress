<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'test@example.com')->first();
    
    expect($user)->not->toBeNull();
    expect($response->getStatusCode())->toBe(302);
    expect(Auth::check())->toBeTrue();
    expect(Auth::id())->toBe($user->id);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});