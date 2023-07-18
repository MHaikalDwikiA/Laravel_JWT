<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleShow;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::latest()->paginate(2);
        return response()->json([
            'success' => true,
            'message' => "Article berhasil ditampilkan",
            'data' => $articles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'title' => 'required',
            'body' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $user = auth()->user();

        $article = $user->articles()->create([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Article berhasil ditambahkan",
            'data' => $article,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $articles = Article::with('comments')->find($id);
        return response()->json([
            'success' => true,
            'message' => "Article berhasil ditampilkan",
            'data' => new ArticleShow($articles),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make(request()->all(), [
            'title' => 'required',
            'body' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        // $article = Article::where('id', $id)->update([
        //     'title' => $request->title,
        //     'body' => $request->body,
        // ]);
        $user = auth()->user();

        $article = Article::find($id);
        if ($user->id != $article->user_id) {
            return response()->json([
                'success' => false,
                'message' => "Kamu bukan pemilik article",
            ],403);
        }
        $article->title = $request->title;
        $article->body = $request->body;
        $article->save();

        return response()->json([
            'success' => true,
            'message' => "Article berhasil diubah",
            'data' => $article,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => "Unauthenticated.",
            ], 401);
        }

        $article = Article::find($id);
        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => "Article not found.",
            ], 404);
        }

        if ($user->id != $article->user_id) {
            return response()->json([
                'success' => false,
                'message' => "You are not the owner of this article.",
            ], 403);
        }

        $article->delete();

        return response()->json([
            'success' => true,
            'message' => "Article successfully deleted.",
            'data' => $article,
        ]);

    }
}
