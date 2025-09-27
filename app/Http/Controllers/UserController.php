<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * List User + Search + Pagination
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = User::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->paginate(10);

        return UserResource::collection($users);
    }

    /**
     * Detail User
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    /**
     * Edit Profile (name, email, password optional)
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        
        $data = $request->validated();

        // Jika ada password, hash dulu
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return new UserResource($user);
    }

    /**
     * Upload / Update Avatar
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = $request->user();

        // hapus avatar lama jika ada
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // simpan avatar baru
        $path = $request->file('avatar')->store('avatars', 'public');

        $user->update([
            'avatar' => $path,
        ]);

        return new UserResource($user);
    }
}
