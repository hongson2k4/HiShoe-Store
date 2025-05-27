<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'content' => 'required|string|max:500',
        ]);

        Comment::create($request->all());

        return redirect()->back()->with('success', 'Bình luận của bạn đã được gửi.');
    }
}