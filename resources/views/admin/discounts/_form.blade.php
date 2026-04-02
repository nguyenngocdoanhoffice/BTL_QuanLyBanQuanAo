@php
    /** @var \App\Models\Discount $discount */
    $isEdit = $discount->exists;
@endphp

<div class="grid gap-6 lg:grid-cols-2">
    <div class="space-y-5 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div>
            <label class="text-sm font-medium text-slate-600">Mã giảm giá</label>
            <input type="text" name="code" value="{{ old('code', $discount->code) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:border-slate-900 focus:ring-slate-900" placeholder="DOCHA10" required>
            @error('code')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="text-sm font-medium text-slate-600">Loại giảm</label>
                <select name="type" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:border-slate-900 focus:ring-slate-900" required>
                    <option value="percentage" @selected(old('type', $discount->type ?: 'percentage') === 'percentage')>Phần trăm (%)</option>
                    <option value="fixed" @selected(old('type', $discount->type) === 'fixed')>Số tiền (đ)</option>
                </select>
                @error('type')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">Giá trị</label>
                <input type="number" name="value" value="{{ old('value', $discount->value) }}" step="0.01" min="0" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:border-slate-900 focus:ring-slate-900" required>
                @error('value')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="text-sm font-medium text-slate-600">Giới hạn số lượt dùng</label>
            <input type="number" name="usage_limit" value="{{ old('usage_limit', $discount->usage_limit) }}" min="1" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:border-slate-900 focus:ring-slate-900" placeholder="Bỏ trống nếu không giới hạn">
            @error('usage_limit')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            @if ($isEdit)
                <p class="mt-2 text-sm text-slate-500">Đã dùng: <span class="font-semibold text-slate-900">{{ (int) $discount->used }}</span></p>
            @endif
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="text-sm font-medium text-slate-600">Bắt đầu</label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $discount->starts_at?->format('Y-m-d\\TH:i')) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:border-slate-900 focus:ring-slate-900">
                @error('starts_at')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">Kết thúc</label>
                <input type="datetime-local" name="ends_at" value="{{ old('ends_at', $discount->ends_at?->format('Y-m-d\\TH:i')) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:border-slate-900 focus:ring-slate-900">
                @error('ends_at')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <label class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 px-4 py-3">
            <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-slate-900" {{ old('is_active', $discount->is_active ?? true) ? 'checked' : '' }}>
            <span class="text-sm font-medium text-slate-700">Bật mã giảm giá</span>
        </label>
    </div>

    <div class="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">Gợi ý</h2>
        <ul class="list-disc space-y-2 pl-5 text-sm text-slate-600">
            <li>Mã nên viết hoa, không dấu và không khoảng trắng.</li>
            <li>Giảm phần trăm sẽ tính trên tổng tạm tính (chưa gồm phí vận chuyển).</li>
            <li>Giảm số tiền sẽ không vượt quá tổng tạm tính.</li>
        </ul>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white">
        {{ $isEdit ? 'Lưu thay đổi' : 'Tạo mã' }}
    </button>
    <a href="{{ route('admin.discounts.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-700 hover:border-slate-900">
        Hủy
    </a>
</div>
