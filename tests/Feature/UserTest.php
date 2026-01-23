<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);
});

it('return a user list', function () {
    $response = $this->getJson('api/users');

    $response->assertOk();
});


it('return a specific user', function () {
    $user = User::factory()->create();

    $response = $this->getJson('api/users/' . $user->id);

    $response
        ->assertOk()
        ->assertJsonFragment([
            'email' => $user->email,
            'name' => $user->name,
        ]);
});

it('fail to return a non existing user', function () {
    $response = $this->getJson('api/users/2');

    $response->assertNotFound();
});

it('create a new user', function () {
    $user = [
        "name" => fake()->name(),
        "email" => "teste@teste.com",
        "password" => fake()->password(8),
    ];

    $response = $this->postJson('api/users', $user);

    $response
        ->assertCreated()
        ->assertJsonFragment([
            'email' => $user['email'],
            'name' => $user['name'],
        ]);
});

it('fail to create a user with invalid data', function () {
    $user = [
        "name" => 2,
    ];

    $response = $this->postJson('api/users', $user);

    $response->assertUnprocessable();
});

it('update an user', function () {
    $user = User::first();

    $updatedData = [
        'name' => fake()->name(),
    ];

    $response = $this->putJson('api/users/' . $user->id, $updatedData);

    $response
        ->assertOk()
        ->assertJsonFragment($updatedData);
});

it('delete an user', function () {
    $user = User::factory()->create();

    $response = $this->deleteJson('api/users/' . $user->id);

    $response->assertNoContent();
});