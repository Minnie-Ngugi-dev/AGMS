<?php
namespace App\Http\Controllers;

use App\Models\{GarageSetting, Staff};
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller {
    public function users()   { $users = User::latest()->get(); return view('admin.users', compact('users')); }
    public function catalog() { return view('admin.catalog'); }
    public function settings(){ $settings = GarageSetting::get(); return view('admin.settings', compact('settings')); }

    public function storeUser(Request $request) {
        $request->validate(['name'=>'required','email'=>'required|email|unique:users','role'=>'required','password'=>'required|min:8']);
        User::create(['name'=>$request->name,'email'=>$request->email,'role'=>$request->role,'password'=>Hash::make($request->password),'is_active'=>true]);
        return back()->with('success','User created.');
    }
    public function toggleUser(User $user) {
        $user->update(['is_active'=>!$user->is_active]);
        return back()->with('success','User '.($user->is_active?'activated':'deactivated').'.');
    }
    public function updateSettings(Request $request) {
        $settings = GarageSetting::get();
        $data = $request->except(['_token','_method','logo']);
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos','public');
        }
        $settings->update($data);
        return back()->with('success','Settings saved.');
    }
}