<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        Model::preventLazyLoading();

        Model::unguard();

        Relation::enforceMorphMap([
            'comment' => Comment::class,
            'post' => Post::class,
            'user' => User::class,
        ]);
    }
}
