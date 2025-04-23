<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserDetailResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return UserResource::collection(User::paginate(10));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user)
    {
        return response()->json(
            new UserDetailResource($user)
        );
    }
}
