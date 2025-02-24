<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    private $view;

    public function __construct(){
        $this->view = [];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $users = Users::query()
            ->when($search, function ($query) use ($search) {
                return $query->where('username', 'like', "%{$search}%")
                             ->orWhere('full_name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%")
                             ->orWhere('phone_number', 'like', "%{$search}%");
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
        // dd($users);
        return view("admin.users.list", compact("users"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.users.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
        if($request->hasFile('avatar')){
            $part = $request->file('avatar')->store('uploads/users','public');
        }else{
            $part = null;
        };
        $user = Users::create([
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Users::find($id);
        // dd($user);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
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
        $user = Users::find($id);
        if($request->hasFile('avatar')){
            if($user->avatar){
                Storage::disk('public')->delete($user->avatar);
            }
            $part = $request->file('avatar')->store('uploads/users','public');
        }else{
            $part = $user->avatar;
        }
        $user = Users::create([
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Users::findOrFail($id)->delete();
        return redirect()->route('users.list');
    }

    public function ban(string $id)
    {

    }
}
