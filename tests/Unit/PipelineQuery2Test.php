<?php

namespace Tests\Unit;

use App\Models\Pipeline\User;
use App\Services\Pipelines\Queries\Query2\WhereLike;
use App\Services\Pipelines\Queries\Query2\WhereUser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Pipeline\Post;
use Illuminate\Pipeline\Pipeline;

class PipelineQuery2Test extends TestCase
{
    use DatabaseMigrations;

    /**
     * @group pipeline
     */
    public function testQueryLike()
    {
        $this->createFaker();

        $posts = Post::where('title', 'like', "%a%")->where('content', 'like', "%b%")->get();
        $result = app(Pipeline::class)
            ->send(Post::query())
            ->through([
                new WhereLike('title', 'a'),
                new WhereLike('content', 'b'),
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
                new WhereUser($user->id),
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
