<?php

namespace Tests\Feature;

use App\Models\Article;
use Tests\TestCase;

class AricleTest extends TestCase
{
    /**
     * Test show article
     *
     * @return void
     */
    public function testShowArticle()
    {
        $article = Article::factory()->create();

        $response = $this->get('api/articles/' . $article->slug)
            ->assertExactJson([
                'article' => [
                    'slug'      => $article->slug,
                    'title'     => $article->title,
                    'tagList'   => [],
                    'createdAt' => $article->created_at,
                    'updatedAt' => $article->updated_at,
                    'author'    => [
                        'name' => $article->user->name,
                    ],
                ],
            ]);
    }
}
