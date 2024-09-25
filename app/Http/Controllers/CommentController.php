<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    public function store(Request $request, Post $post)
    {

        $validatedData = $request->validate([
            'body' => 'required|string',
        ]);


        $comment = $post->comments()->create([
            'body' => $validatedData['body'],
            'user_id' => Auth::id(),
        ]);

        return response()->json($comment, 201);
    }


    public function show(Comment $comment)
    {
        return response()->json($comment);
    }


    public function update(Request $request, Comment $comment)
    {

        if ($comment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }


        $validatedData = $request->validate([
            'body' => 'required|string',
        ]);


        $comment->update($validatedData);

        return response()->json($comment);
    }


    public function destroy(Comment $comment)
    {

        if ($comment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }


        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
