<?php

use App\Models\User;
use App\Models\Comment;
use function Pest\Laravel\put;
use function Pest\Laravel\actingAs;

it('requires authentication', function () {
    put(route('comments.update', Comment::factory()->create()))
        ->assertRedirect(route('login'));
});

it('can update a comment', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', $comment), [
            'body' => 'This is an updated comment.',
        ]);

    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'body' => 'This is an updated comment.',
    ]);
});


it('redirects to the post show page', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', $comment), [
            'body' => 'This is an updated comment.',
        ])
        ->assertRedirect($comment->post->showRoute());
});

it('redirects to the correct page of comments', function () {
    $comment = Comment::factory()->create();
    $page = 2;

    actingAs($comment->user)
        ->put(route('comments.update', [ 'comment' => $comment, 'page' => $page]), [
            'body' => 'This is an updated comment.',
        ])
        ->assertRedirect($comment->post->showRoute(['page' => $page]));
});


it('cannot update a comment from another user', function () {
    $comment = Comment::factory()->create();

    actingAs(User::factory()->create())
        ->put(route('comments.update', ['comment' => $comment]), ['body' => 'This is an updated comment.'])
        ->assertForbidden();
});

it('requires a valid body', function ($body) {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', ['comment' => $comment]), ['body' => $body])
        ->assertInvalid('body');
})->with([
    null,
    1,
    1.5,
    true,
    str_repeat('a', 2501),
]);
