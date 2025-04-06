<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'user_id' => 'required|exists:users,id',
        'content' => 'required|string|max:1000',
    ]);

    $comment = new Comment();
    $comment->product_id = $validated['product_id'];
    $comment->user_id = $validated['user_id'];
    $comment->content = $validated['content'];
    $comment->save();

    return back()->with('success', 'Bình luận đã được gửi!');
}

}
