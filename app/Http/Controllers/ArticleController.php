<?php

namespace App\Http\Controllers;

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
        $articles = Article::latest()->get();
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
        $validator = Validator::make(request()->all(),[
            'title' => 'required',
            'body' => 'required'
        ]);

        if($validator->fails()) {
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
    public function show(string $id)
    {
        $articles = Article::find($id);
        return response()->with('comments')->json([
            'success' => true,
            'message' => "Article berhasil ditampilkan",
            'data' => $articles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make(request()->all(),[
            'title' => 'required',
            'body' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        // $article = Article::where('id', $id)->update([
        //     'title' => $request->title,
        //     'body' => $request->body,
        // ]);

        $article = Article::find($id);
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
    public function destroy(string $id)
    { 
        $article = Article::find($id);
        $article->delete();

        return response()->json([
            'success' => true,
            'message' => "Article berhasil dihapus",
            'data' => $article,
        ]);
    }
}
