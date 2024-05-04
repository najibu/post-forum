<?php

use App\Models\Post;
use App\Models\Comment;
use function Pest\Laravel\get;
use App\Http\Resources\PostResource;

use Inertia\Testing\AssertableInertia;
use App\Http\Resources\CommentResource;

it('can show a post', function () {
    $post = Post::factory()->create();

    get(route('posts.show', $post))
        ->assertComponent('Posts/Show');
});

it('passes a post to the view', function () {
    $post = Post::factory()->create();

    $post->load('user');

    get(route('posts.show', $post))
        ->assertHasResource('post', PostResource::make($post));
});

it('passes a comment to the view', function () {
    $this->withoutExceptionHandling();
    $post = Post::factory()->create();
    $comments = Comment::factory(2)->for($post)->create();

    $comments->load('user');

    get(route('posts.show', $post))
        ->assertHasPaginatedResource('comments', CommentResource::collection($comments->reverse()));
});

