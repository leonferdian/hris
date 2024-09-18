<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // List all users
    public function index()
    {
        return User::all();
    }

    // Get a single user
    public function show($id)
    {
        return User::findOrFail($id);
    }

    // Create a new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
        ]);

        return User::create($request->all());
    }

    // Update an existing user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
        ]);

        $user->update($request->all());

        return $user;
    }

    // Delete a user
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}

