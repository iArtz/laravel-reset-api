<?php

namespace App\Http\Controllers;

use App\Http\Requests\Article\DestroyRequest;
use App\Http\Requests\Article\IndexRequest;
use App\Http\Requests\Article\StoreRequest;
use App\Http\Requests\Article\UpdateRequest;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\User;
use App\Services\ArticleService;

class ArticleController extends Controller
{
    protected Article $article;
    protected ArticleService $articleService;

    public function __construct(Article $article, ArticleService $articleService, User $user)
    {
        $this->article        = $article;
        $this->articleService = $articleService;
        $this->user           = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request): ArticleCollection
    {
        return new ArticleCollection($this->article->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreArticleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request): ArticleResource
    {
        $article = auth()->user()->articles()->create($request->validated()['article']);

        $this->articleService->syncTags($article, $request->validated()['article']['tagList'] ?? []);

        return $this->articleResponse($article);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article): ArticleResource
    {
        return $this->articleResponse($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateArticleRequest  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Article $article)
    {
        $article->update($request->validated()['article']);

        $this->articleService->syncTags($article, $request->validated()['article']['tagList'] ?? []);

        return $this->articleResponse($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article, DestroyRequest $request): void
    {
        $article->delete();
    }

    protected function articleResponse(Article $article): ArticleResource
    {
        return new ArticleResource($article->load('user', 'users', 'tags'));
    }
}
