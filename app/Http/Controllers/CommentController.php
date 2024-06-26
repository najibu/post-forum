<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $data = $request->validate([
            'body' => 'required|string|max:2500',
        ]);

        Comment::create([
            ...$data,
            'user_id' => $request->user()->id,
            'post_id' => $post->id,
        ]);

        return redirect($post->showRoute())
            ->banner('Comment added.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $data = $request->validate([
            'body' => 'required|string|max:2500',
        ]);

        $comment->update($data);

        return redirect($comment->post->showRoute(['page' => $request->query('page')]))
            ->banner('Comment updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Comment $comment)
    {
        $comment->delete();

        return redirect($comment->post->showRoute(['page' => $request->query('page')]))
            ->banner('Comment deleted.');
    }
}
