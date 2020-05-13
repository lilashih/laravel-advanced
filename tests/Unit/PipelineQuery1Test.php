<?php

namespace Tests\Unit;

use App\Models\Pipeline\User;
use App\Services\Pipelines\Queries\Query1\WhereLike;
use App\Services\Pipelines\Queries\Query1\WhereUser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Pipeline\Post;
use Illuminate\Pipeline\Pipeline;

class PipelineQueryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @group pipeline
     */
    public function testQuery()
    {
        $this->createFaker();

        $posts = Post::where('title', 'like', "%a%")->where('content', 'like', "%b%")->get();
        $result = app(Pipeline::class)
            ->send(Post::query())
            ->through([
                WhereLike::class.':title,a',
                WhereLike::class.':content,b',
            ])
            ->then(function ($query) {
                return $query->get();
            });

        $this->assertCount($posts->count(), $result);
    }

    /**
     * @group pipeline
     */
    public function testQueryUser()
    {
        $this->createFaker();

        $user = User::first();
        $posts = Post::where('user_id', $user->id)->get();

        $result = app(Pipeline::class)
            ->send(Post::query())
            ->through([
                WhereUser::class.':'.$user->id,
            ])
            ->then(function ($query) {
                return $query->get();
            });

        $this->assertCount($posts->count(), $result);
    }

    public function createFaker()
    {
        factory(Post::class, 5)->create();
    }
}
