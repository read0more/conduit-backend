<?php

namespace App\Http\Middleware;

use App\Models\Article;
use Closure;
use Illuminate\Http\Request;

class CheckArticle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $slug = $request->slug;

        $article = Article::where('slug', $slug);

        try {
            if (!$article) {
                throw new \Exception('Article not founded');
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }

        return $next($request);
    }
}
