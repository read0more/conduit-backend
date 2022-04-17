<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Favorite;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'article' => 'required|array|min:3',
            'article.title' => 'required',
            'article.description' => 'required',
            'article.body' => 'required',
            'article.tagList' => 'array|min:1',
        ]);

        $user = $request->user();
        $articleFields = $request->all()['article'];
        $tagFields = $articleFields['tagList'] ?? [];

        $article = $user->articles()->create($articleFields);

        if (count($tagFields)) {
            $tagIds = [];
            foreach ($tagFields as $tag) {
                $tagIds[] = (Tag::create(['body' => $tag]))->id;
            }
            // todo: 중복인 tag는 새로 안만들어지게?
            // todo: article에 추가로 필요한 정보들 realworld 참고해서 넣어야 함
            $article->tags()->sync($tagIds);
        }

        return response()->json(['article' => $article], 201);
    }

    public function read(Request $request)
    {
        $author = $request->get('author');
        $tag = $request->get('tag');
        $favorited = $request->get('favorited');

        if ($author) {
            $articles = Article::whereHas('user', function ($query) use ($author) {
                $query->where('username', 'like', "%$author%");
            })->get();
        } else if ($tag) {
            $articles = Article::whereHas('tags', function ($query) use ($tag) {
                $query->where('body', '=', $tag);
            })->get();
        } else if ($favorited) {
            $articles = User::where('username', $favorited)->get();
        } else {
            $articles = Article::all();
        }

        return response()->json(['articles' => $articles, 'articlesCount' => $articles->count()], 200);
    }

    public function update(Request $request, Article $article)
    {
        $this->validate($request, [
            'article' => 'required|array|min:1',
            'article.body' => 'required',
        ]);

        $user = Auth::user();

        if ($user['id'] !== $article->user()->first()->id) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $fields = $request->all()['article'];
        $article->update($fields);

        return response()->json(['article' => $article], 200);
    }


    /**
     * @param Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function favorite(Article $article)
    {
        $user = Auth::user();
        $favorite = Favorite::whereBelongsTo($article)->whereBelongsTo($user)->first();

        if ($favorite) {
            $favorite->delete();
        } else {
            $favorite = new Favorite;
            $favorite->user()->associate($user);
            $favorite->article()->associate($article);
            $favorite->save();
        }

        return response()->json(['article' => $article->comments()->get()], 201);
    }
}
