<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Display the users management page
    public function index()
    {
        return view('users'); // Ensure this view exists as resources/views/users.blade.php
    }

    // Fetch all users and their associated roles as JSON
    public function fetchUsersData()
    {
        // Fetch users with their roles
        $users = User::with('role')->get();
        return response()->json($users);
    }

    // Show a specific user by ID
    public function show($id)
    {
        try {
            $user = User::with('role')->findOrFail($id);
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'User not found.'], 404);
        }
    }

    // Store a new user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'login_name' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'required|integer',
        ]);

        $validated['password'] = bcrypt($validated['password']); // Hash the password

        User::create($validated);

        return response()->json(['success' => 'User created successfully.']);
    }

    // Update an existing user
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'login_name' => 'required|string|max:255|unique:users,login_name,' . $id,
            'password' => 'nullable|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'required|integer',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']); // Hash the password if provided
        } else {
            unset($validated['password']); // Remove password if not updating
        }

        $user = User::findOrFail($id);
        $user->update($validated);

        return response()->json(['success' => 'User updated successfully.']);
    }

    // Delete a user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => 'User deleted successfully.']);
    }
}
