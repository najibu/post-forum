<?php

use App\Models\Post;
use App\Models\User;
use function Pest\Laravel\post;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->validData = [
        'title' => 'My title is the best title',
        'body' => 'My bOur mission at Adblock Plus is to create a worry-free and distraction-free internet for everyone. That’s why we developed our extension to block pop-ups, annoying ads, and other distractions across the web. Adblock Plus helps you to browse the web faster, safer, and with more control over your internet experience.ody is the best This is my very first post!body of this body',
    ];
});

it('requires authentication', function () {
    post(route('posts.store'))
            ->assertRedirect(route('login'));
});

it('stores a post', function () {
    $user = User::factory()->create();

    actingAs($user)->post(route('posts.store'), $this->validData);

    $this->assertDatabaseHas(Post::class, [
        ...$this->validData,
        'user_id' => $user->id,
    ]);
});

it('redirects to the post show page', function () {
    $user = User::factory()->create();
    actingAs($user)
        ->post(route('posts.store'), $this->validData)
        ->assertRedirect(route('posts.show', Post::latest('id')->first()));
});

it('requires valid data', function (array $badData, array|string $errors) {
    $user = User::factory()->create();
    actingAs($user)
        ->post(route('posts.store'), [ ...$this->validData, ...$badData])
        ->assertInvalid($errors);
})->with([
    [['title' => null], 'title'],
    [['title' => true], 'title'],
    [['title' => 1], 'title'],
    [['title' => 1.5], 'title'],
    [['title' => str_repeat('a', 121)], 'title'],
    [['title' => str_repeat('a', 9)], 'title'],
    [['body' => null], 'body'],
    [['body' => true], 'body'],
    [['body' => 1], 'body'],
    [['body' => 1.5], 'body'],
    [['body' => str_repeat('a', 10_001)], 'body'],
    [['body' => str_repeat('a', 99)], 'body'],
]);
