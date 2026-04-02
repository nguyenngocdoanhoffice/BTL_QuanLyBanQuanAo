<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CartService
{
    private const GUEST_SESSION_KEY = 'cart.items.guest';

    public function all(): Collection
    {
        return collect($this->currentItems());
    }

    public function add(Product $product, int $quantity, ?string $size = null): array
    {
        $key = $this->makeKey($product->id, $size);
        $items = $this->currentItems();

        $items[$key] = [
            'key' => $key,
            'product_id' => $product->id,
            'name' => $product->title,
            'sku' => $product->sku,
            'price' => $product->final_price,
            'quantity' => $quantity + ($items[$key]['quantity'] ?? 0),
            'size' => $size,
            'image' => $product->cover_image ? Storage::url($product->cover_image) : null,
            'slug' => $product->slug,
            'available_sizes' => $this->availableSizesForProduct($product),
        ];

        $items[$key]['subtotal'] = $items[$key]['price'] * $items[$key]['quantity'];

        $this->persist($items);

        return $items[$key];
    }

    public function update(string $key, int $quantity, ?string $size = null): void
    {
        $items = $this->currentItems();

        if (isset($items[$key])) {
            $item = $items[$key];
            $item['quantity'] = max(1, $quantity);

            if ($size !== null && $size !== $item['size']) {
                $newKey = $this->makeKey((int) $item['product_id'], $size ?: null);
                $item['size'] = $size ?: null;
                $item['key'] = $newKey;
                $item['available_sizes'] = $item['available_sizes'] ?? [];

                if (isset($items[$newKey])) {
                    $item['quantity'] += $items[$newKey]['quantity'];
                    unset($items[$newKey]);
                }

                unset($items[$key]);
                $items[$newKey] = $item;
                $items[$newKey]['subtotal'] = $items[$newKey]['price'] * $items[$newKey]['quantity'];
                $this->persist($items);

                return;
            }

            $items[$key]['quantity'] = $item['quantity'];
            $items[$key]['subtotal'] = $items[$key]['price'] * $items[$key]['quantity'];
            $this->persist($items);
        }
    }

    public function remove(string $key): void
    {
        $items = $this->currentItems();
        unset($items[$key]);
        $this->persist($items);
    }

    public function clear(): void
    {
        if ($user = Auth::user()) {
            $this->storeUserItems($user, []);

            return;
        }

        session()->forget(self::GUEST_SESSION_KEY);
    }

    public function mergeGuestCartIntoUser(User $user, array $guestItems): void
    {
        $userItems = $this->normalizeItems($user->cart_items ?? []);

        foreach ($guestItems as $key => $item) {
            if (! isset($userItems[$key])) {
                $userItems[$key] = $item;

                continue;
            }

            $userItems[$key]['quantity'] += $item['quantity'];
            $userItems[$key]['subtotal'] = $userItems[$key]['price'] * $userItems[$key]['quantity'];
        }

        $this->storeUserItems($user, $userItems);
        session()->forget(self::GUEST_SESSION_KEY);
    }

    public function totals(): array
    {
        $items = $this->all();

        return [
            'items' => $items,
            'subtotal' => $items->sum('subtotal'),
            'count' => $items->count(),
        ];
    }

    private function makeKey(int $productId, ?string $size = null): string
    {
        return $productId . ($size ? ':' . $size : '');
    }

    private function currentItems(): array
    {
        if ($user = Auth::user()) {
            return $this->hydrateItems($this->normalizeItems($user->cart_items ?? []));
        }

        return $this->hydrateItems($this->normalizeItems(session(self::GUEST_SESSION_KEY, [])));
    }

    private function persist(array $items): void
    {
        if ($user = Auth::user()) {
            $this->storeUserItems($user, $items);

            return;
        }

        session([self::GUEST_SESSION_KEY => $items]);
    }

    private function storeUserItems(User $user, array $items): void
    {
        $user->forceFill(['cart_items' => $items])->save();
    }

    private function normalizeItems(array $items): array
    {
        $normalized = [];

        foreach ($items as $key => $item) {
            if (is_array($item) && isset($item['key'])) {
                $normalized[$key] = $item;
            }
        }

        return $normalized;
    }

    private function hydrateItems(array $items): array
    {
        foreach ($items as $key => $item) {
            if (! isset($item['available_sizes'])) {
                $items[$key]['available_sizes'] = $this->availableSizesForProduct(Product::find($item['product_id']));
            }
        }

        return $items;
    }

    private function availableSizesForProduct(?Product $product): array
    {
        if (! $product) {
            return [];
        }

        $sizes = $product->inventories()->whereNotNull('size')->orderBy('size')->pluck('size')->filter()->unique()->values()->all();

        if (empty($sizes)) {
            $sizes = array_values(array_filter($product->size_options ?? []));
        }

        return $sizes;
    }
}
