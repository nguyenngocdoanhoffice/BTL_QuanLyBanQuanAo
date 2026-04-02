import './bootstrap';

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
const baseHeaders = {
	Accept: 'application/json',
	'X-Requested-With': 'XMLHttpRequest',
};

if (csrfToken) {
	baseHeaders['X-CSRF-TOKEN'] = csrfToken;
}

const currency = new Intl.NumberFormat('vi-VN', {
	style: 'currency',
	currency: 'VND',
	maximumFractionDigits: 0,
});

const apiRequest = async (url, options = {}) => {
	const response = await fetch(url, {
		credentials: 'same-origin',
		...options,
	});

	let payload;
	try {
		payload = await response.json();
	} catch (error) {
		payload = {};
	}

	if (!response.ok) {
		const firstError = payload?.errors ? Object.values(payload.errors).flat()[0] : null;
		const message = firstError || payload?.message || 'Có lỗi xảy ra. Vui lòng thử lại.';
		throw new Error(message);
	}

	return payload;
};

document.addEventListener('DOMContentLoaded', () => {
	const toastRoot = document.createElement('div');
		toastRoot.className = 'pointer-events-none fixed inset-x-0 top-4 z-50 flex flex-col items-end gap-3 px-4';
	document.body.appendChild(toastRoot);
		let toastTimer = null;

	const showToast = (message, variant = 'success') => {
			if (toastTimer) {
				clearTimeout(toastTimer);
				toastTimer = null;
			}
			toastRoot.innerHTML = '';
		const toast = document.createElement('div');
		toast.textContent = message;
		toast.className = `pointer-events-auto rounded-full px-4 py-2 text-sm font-semibold shadow-lg ${
			variant === 'error' ? 'bg-rose-500 text-white' : 'bg-slate-900 text-white'
		}`;
		toastRoot.appendChild(toast);
			toastTimer = setTimeout(() => {
				toast.remove();
				toastTimer = null;
			}, 3000);
	};

	const updateCartTotals = (totals) => {
		if (!totals) return;
		const cartCount = document.getElementById('cart-count');
		if (cartCount) {
			cartCount.textContent = totals.count ?? 0;
		}
		const cartPageCount = document.querySelector('[data-cart-page-count]');
		if (cartPageCount) {
			cartPageCount.textContent = totals.count ?? 0;
		}
		const subtotalEl = document.querySelector('[data-cart-subtotal]');
		const totalEl = document.querySelector('[data-cart-total]');
		if (subtotalEl) subtotalEl.textContent = currency.format(totals.subtotal ?? 0);
		if (totalEl) totalEl.textContent = currency.format((totals.subtotal ?? 0));
	};

	const addToCart = async (payload) => {
		const response = await apiRequest('/cart', {
			method: 'POST',
			headers: {
				...baseHeaders,
				'Content-Type': 'application/json',
			},
			body: JSON.stringify(payload),
		});
		updateCartTotals(response.totals);
		showToast(response.message || 'Đã thêm sản phẩm vào giỏ');
	};

	const updateCartItem = async (key, quantity, size = null) => {
		const response = await apiRequest(`/cart/${encodeURIComponent(key)}`, {
			method: 'PATCH',
			headers: {
				...baseHeaders,
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({ quantity, size }),
		});
		updateCartTotals(response.totals);
		showToast('Đã cập nhật giỏ hàng');
		return response;
	};

	const removeCartItem = async (key) => {
		const response = await apiRequest(`/cart/${encodeURIComponent(key)}`, {
			method: 'DELETE',
			headers: baseHeaders,
		});
		updateCartTotals(response.totals);
		showToast('Đã xóa sản phẩm', 'success');
		return response;
	};

	document.body.addEventListener('click', async (event) => {
		const addBtn = event.target.closest('[data-add-to-cart]');
		if (addBtn) {
			event.preventDefault();
			const productId = Number(addBtn.dataset.productId);
			if (!productId) return;
			try {
				await addToCart({ product_id: productId, quantity: 1, size: addBtn.dataset.size || null });
			} catch (error) {
				showToast(error.message, 'error');
			}
			return;
		}

		const qtyMinus = event.target.closest('[data-qty-minus]');
		if (qtyMinus) {
			event.preventDefault();
			changeQuantity(qtyMinus, -1);
			return;
		}

		const qtyPlus = event.target.closest('[data-qty-plus]');
		if (qtyPlus) {
			event.preventDefault();
			changeQuantity(qtyPlus, 1);
			return;
		}

		const removeBtn = event.target.closest('[data-remove-item]');
		if (removeBtn) {
			event.preventDefault();
			const cartRow = removeBtn.closest('[data-cart-item]');
			if (!cartRow) return;
			const key = cartRow.dataset.key;
			try {
				const response = await removeCartItem(key);
				cartRow.remove();
				if ((response?.totals?.count ?? 0) === 0) {
					window.location.reload();
				}
			} catch (error) {
				showToast(error.message, 'error');
			}
		}
	});

	const changeQuantity = (trigger, delta) => {
		const qtyInput = trigger.closest('div')?.querySelector('[data-cart-qty], [data-qty-input]');
		if (!qtyInput) return;
		const newValue = Math.max(1, Number(qtyInput.value || 1) + delta);
		qtyInput.value = newValue;
		if (qtyInput.hasAttribute('data-cart-qty')) {
			const cartRow = qtyInput.closest('[data-cart-item]');
			if (!cartRow) return;
			const key = cartRow.dataset.key;
			const price = Number(cartRow.dataset.price || 0);
			const subtotalDom = cartRow.querySelector('[data-line-subtotal]');
			if (subtotalDom) subtotalDom.textContent = currency.format(price * newValue);
			updateCartItem(key, newValue).catch((error) => showToast(error.message, 'error'));
		}
	};

	document.body.addEventListener('change', (event) => {
		const qtyInput = event.target.closest('[data-cart-qty]');
		if (!qtyInput) return;
		const cartRow = qtyInput.closest('[data-cart-item]');
		if (!cartRow) return;
		const key = cartRow.dataset.key;
		const price = Number(cartRow.dataset.price || 0);
		const value = Math.max(1, Number(qtyInput.value || 1));
		qtyInput.value = value;
		const subtotalDom = cartRow.querySelector('[data-line-subtotal]');
		if (subtotalDom) subtotalDom.textContent = currency.format(price * value);
		const sizeInput = cartRow.querySelector('[data-cart-size]');
		updateCartItem(key, value, sizeInput?.value || null).catch((error) => showToast(error.message, 'error'));
	});

	document.body.addEventListener('change', (event) => {
		const sizeSelect = event.target.closest('[data-cart-size]');
		if (!sizeSelect) return;
		const cartRow = sizeSelect.closest('[data-cart-item]');
		if (!cartRow) return;
		const qtyInput = cartRow.querySelector('[data-cart-qty]');
		const quantity = Math.max(1, Number(qtyInput?.value || 1));
		const currentKey = cartRow.dataset.key;
		const price = Number(cartRow.dataset.price || 0);
		const subtotalDom = cartRow.querySelector('[data-line-subtotal]');
		if (subtotalDom) subtotalDom.textContent = currency.format(price * quantity);
		updateCartItem(currentKey, quantity, sizeSelect.value || null)
			.then(() => window.location.reload())
			.catch((error) => showToast(error.message, 'error'));
	});

	const cartSearchInput = document.querySelector('[data-cart-search]');
	if (cartSearchInput) {
		const cartItemsContainer = document.querySelector('[data-cart-items]');
		const cartRows = () => Array.from(document.querySelectorAll('[data-cart-item]'));
		const emptySearchState = document.createElement('div');
		emptySearchState.className = 'hidden rounded-3xl border border-dashed border-slate-200 bg-white p-8 text-center text-sm text-slate-500';
		emptySearchState.textContent = 'Không tìm thấy sản phẩm phù hợp trong giỏ hàng.';
		cartItemsContainer?.appendChild(emptySearchState);

		cartSearchInput.addEventListener('input', () => {
			const keyword = cartSearchInput.value.trim().toLowerCase();
			let visibleCount = 0;

			cartRows().forEach((row) => {
				const itemName = (row.dataset.itemName || '').toLowerCase();
				const matched = !keyword || itemName.includes(keyword);
				row.classList.toggle('hidden', !matched);
				if (matched) visibleCount += 1;
			});

			emptySearchState.classList.toggle('hidden', visibleCount > 0);
		});
	}

	const productForm = document.querySelector('[data-product-form]');
	if (productForm) {
		productForm.addEventListener('submit', async (event) => {
			event.preventDefault();
			const formData = new FormData(productForm);
			const payload = Object.fromEntries(formData.entries());
			payload.quantity = Number(payload.quantity) || 1;
			if (payload.size === '') payload.size = null;
			try {
				await addToCart(payload);
			} catch (error) {
				showToast(error.message, 'error');
			}
		});
	}

	document.querySelectorAll('[data-gallery-thumb]').forEach((thumb) => {
		thumb.addEventListener('click', () => {
			const target = document.getElementById('main-product-image');
			if (!target) return;
			target.src = thumb.dataset.image;
		});
	});

	const filterForm = document.querySelector('[data-filter-form]');
	const productsGrid = document.querySelector('[data-products-grid]');
	if (filterForm && productsGrid) {
		filterForm.addEventListener('submit', async (event) => {
			event.preventDefault();
			const params = new URLSearchParams(new FormData(filterForm));
			const url = `${filterForm.getAttribute('action')}?${params.toString()}`;
			productsGrid.classList.add('opacity-50');
			try {
				const response = await apiRequest(url, {
					method: 'GET',
					headers: baseHeaders,
				});
				productsGrid.innerHTML = response.html;
				history.replaceState({}, '', url);
			} catch (error) {
				showToast(error.message, 'error');
			} finally {
				productsGrid.classList.remove('opacity-50');
			}
		});
	}

	const previewModal = document.getElementById('product-preview-modal');
	const previewImage = document.querySelector('[data-preview-image]');
	const previewCategory = document.querySelector('[data-preview-category]');
	const previewTitle = document.querySelector('[data-preview-title]');
	const previewBrand = document.querySelector('[data-preview-brand]');
	const previewPrice = document.querySelector('[data-preview-price]');
	const previewSalePrice = document.querySelector('[data-preview-sale-price]');
	const previewDescription = document.querySelector('[data-preview-description]');
	const previewProductId = document.querySelector('[data-preview-product-id]');
	const previewSizes = document.querySelector('[data-preview-sizes]');
	const previewFreeSize = document.querySelector('[data-preview-freesize]');
	const previewLink = document.querySelector('[data-preview-link]');
	const previewQtyInput = document.querySelector('[data-preview-qty]');
	const previewForm = document.querySelector('[data-product-preview-form]');

	let activePreviewSize = null;

	const closePreviewModal = () => {
		if (!previewModal) return;
		previewModal.classList.add('hidden');
		previewModal.classList.remove('flex');
		document.body.classList.remove('overflow-hidden');
	};

	const openPreviewModal = (payload) => {
		if (!previewModal || !previewImage || !previewCategory || !previewTitle || !previewBrand || !previewPrice || !previewDescription || !previewProductId || !previewSizes || !previewFreeSize || !previewLink || !previewQtyInput) {
			return;
		}

		previewImage.src = payload.image || 'https://placehold.co/900x1100?text=QAO+Fashion';
		previewImage.alt = payload.title || 'QAO Fashion';
		previewCategory.textContent = payload.category || 'Fashion';
		previewTitle.textContent = payload.title || '';
		previewBrand.textContent = payload.brand || '';
		previewPrice.textContent = payload.price || '0 đ';
		previewDescription.textContent = payload.description || '';
		previewProductId.value = payload.id || '';
		previewLink.href = payload.url || '#';
		previewQtyInput.value = 1;
		activePreviewSize = null;
		if (payload.sale_price) {
			previewSalePrice.textContent = payload.sale_price;
			previewSalePrice.classList.remove('hidden');
		} else {
			previewSalePrice.classList.add('hidden');
			previewSalePrice.textContent = '';
		}

		previewSizes.innerHTML = '';
		previewFreeSize.classList.add('hidden');
		previewForm?.querySelectorAll('input[type="hidden"][name="size"]').forEach((input) => input.remove());

		const sizes = Array.isArray(payload.sizes) ? payload.sizes : [];
		if (sizes.length > 0) {
			previewSizes.classList.remove('hidden');
			previewSizes.classList.add('flex');
			previewSizes.classList.add('flex-wrap');
			previewSizes.classList.add('gap-3');

			sizes.forEach((size, index) => {
				const label = document.createElement('label');
				label.className = 'inline-flex cursor-pointer';
				label.innerHTML = `
					<input type="radio" name="size" value="${size}" class="peer sr-only" ${index === 0 ? 'checked' : ''}>
					<span class="min-w-15 rounded-full border border-slate-200 px-4 py-2 text-center text-sm font-medium text-slate-600 transition peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white">${size}</span>
				`;
				previewSizes.appendChild(label);
			});
			activePreviewSize = sizes[0] ?? null;
		} else {
			previewSizes.classList.remove('flex', 'flex-wrap', 'gap-3');
			previewSizes.classList.add('hidden');
			previewFreeSize.classList.remove('hidden');
			if (!previewForm?.querySelector('input[type="hidden"][name="size"]')) {
				const hiddenSizeInput = document.createElement('input');
				hiddenSizeInput.type = 'hidden';
				hiddenSizeInput.name = 'size';
				hiddenSizeInput.value = '';
				previewForm?.appendChild(hiddenSizeInput);
			}
		}

		previewModal.classList.remove('hidden');
		previewModal.classList.add('flex');
		document.body.classList.add('overflow-hidden');
	};

	document.body.addEventListener('click', (event) => {
		const previewBtn = event.target.closest('[data-product-preview-open]');
		if (previewBtn) {
			event.preventDefault();
			try {
				const payload = JSON.parse(previewBtn.dataset.productPreview || '{}');
				openPreviewModal(payload);
			} catch (error) {
				showToast('Không thể mở xem nhanh sản phẩm.', 'error');
			}
			return;
		}

		const previewClose = event.target.closest('[data-product-preview-close]');
		if (previewClose) {
			event.preventDefault();
			closePreviewModal();
			return;
		}

		if (event.target.matches('[data-product-preview-backdrop]')) {
			closePreviewModal();
			return;
		}

		const previewQtyMinus = event.target.closest('[data-preview-qty-minus]');
		if (previewQtyMinus) {
			event.preventDefault();
			if (previewQtyInput) {
				previewQtyInput.value = Math.max(1, Number(previewQtyInput.value || 1) - 1);
			}
			return;
		}

		const previewQtyPlus = event.target.closest('[data-preview-qty-plus]');
		if (previewQtyPlus) {
			event.preventDefault();
			if (previewQtyInput) {
				previewQtyInput.value = Math.max(1, Number(previewQtyInput.value || 1) + 1);
			}
			return;
		}
	});

	document.body.addEventListener('change', (event) => {
		const previewSizeInput = event.target.closest('#product-preview-modal input[type="radio"][name="size"]');
		if (previewSizeInput) {
			activePreviewSize = previewSizeInput.value;
		}
	});

	if (previewForm) {
		previewForm.addEventListener('submit', async (event) => {
			event.preventDefault();
			const formData = new FormData(previewForm);
			const payload = Object.fromEntries(formData.entries());
			payload.quantity = Number(payload.quantity) || 1;
			if (payload.size === '') payload.size = null;
			if (!payload.size && activePreviewSize) payload.size = activePreviewSize;
			try {
				await addToCart(payload);
				closePreviewModal();
			} catch (error) {
				showToast(error.message, 'error');
			}
		});
	}

	const accountMenuRoot = document.querySelector('[data-account-menu-root]');
	const accountMenuToggle = document.querySelector('[data-account-menu-toggle]');
	const accountMenu = document.querySelector('[data-account-menu]');

	if (accountMenuRoot && accountMenuToggle && accountMenu) {
		const closeAccountMenu = () => {
			accountMenu.classList.add('invisible', 'opacity-0');
			accountMenu.classList.remove('visible', 'opacity-100');
			accountMenuToggle.setAttribute('aria-expanded', 'false');
		};

		const openAccountMenu = () => {
			accountMenu.classList.remove('invisible', 'opacity-0');
			accountMenu.classList.add('visible', 'opacity-100');
			accountMenuToggle.setAttribute('aria-expanded', 'true');
		};

		accountMenuToggle.addEventListener('click', (event) => {
			event.stopPropagation();
			const isOpen = accountMenu.classList.contains('visible');
			if (isOpen) {
				closeAccountMenu();
			} else {
				openAccountMenu();
			}
		});

		document.addEventListener('click', (event) => {
			if (!accountMenuRoot.contains(event.target)) {
				closeAccountMenu();
			}
		});

		document.addEventListener('keydown', (event) => {
			if (event.key === 'Escape') {
				closeAccountMenu();
			}
		});
	}
});
