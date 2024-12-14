<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Client;
use Auth;
class AuthController extends Controller
{
    public function register(Request $request)
{
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:clients',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $client = Client::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'debts' => 0,
    ]);

    if (!$client) {
        return response()->json(['error' => 'Client not created'], 500);
    }

    // Assign role
    $role = Role::findByName('client', 'web');
    if ($role) {
        $client->assignRole($role);
    }

    // Assign permission
    $permission = Permission::findByName('view dashboard', 'web');
    if ($permission) {
        $client->givePermissionTo($permission);
    }

    return response()->json(['message' => 'User registered successfully.', 'user' => $client]);
}


public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string|min:8',
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['message' => 'Invalid login credentials'], 401);
    }

    $client = Auth::user();

    // Generate an access token
    $token = $client->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Login successful',
        'user' => $client,
        'token' => $token,
    ]);
}


public function logout(Request $request)
{
    // Revoke the current user's token if using Sanctum
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Logged out successfully']);
}

}
