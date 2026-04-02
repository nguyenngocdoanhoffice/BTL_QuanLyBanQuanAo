<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(private readonly CartService $cart)
    {
    }

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Thông tin đăng nhập không chính xác.',
            ]);
        }

        $guestCart = $request->session()->get('cart.items.guest', []);
        $request->session()->regenerate();

        if ($user = Auth::user()) {
            $this->cart->mergeGuestCartIntoUser($user, $guestCart);
        }

        return $this->redirectBasedOnRole($request->user());
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Bạn đã đăng xuất.');
    }

    private function redirectBasedOnRole(?User $user): RedirectResponse
    {
        if ($user && $user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }
}
