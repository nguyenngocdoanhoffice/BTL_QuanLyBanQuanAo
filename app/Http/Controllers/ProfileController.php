<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit(): View
    {
        /** @var User $user */
        $user = Auth::user();

        return view('profile.edit', ['user' => $user]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40'],
            'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:20'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        $user->update($data);

        return back()->with('status', 'Profile updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return back()->with('status', 'Password changed.');
    }

    public function orders(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $orders = $user
            ->orders()
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('profile.orders', compact('orders'));
    }

    public function showOrder(Order $order): View
    {
        abort_unless($order->user_id === Auth::id(), 403);

        $order->load('items.product');

        return view('profile.order-show', compact('order'));
    }
}
