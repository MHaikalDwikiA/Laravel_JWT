<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleCommentController extends Controller
{
    public function store(Request $request, $article_id)
    {
        $validator = Validator::make(request()->all(),[
            'body' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $user = auth()->user();

        $comment = $user->articleComments()->create([
            'article_id' => $article_id,
            'body' => $request->body,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Komentar berhasil ditambahkan",
            'data' => $comment,
        ]);
    }

    public function update(Request $request, $comment_id)
    {

    }
    public function destroy($comment_id)
    {

    }
}
