<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     *
     * @param  Request  $request
     * @return Response
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
            foreach($tagFields as $tag) {
                $tagIds[] = (Tag::create(['body' => $tag]))->id;
            }
            // todo: 중복인 tag는 새로 안만들어지게?
            // todo: article에 추가로 필요한 정보들 realworld 참고해서 넣어야 함
            $article->tags()->sync($tagIds);
        }

        return response()->json(['article' => $article], 201);
    }
}
