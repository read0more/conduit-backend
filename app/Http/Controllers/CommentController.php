<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\checkIdIsMe;

class CommentController extends Controller
{
    use CheckIdIsMe;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Article $article, Request $request)
    {
        $this->validate($request, [
            'comment.body' => 'required',
        ]);

        $commentFields = $request->all()['comment'];
        $body = $commentFields['body'];

        $comment = new Comment();
        $comment->body = $body;
        $comment->author()->associate(Auth::user());
        $comment->article()->associate($article);
        $comment->save();

        return response()->json(['comment' => $comment], 201);
    }

    public function read(Article $article)
    {
        return response()->json(['comments' => $article->comments()->get()], 200);
    }

    public function delete(Article $article, Comment $comment)
    {
        if (!$this->checkIdIsMe($comment->author()->first()->id)) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $comment->delete();
        return response('', 204);
    }
}
