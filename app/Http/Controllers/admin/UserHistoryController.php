<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\UserHistoryChanges;
use App\Models\User;
use Illuminate\Http\Request;

class UserHistoryController extends Controller
{
    public function index(string $id)
    {
        $user = User::find($id);
        $history = UserHistoryChanges::where('user_id', $id)
            ->with('changed_by') // Load user thực hiện thay đổi
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.users.history', compact('user', 'history'));
    }

}