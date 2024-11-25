<?php

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use function Pest\Laravel\delete;
use function Pest\Laravel\actingAs;
use Illuminate\Database\Eloquent\Model;

it('requires authentication', function () {
    delete(route('likes.destroy', ['type' => 'posts', 'id' => 1]))
        ->assertRedirect(route('login'));
});


it('allows unliking a likeable', function (Model $likeable) {
    $user = User::factory()->create();
    $like = Like::factory()->for($user)->for($likeable, 'likeable')->create();

    actingAs($user)
        ->fromRoute('dashboard')
        ->delete(route('likes.destroy', [$likeable->getMorphClass(), $likeable->id]))
        ->assertRedirect(route('dashboard'));

    $this->assertDatabaseEmpty(Like::class);

    expect($likeable->fresh()->likes_count)->toBe(0);
})->with([
    fn () => Post::factory()->create(['likes_count' => 1]),
    fn () => Comment::factory()->create(['likes_count' => 1]),
]);

it('prevents unliking something you havent liked', function () {
    $user = User::factory()->create();
    $likeable = Post::factory()->create();

    actingAs($user)
        ->delete(route('likes.destroy', [$likeable->getMorphClass(), $likeable->id]))
        ->assertForbidden();
});

it('only allows unliking supported models', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->delete(route('likes.destroy', [$user->getMorphClass(), $user->id]))
        ->assertForbidden();
});

it('throws a 404 if the type is unsupported', function () {
    actingAs(User::factory()->create())
        ->delete(route('likes.destroy', ['unsupported', 1]))
        ->assertNotFound();
});
