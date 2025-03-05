<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Models\UserHistoryChanges;

class UserController extends Controller
{
    private $view;

    public function __construct(){
        $this->view = [];
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $role = $request->query('role');
        $address = $request->query('address');
    
        $users = User::query()
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('username', 'like', "%{$search}%")
                          ->orWhere('full_name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%")
                          ->orWhere('phone_number', 'like', "%{$search}%");
                });
            })
            ->when($status !== null, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($role !== null, function ($query) use ($role) {
                return $query->where('role', $role);
            })
            ->when($address, function ($query) use ($address) {
                return $query->where('address', 'like', "%{$address}%");
            })
            ->get();
    
        $provinces = json_decode(File::get(public_path('hanhchinhvn/tinh_tp.json')), true);
        $districts = json_decode(File::get(public_path('hanhchinhvn/quan_huyen.json')), true);
        $wards = json_decode(File::get(public_path('hanhchinhvn/xa_phuong.json')), true);
    
        foreach ($users as $user) {
            $addressParts = explode(', ', $user->address);
            
            if (count($addressParts) !== 3) {
                continue;
            }
        
            list($wardCode, $districtCode, $provinceCode) = $addressParts;
        
            $wardName = $wards[$wardCode]['name_with_type'] ?? 'N/A';
            $districtName = $districts[$districtCode]['name_with_type'] ?? 'N/A';
            $provinceName = $provinces[$provinceCode]['name_with_type'] ?? 'N/A';
        
            $user->address = "$wardName, $districtName, $provinceName";
        }
    
        $addresses = $users->pluck('address')->unique();
    
        return view("admin.users.list", compact("users", "addresses"));
    }

    public function show(string $id)
    {
        
    }

    public function edit(string $id)
    {
        $user = User::find($id);
        // dd($user);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'username' => 'required',
            'password' => 'required',
            'full_name' => 'required',
            'email' => 'required',
            'avatar' => 'nullable|file|mimes:jpg,jpeg,png',
            'phone_number' => 'required',
            'address' => 'required',
        ]);
    
        $user = User::find($id);
        $changes = [];
    
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $part = $request->file('avatar')->store('uploads/users', 'public');
        } else {
            $part = $user->avatar;
        }
    
        $fields = ['username', 'password', 'full_name', 'email', 'phone_number', 'address'];
        foreach ($fields as $field) {
            if ($user->$field != $validate[$field]) {
                $changes[] = [
                    'user_id' => $user->id,
                    'field_name' => $field,
                    'old_value' => $user->$field,
                    'new_value' => $validate[$field],
                    'change_by' => Auth::id(),
                    'content' => "Cập nhật thông tin người dùng! ",
                    'updated_at' => now(),
                ];
            }
        }
    
        $user->update([
            'username' => $validate['username'],
            'password' => $validate['password'],
            'full_name' => $validate['full_name'],
            'email' => $validate['email'],
            'avatar' => $part,
            'phone_number' => $validate['phone_number'],
            'address' => $validate['address'],
            'role' => 0,
        ]);
    
        UserHistoryChanges::insert($changes);
    
        return redirect()->route('users.list');
    }
    
    public function ban(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        if ($user->role == 1) {
            return redirect()->back()->with('error', 'Không thể khóa tài khoản này!');
        }
        if ($user->status == 0) {
            $user->status = 1;
            $user->ban_reason = $request->ban_reason;
            $user->banned_at = now();
    
            UserHistoryChanges::create([
                'user_id' => $user->id,
                'field_name' => 'status',
                'old_value' => 0,
                'new_value' => 1,
                'change_by' => Auth::id(),
                'content' => "Người dùng bị khóa tài khoản với lí do: {$request->ban_reason}",
                'updated_at' => now(),
            ]);
        }
        $user->save();
    
        return redirect()->route('users.list')->with('success', 'Trạng thái đã được cập nhật!');
    }
    
    public function unban($id)
    {
        $user = User::findOrFail($id);
        if ($user->status == 0) {
            return redirect()->back()->with('error', 'Không thể mở khóa tài khoản này!');
        }
        $user->status = 0;
        $user->ban_reason = null;
        $user->banned_at = null;
    
        UserHistoryChanges::create([
            'user_id' => $user->id,
            'field_name' => 'status',
            'old_value' => 1,
            'new_value' => 0,
            'change_by' => Auth::id(),
            'content' => "Gỡ khóa tài khoản người dùng!",
            'updated_at' => now(),
        ]);
    
        $user->save();
        return redirect()->route('users.list')->with('success', 'Trạng thái đã được cập nhật!');
    }
}
