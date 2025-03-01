<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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
            'username'=>'required',
            'password'=> 'required',
            'full_name' => 'required',
            'email'=> 'required',
            'avatar'=>'nullable|file|mimes:jpg,jpeg,png',
            'phone_number'=>'required',
            'address'=>'required',

        ]);
        $user = User::find($id);
        if($request->hasFile('avatar')){
            if($user->avatar){
                Storage::disk('public')->delete($user->avatar);
            }
            $part = $request->file('avatar')->store('uploads/users','public');
        }else{
            $part = $user->avatar;
        }
        $user = User::create([
            'username'=>$validate['username'],
            'password'=>$validate['password'],
            'full_name'=>$validate['full_name'],
            'email'=>$validate['email'],
            'avatar'=>$part,
            'phone_number'=>$validate['phone_number'],
            'address'=>$validate['address'],
            'role'=>0,
        ]);
        return redirect()->route('users.list');
    }

    public function ban(Request $request)
    {
        $user = User::findOrFail($request->user_id); // Tìm user theo ID
        if ($user->role == 1) {
            return redirect()->back()->with('error', 'Không thể khóa tài khoản này!');
        }
        if ($user->status == 0) {
            $user->status = 1; // Khóa tài khoản
            $user->ban_reason = $request->ban_reason; // Lưu lý do khóa
            $user->banned_at = now(); // Lưu thời gian khóa
        }
        $user->save(); // Lưu vào database
    
        return redirect()->route('users.list')->with('success', 'Trạng thái đã được cập nhật!');
    }
    public function unban( $id){
        $user = User::findOrFail($id);
        if ($user->status == 0) {
            return redirect()->back()->with('error', 'Không thể mở khóa tài khoản này!');
        }
        $user->status = 0;
        $user->ban_reason = null;
        $user->banned_at = null;
        $user->save();
        return redirect()->route('users.list')->with('success', 'Trạng thái đã được cập nhật!');
    }
}
