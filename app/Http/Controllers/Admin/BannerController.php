<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(): View
    {
        $banners = Banner::orderBy('sort_order')->get();

        return view('admin.banners.index', compact('banners'));
    }

    public function create(): View
    {
        return view('admin.banners.create', [
            'banner' => new Banner(),
            'products' => $this->productOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('banners', 'public');
        }

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('status', 'Đã tạo banner mới.');
    }

    public function edit(Banner $banner): View
    {
        return view('admin.banners.edit', [
            'banner' => $banner,
            'products' => $this->productOptions(),
        ]);
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $data = $this->validatedData($request, $banner->id);

        if ($request->hasFile('image')) {
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }

            $data['image_path'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('status', 'Đã cập nhật banner.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        if ($banner->image_path) {
            Storage::disk('public')->delete($banner->image_path);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')->with('status', 'Đã xóa banner.');
    }

    protected function validatedData(Request $request, ?int $bannerId = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'product_id' => ['nullable', Rule::exists('products', 'id')],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'image' => [
                $bannerId ? 'nullable' : 'required',
                'image',
                'max:5120',
                'dimensions:min_width=1600,min_height=900',
            ],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');
        unset($data['image']);

        return $data;
    }

    protected function productOptions()
    {
        return Product::orderBy('title')->pluck('title', 'id');
    }
}
