<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {

    }

    /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Contracts\Support\Renderable
    */

    public function index()
    {
        $user = auth()->user(); // Fetch user details
        $imagePath = 'images/' . $user->id . '.jpg';
        $imageUrl = Storage::disk('public')->exists($imagePath)
            ? Storage::url($imagePath)
            : Storage::url('images/demo.jpg');

        $user = User::with('roles')->findOrFail($user->id); // Fetch user with roles
        $roles = Role::all(); // Fetch all available roles
        $permissions = Permission::all(); // Fetch all available permissions

        return view('user_profile.index', compact('user', 'imageUrl', 'roles', 'permissions'));
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $userId = auth()->id(); // Get the authenticated user's ID
            $file = $request->file('image');
            $fileName = $userId . '.jpg'; // Name the file using the user's ID
            $path = $file->storeAs('images', $fileName, 'public'); // Store the file with the new name

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => Storage::url($path),
            ]);
        }

        return response()->json(['success' => false], 400);
    }

    public function update(Request $request){

        // \dd($request->all());
        $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email',
            'mobile' => 'required',
            'rank' => 'required',
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->rank = $request->rank;
        $user->save();

        return redirect()->back()->withSuccess('Updated Successfully');
    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'currentPassword' => 'required|min:8|max:255',
            'newpassword' => 'required|string|min:8|',
            'reEnterNewPassword' => 'required|string|min:8|same:newpassword',
        ]);

        $user = auth()->user();
        // Check if the current password is correct
        if (!Hash::check($request->currentPassword, $user->password)) {
            return redirect()->back()->withErrors(['currentPassword' => 'Current password is not correct']);
        }

        // Update the user's password
        $user->password = Hash::make($request->newpassword);
        $user->save();

        return redirect()->back()->withSuccess('Password updated successfully');
    }

}
