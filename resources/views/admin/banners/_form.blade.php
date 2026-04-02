@php use Illuminate\Support\Facades\Storage; @endphp

@csrf
<div class="space-y-6">
    <div>
        <label class="text-sm font-semibold text-slate-600">Tiêu đề</label>
        <input type="text" name="title" value="{{ old('title', $banner->title) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3" required>
    </div>
    <div>
        <label class="text-sm font-semibold text-slate-600">Phụ đề</label>
        <input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3">
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-slate-600">Liên kết (tùy chọn)</label>
            <input type="text" name="link_url" value="{{ old('link_url', $banner->link_url) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="/products/...">
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600">Sản phẩm liên kết</label>
            <select name="product_id" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3">
                <option value="">Không liên kết</option>
                @foreach ($products as $id => $name)
                    <option value="{{ $id }}" @selected(old('product_id', $banner->product_id) == $id)>{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-slate-600">Thứ tự hiển thị</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3" min="0">
        </div>
        <div class="flex items-center gap-3 pt-6">
            <input type="checkbox" name="is_active" value="1" class="h-5 w-5 rounded border-slate-300" @checked(old('is_active', $banner->is_active))>
            <span class="text-sm font-semibold text-slate-700">Hiển thị trên trang chủ</span>
        </div>
    </div>
    <div>
        <label class="text-sm font-semibold text-slate-600">Ảnh banner</label>
        <input type="file" name="image" class="mt-2 w-full rounded-2xl border border-dashed border-slate-300 px-4 py-3">
        @if ($banner->image_path)
            <p class="mt-2 text-xs text-slate-500">Ảnh hiện tại:</p>
            <div class="mt-2 h-40 w-full overflow-hidden rounded-2xl bg-slate-100">
                <img src="{{ Storage::url($banner->image_path) }}" alt="{{ $banner->title }}" class="h-full w-full object-cover">
            </div>
        @endif
    </div>
</div>
