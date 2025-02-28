<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;


use Exception;

class AuthController extends Controller
{
    public function storeRegister(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|min:3',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|confirmed|min:8'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);

        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function indexRegister(): JsonResponse
    {
        $users = User::orderBy('id', 'desc')->get();
        return response()->json($users);
    }
}
