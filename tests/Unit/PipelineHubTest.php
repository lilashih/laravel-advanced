<?php

namespace Tests\Unit;

use App\Services\Pipelines\Hub\ArticleHub;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PipelineHubTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @group pipeline
     * @group hub
     */
    public function testTitle()
    {
        $hub = app(ArticleHub::class);
        $title = $hub->pipe('Hi Amy, do you want to eat an apple?', 'title');
        $this->assertTrue($title === 'hi my, d yu wnt t et n pple?');
    }

    /**
     * @group pipeline
     * @group hub
     */
    public function testBody()
    {
        $hub = app(ArticleHub::class);
        $title = $hub->pipe('hi Amy, do you want to eat an apple?', 'body');
        $this->assertTrue($title === 'Hi Amy, d yu want t eat an apple?');
    }
}
