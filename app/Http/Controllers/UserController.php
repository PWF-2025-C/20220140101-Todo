<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        // $users = User::where('id', '!=', 1)->orderBy('name')->paginate(10);
        // return view('user.index', compact('users'));

        $search = request('search');

        if ($search) {
            $users = User::where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%') // Perbaikan di sini
                      ->orWhere('email', 'like', '%' . $search . '%'); // Perbaikan di sini
            })
            ->orderBy('name')
            ->where('id', '!=', 1)
            ->paginate(20)
            ->withQueryString();
        } else {
            $users = User::where('id', '!=', 1)
            ->orderBy('name')
            ->paginate(10);
        }

        return view('user.index', compact('users'));
    }

    public function destroy(User $user)
    {
        $user->delete(); // Menghapus user
        return redirect()->route('user.index')->with('success', 'User  deleted successfully!');
    }

    public function edit(User $user)
    {
        return view('user.edit', compact('user')); // Mengirimkan user ke view edit
    }
}