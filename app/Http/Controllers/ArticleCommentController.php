<?php

namespace App\Http\Controllers;

use App\Models\ArticleComment;
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
        $validator = Validator::make(request()->all(),[
            'body' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        // $comment = ArticleComment::where('id', $comment_id)->update([
        //     'body' => $request->body,
        // ]);

        $user = auth()->user();
        $comment = ArticleComment::find($comment_id);
        if ($user->id != $comment->user_id) {
            return response()->json([
                'success' => false,
                'message' => "Kamu bukan pemilik komentar",
            ],403);
        }
        $comment->body = $request->body;
        $comment->save();

        return response()->json([
            'success' => true,
            'message' => "Komentar berhasil diubah",
            'data' => $comment,
        ]);
    }
    public function destroy($comment_id)
    {
        $user = auth()->user();
        $comment = ArticleComment::find($comment_id);
        if ($user->id != $comment->user_id) {
            return response()->json([
                'success' => false,
                'message' => "Kamu bukan pemilik komentar",
            ],403);
        }
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => "Article berhasil dihapus",
            'data' => $comment,
        ]);
    }
}
