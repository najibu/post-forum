<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LikeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $type, int $id)
    {
        $likeable = $this->findLikeable($type, $id);
        $this->authorize('create', [Like::class, $likeable]);

        $likeable->likes()->create([
            'user_id' =>$request->user()->id,
        ]);
        $likeable->increment('likes_count');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $type, int $id)
    {
        $likeable = $this->findLikeable($type, $id);
        $like = $likeable->likes()->where('user_id', $request->user()->id)->first();
        $this->authorize('delete', [Like::class, $likeable]);

        $like->delete();
        $likeable->decrement('likes_count');

        return back();
    }

    protected function findLikeable(string $type, int $id): Model
    {
        /** @var class-string<Model>|null */
        $modelName = Relation::getMorphedModel($type);

        if ($modelName === null) {
            throw new ModelNotFoundException();
        }

        $likeable = $modelName::findOrFail($id);
        return $likeable;
    }

}
