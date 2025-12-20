<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();

    Sanctum::actingAs($this->user);
});

it('return a successful response', function () {
    $response = $this->getJson('/api/tasks');

    $response->assertOk();
});

it('return a task list', function () {
    $task = Task::create([
        'title' => fake()->title(),
        'description' => fake()->text(),
        'status' => 'pending',
        'user_id' => $this->user->id
    ]);

    $response = $this->getJson('/api/tasks');

    $response
        ->assertOk()
        ->assertJsonFragment($task->toArray());
});

it('return a specific task', function () {
    $task = Task::create([
        'title' => fake()->title(),
        'description' => fake()->text(),
        'status' => 'pending',
        'user_id' => $this->user->id
    ]);

    $response = $this->getJson('/api/tasks/' . $task->id);

    $response
        ->assertOk()
        ->assertJsonFragment($task->toArray());
});

it('it fail to find a specific task', function () {
    $response = $this->getJson('/api/tasks/1');

    $response->assertNotFound();
});

it('create a new task', function () {
    $task = Task::factory()->make([
        'user_id' => $this->user->id
    ]);

    $response = $this->postJson('/api/tasks', $task->toArray());

    $response
        ->assertCreated()
        ->assertJsonFragment($task->toArray());
});

it('fail to create a task with invalid data', function () {
    $payload = [
        'title' => '',
        'description' => '',
        'status' => '',
    ];

    $response = $this->postJson('/api/tasks', $payload);

    $response->assertServerError();
});

it('update a task', function () {
    $task = Task::create([
        'title' => fake()->title(),
        'description' => fake()->text(),
        'status' => 'pending',
        'user_id' => $this->user->id
    ]);

    $payload = [
        'title' => fake()->title(),
    ];

    $response = $this->putJson('/api/tasks/' . $task->id, $payload);

    $response
        ->assertOk()
        ->assertJsonFragment($payload);
});

it('delete a task', function () {
    $task = Task::create([
        'title' => fake()->title(),
        'description' => fake()->text(),
        'status' => 'pending',
        'user_id' => $this->user->id
    ]);

    $response = $this->deleteJson('/api/tasks/' . $task->id);

    $response->assertNoContent();
});