@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-10">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Tài khoản</p>
            <h1 class="text-4xl font-semibold">Thông tin cá nhân</h1>
        </div>
        <div class="grid gap-10 lg:grid-cols-2">
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4 rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                @csrf
                @method('PUT')
                <h2 class="text-xl font-semibold">Cập nhật thông tin</h2>
                <div>
                    <label class="text-sm font-medium text-slate-600">Họ tên</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-2 w-full rounded-2xl border-slate-200 focus:border-slate-900 focus:ring-slate-900" required>
                    @error('name')<p class="text-sm text-rose-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="mt-2 w-full rounded-2xl border-slate-200 focus:border-slate-900 focus:ring-slate-900">
                    @error('phone')<p class="text-sm text-rose-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600">Giới tính</label>
                    <select name="gender" class="mt-2 w-full rounded-2xl border-slate-200 focus:border-slate-900 focus:ring-slate-900">
                        <option value="">Chọn</option>
                        <option value="male" @selected(old('gender', $user->gender) === 'male')>Nam</option>
                        <option value="female" @selected(old('gender', $user->gender) === 'female')>Nữ</option>
                        <option value="other" @selected(old('gender', $user->gender) === 'other')>Khác</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600">Địa chỉ</label>
                    <textarea name="address" rows="3" class="mt-2 w-full rounded-2xl border-slate-200 focus:border-slate-900 focus:ring-slate-900">{{ old('address', $user->address) }}</textarea>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-slate-600">Thành phố</label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}" class="mt-2 w-full rounded-2xl border-slate-200 focus:border-slate-900 focus:ring-slate-900">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">Mã bưu điện</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" class="mt-2 w-full rounded-2xl border-slate-200 focus:border-slate-900 focus:ring-slate-900">
                    </div>
                </div>
                <button type="submit" class="w-full rounded-full bg-slate-900 px-6 py-3 text-base font-semibold text-white">Lưu thay đổi</button>
            </form>

            <form method="POST" action="{{ route('profile.password') }}" class="space-y-4 rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                @csrf
                @method('PUT')
                <h2 class="text-xl font-semibold">Đổi mật khẩu</h2>
                <div>
                    <label class="text-sm font-medium text-slate-600">Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" class="mt-2 w-full rounded-2xl border-slate-200 focus:border-slate-900 focus:ring-slate-900" required>
                    @error('current_password')<p class="text-sm text-rose-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600">Mật khẩu mới</label>
                    <input type="password" name="password" class="mt-2 w-full rounded-2xl border-slate-200 focus:border-slate-900 focus:ring-slate-900" required>
                    @error('password')<p class="text-sm text-rose-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600">Nhập lại mật khẩu</label>
                    <input type="password" name="password_confirmation" class="mt-2 w-full rounded-2xl border-slate-200 focus:border-slate-900 focus:ring-slate-900" required>
                </div>
                <button type="submit" class="w-full rounded-full border border-slate-900 px-6 py-3 text-base font-semibold text-slate-900">Đổi mật khẩu</button>
            </form>
        </div>
    </div>
@endsection
