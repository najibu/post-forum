<?php

use App\Models\Post;

it('uses title case for titles', function () {
    $post = Post::factory()->create([
        'title' => 'My title is the best title',
    ]);

    expect($post->title)->toBe('My Title Is The Best Title');
});
