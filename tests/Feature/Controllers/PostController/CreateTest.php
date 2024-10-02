<?php

use App\Models\User;
use App\Models\Topic;
use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;
use App\Http\Resources\TopicResource;

it('requires authentication', function () {
    get(route('posts.create'))->assertRedirect(route('login'));
});

it('it requires the correct component', function () {
    actingAs(User::factory()->create())
        ->get(route('posts.create'))
        ->assertComponent('Posts/Create');
});

it('passes topics to the view', function () {
    $topics = Topic::factory(2)->create();

    actingAs(User::factory()->create())
        ->get(route('posts.create'))
        ->assertHasResource('topics', TopicResource::collection($topics));
});
