<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserService $service) {}

    public function index(Request $request)
    {
        return redirect()->route('profile.edit');
    }

    public function create()
    {
        return redirect()->route('profile.edit');
    }

    public function store(UserRequest $request)
    {
        return redirect()->route('profile.edit');
    }

    public function show(User $user)
    {
        return redirect()->route('profile.edit');
    }

    public function edit(User $user)
    {
        return redirect()->route('profile.edit');
    }

    public function update(UserRequest $request, User $user)
    {
        return redirect()->route('profile.edit');
    }

    public function destroy(User $user)
    {
        return redirect()->route('profile.edit');
    }
}
