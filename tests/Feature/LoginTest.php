<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password')
    ]);
});

it('login with valid credentials', function () {
    $user = [
        'email' => $this->user->email,
        'password' => 'password'
    ];

    $response = $this->postJson('/api/auth/login', $user);

    $response->assertStatus(200)
        ->assertJsonStructure(['message']);
});

it('faild in login with invalid credentials', function () {
    $response = $this->postJson('/api/auth/login', [
        'email' => '',
    ]);

    $response->assertUnprocessable();
});
