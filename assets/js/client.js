/**
 * Tương tác giao diện phía Client (DTD STORE)
 * File: public/assets/js/client.js
 */

document.addEventListener('DOMContentLoaded', function () {
    initGalleryThumbnails();
    initVariantSelector();
    initQuantityInputs();
});

/**
 * Chuyển ảnh chính khi người dùng bấm vào ảnh thu nhỏ ở trang chi tiết
 */
function initGalleryThumbnails() {
    const mainImg = document.getElementById('mainProductImage');
    const thumbButtons = document.querySelectorAll('.dtd-thumb-btn');
    if (!mainImg || thumbButtons.length === 0) {
        return;
    }

    thumbButtons.forEach((btn) => {
        btn.addEventListener('click', function () {
            const newSrc = this.getAttribute('data-image');
            if (newSrc) {
                mainImg.src = newSrc;
            }
            thumbButtons.forEach((item) => item.classList.remove('active'));
            this.classList.add('active');
        });
    });
}

/**
 * Định dạng tiền VNĐ
 */
function formatVnd(amount) {
    return new Intl.NumberFormat('vi-VN').format(Number(amount)) + '₫';
}

/**
 * Xử lý chọn biến thể RAM - Dung lượng - Màu sắc theo đúng tổ hợp tồn tại trong DB
 */
function initVariantSelector() {
    const container = document.getElementById('variantSelectorContainer');
    if (!container) {
        return;
    }

    let variants = [];
    try {
        variants = JSON.parse(container.getAttribute('data-variants') || '[]');
    } catch (e) {
        variants = [];
    }

    if (variants.length === 0) {
        return;
    }

    const ramButtons = container.querySelectorAll('.variant-ram-btn');
    const storageButtons = container.querySelectorAll('.variant-storage-btn');
    const colorButtons = container.querySelectorAll('.variant-color-btn');

    const variantIdInput = document.getElementById('selectedVariantId');
    const addToCartBtn = document.getElementById('addToCartBtn');
    const priceDisplay = document.getElementById('variantPriceNow');
    const oldPriceDisplay = document.getElementById('variantPriceOld');
    const discountDisplay = document.getElementById('variantDiscountBadge');
    const selectedLabel = document.getElementById('selectedVariantSummary');

    // Khởi tạo theo biến thể đầu tiên hợp lệ
    let selectedRam = variants[0].ram;
    let selectedStorage = variants[0].storage;
    let selectedColor = variants[0].color;

    function findMatchingVariant(ram, storage, color) {
        return variants.find(
            (v) => v.ram === ram && v.storage === storage && v.color === color
        );
    }

    function updateUI() {
        // 1. Lọc các dung lượng tồn tại với RAM đang chọn
        const availableStorages = [...new Set(
            variants.filter((v) => v.ram === selectedRam).map((v) => v.storage)
        )];
        if (!availableStorages.includes(selectedStorage)) {
            selectedStorage = availableStorages[0] || '';
        }

        // 2. Lọc các màu sắc tồn tại với cặp (RAM, Dung lượng) đang chọn
        const availableColors = [...new Set(
            variants
                .filter((v) => v.ram === selectedRam && v.storage === selectedStorage)
                .map((v) => v.color)
        )];
        if (!availableColors.includes(selectedColor)) {
            selectedColor = availableColors[0] || '';
        }

        // 3. Cập nhật trạng thái nút RAM
        ramButtons.forEach((btn) => {
            const val = btn.getAttribute('data-value');
            btn.classList.toggle('active', val === selectedRam);
        });

        // 4. Cập nhật trạng thái nút Dung lượng (disable nếu không tồn tại với RAM hiện tại)
        storageButtons.forEach((btn) => {
            const val = btn.getAttribute('data-value');
            const exists = availableStorages.includes(val);
            btn.disabled = !exists;
            btn.classList.toggle('active', exists && val === selectedStorage);
        });

        // 5. Cập nhật trạng thái nút Màu sắc (disable nếu không tồn tại với RAM + Dung lượng hiện tại)
        colorButtons.forEach((btn) => {
            const val = btn.getAttribute('data-value');
            const exists = availableColors.includes(val);
            btn.disabled = !exists;
            btn.classList.toggle('active', exists && val === selectedColor);
        });

        // 6. Xác định chính xác biến thể đang chọn và cập nhật giá + variant_id
        const matched = findMatchingVariant(selectedRam, selectedStorage, selectedColor);
        if (matched) {
            variantIdInput.value = matched.id;
            if (addToCartBtn) {
                addToCartBtn.disabled = false;
            }

            const price = Number(matched.price);
            const salePrice = matched.sale_price !== null ? Number(matched.sale_price) : null;
            const hasSale = salePrice !== null && salePrice > 0 && salePrice < price;

            if (priceDisplay) {
                priceDisplay.textContent = formatVnd(hasSale ? salePrice : price);
            }
            if (oldPriceDisplay) {
                if (hasSale) {
                    oldPriceDisplay.textContent = formatVnd(price);
                    oldPriceDisplay.classList.remove('d-none');
                } else {
                    oldPriceDisplay.classList.add('d-none');
                }
            }
            if (discountDisplay) {
                if (hasSale) {
                    const percent = Math.round(((price - salePrice) / price) * 100);
                    discountDisplay.textContent = '-' + percent + '%';
                    discountDisplay.classList.remove('d-none');
                } else {
                    discountDisplay.classList.add('d-none');
                }
            }
            if (selectedLabel) {
                selectedLabel.textContent =
                    'Đã chọn: ' + matched.ram + ' / ' + matched.storage + ' / ' + matched.color;
            }
        } else {
            variantIdInput.value = '';
            if (addToCartBtn) {
                addToCartBtn.disabled = true;
            }
        }
    }

    ramButtons.forEach((btn) => {
        btn.addEventListener('click', function () {
            selectedRam = this.getAttribute('data-value');
            updateUI();
        });
    });

    storageButtons.forEach((btn) => {
        btn.addEventListener('click', function () {
            if (this.disabled) return;
            selectedStorage = this.getAttribute('data-value');
            updateUI();
        });
    });

    colorButtons.forEach((btn) => {
        btn.addEventListener('click', function () {
            if (this.disabled) return;
            selectedColor = this.getAttribute('data-value');
            updateUI();
        });
    });

    updateUI();
}

/**
 * Nút tăng giảm số lượng ở trang chi tiết & giỏ hàng
 */
function initQuantityInputs() {
    document.querySelectorAll('.qty-control').forEach((group) => {
        const input = group.querySelector('input[type="number"]');
        const minusBtn = group.querySelector('.qty-minus');
        const plusBtn = group.querySelector('.qty-plus');
        if (!input || !minusBtn || !plusBtn) return;

        minusBtn.addEventListener('click', () => {
            const current = parseInt(input.value, 10) || 1;
            if (current > 1) {
                input.value = current - 1;
            }
        });

        plusBtn.addEventListener('click', () => {
            const current = parseInt(input.value, 10) || 1;
            if (current < 99) {
                input.value = current + 1;
            }
        });
    });
}
