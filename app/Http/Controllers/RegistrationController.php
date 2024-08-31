<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class RegistrationController extends Controller
{
    // Display the registration form
    public function showRegistrationForm()
    {
        $roles = Role::all(); // Fetch all roles from the database
        return view('registration', compact('roles'));
    }

    // Handle registration form submission
    public function register(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'login_name' => 'required|string|max:255|unique:users,login_name',
            'password' => 'required|string|min:8',
            'role_id' => 'required|integer|exists:roles,id',
        ]);
    
        // Create a new user
        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'login_name' => $validatedData['login_name'],
            'password' => bcrypt($validatedData['password']),
            'is_active' => 1,
            'role_id' => $validatedData['role_id'],
        ]);
    
        // Redirect with success message
        return redirect()->route('login')->with('success', 'Registration successful!');
    }
    

    // Optionally update user information
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validatedData = $request->validate([
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'login_name' => 'string|max:255|unique:users,login_name,' . $id,
            'password' => 'string|min:8',
            'role_id' => 'integer|exists:roles,id', // Ensure role_id is validated
        ]);

        $user->update($validatedData);
        return response()->json($user);
    }

    // Optionally delete a user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }
}
