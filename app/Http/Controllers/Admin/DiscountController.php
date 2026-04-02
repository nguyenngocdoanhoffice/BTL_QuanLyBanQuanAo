<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DiscountController extends Controller
{
    public function index(): View
    {
        $discounts = Discount::query()
            ->whereNull('product_id')
            ->whereNotNull('code')
            ->latest()
            ->paginate(12);

        return view('admin.discounts.index', compact('discounts'));
    }

    public function create(): View
    {
        return view('admin.discounts.create', [
            'discount' => new Discount(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateDiscount($request);

        Discount::create([
            ...$data,
            'product_id' => null,
            'code' => Str::upper(trim($data['code'])),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.discounts.index')->with('status', 'Đã tạo mã giảm giá.');
    }

    public function edit(Discount $discount): View
    {
        abort_if($discount->product_id !== null || $discount->code === null, 404);

        return view('admin.discounts.edit', compact('discount'));
    }

    public function update(Request $request, Discount $discount): RedirectResponse
    {
        abort_if($discount->product_id !== null || $discount->code === null, 404);

        $data = $this->validateDiscount($request, $discount);

        $discount->update([
            ...$data,
            'product_id' => null,
            'code' => Str::upper(trim($data['code'])),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.discounts.index')->with('status', 'Đã cập nhật mã giảm giá.');
    }

    public function destroy(Discount $discount): RedirectResponse
    {
        abort_if($discount->product_id !== null || $discount->code === null, 404);

        $discount->delete();

        return redirect()->route('admin.discounts.index')->with('status', 'Đã xóa mã giảm giá.');
    }

    private function validateDiscount(Request $request, ?Discount $discount = null): array
    {
        $id = $discount?->id;

        $validator = Validator::make($request->all(), [
            'code' => ['required', 'string', 'max:50', Rule::unique('discounts', 'code')->ignore($id)],
            'type' => ['required', Rule::in(['percentage', 'fixed'])],
            'value' => ['required', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->input('type') === 'percentage' && (float) $request->input('value') > 100) {
                $validator->errors()->add('value', 'Giá trị phần trăm tối đa là 100%.');
            }
        });

        return $validator->validate();
    }
}
