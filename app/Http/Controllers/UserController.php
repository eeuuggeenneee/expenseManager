<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    public function authenticatedUser() {
        return response()->json(['user_id' => auth()->id()]);
    }
    
    public function store(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'password' => 'nullable|string|min:8',
            'role_id' => 'nullable|exists:roles,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {


            return response()->json(['errors' => $validator->errors()], 422);
        }

        $password = $request->input('password');
        if (is_null($password)) {
            $password = 'password123';
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($password);
        $user->role_id = $request->role_id;
        $user->save();
    }


    public function getAllUser()
    {
        $users = User::with('role')->get();
        return response()->json($users);
    }

    public function getUser($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }


    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'password' => 'nullable|string|min:8',
            'role_id' => 'nullable|exists:roles,id'
        ]);

        $user = User::findOrFail($id);

        if ($request->password) {
            $request->merge(['password' => bcrypt($request->password)]);
        } else {
            $request->except(['password']);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->save();



        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 204);
    }
}
