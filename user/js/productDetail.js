document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const productId = params.get('id');

    // Tìm sản phẩm trong mảng allProducts (đã import từ allProductsData.js)
    const product = allProducts.find(p => p.id === productId);

    if (product) {
        // Cập nhật thông tin sản phẩm
        document.getElementById('product-name').innerText = product.name;
        document.getElementById('main-image').src = product.mainImage;
        document.getElementById('price-new').innerText = `${product.priceNew.toLocaleString('vi-VN')}₫`;
        document.getElementById('price-old').innerText = `${product.priceOld.toLocaleString('vi-VN')}₫`;
        document.getElementById('full-description').innerText = product.description;

        // Xử lý Rating (Sao)
        const starsContainer = document.getElementById('stars');
        starsContainer.innerHTML = ''; // Xóa sao hiện có
        for (let i = 0; i < 5; i++) {
            if (i < product.rating) {
                starsContainer.innerHTML += '<i class="fas fa-star"></i>'; // Sao vàng
            } else {
                starsContainer.innerHTML += '<i class="far fa-star"></i>'; // Sao rỗng
            }
        }
        document.getElementById('total-reviews').innerText = product.reviewCount;

        // Xử lý Thumbnail Images
        const thumbnailGallery = document.getElementById('thumbnail-gallery');
        thumbnailGallery.innerHTML = ''; // Xóa thumbnails hiện có
        product.thumbnailImages.forEach((imgPath, index) => {
            const img = document.createElement('img');
            img.src = imgPath;
            img.alt = `Product Thumbnail ${index + 1}`;
            img.classList.add('img-fluid');
            if (index === 0) {
                img.classList.add('active'); // Đặt ảnh đầu tiên làm active
            }
            img.addEventListener('click', () => {
                document.getElementById('main-image').src = imgPath;
                // Loại bỏ class 'active' khỏi tất cả thumbnails
                document.querySelectorAll('.thumbnail-images img').forEach(thumb => {
                    thumb.classList.remove('active');
                });
                // Thêm class 'active' vào thumbnail được click
                img.classList.add('active');
            });
            thumbnailGallery.appendChild(img);
        });

        // Xử lý Color Options
        const colorOptionsContainer = document.getElementById('color-options-container');
        colorOptionsContainer.innerHTML = ''; // Xóa tùy chọn hiện có
        if (product.colors && product.colors.length > 0) {
            document.getElementById('selected-color-name').innerText = product.colors[0].name; // Mặc định màu đầu tiên
            product.colors.forEach((color, index) => {
                const swatch = document.createElement('span');
                swatch.classList.add('color-swatch');
                swatch.style.backgroundColor = color.hex;
                swatch.dataset.colorName = color.name;
                if (index === 0) {
                    swatch.classList.add('active'); // Mặc định màu đầu tiên active
                }
                swatch.addEventListener('click', () => {
                    document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('active'));
                    swatch.classList.add('active');
                    document.getElementById('selected-color-name').innerText = color.name;
                    // Ở đây bạn có thể thêm logic thay đổi ảnh chính nếu có ảnh theo màu
                });
                colorOptionsContainer.appendChild(swatch);
            });
            document.getElementById('color-list').innerText = product.colors.map(c => c.name).join(', ');
        } else {
            document.getElementById('selected-color-name').innerText = 'Không có';
            document.getElementById('color-list').innerText = 'Không có';
        }


        // Xử lý Size Options
        const sizeSelect = document.getElementById('size-select');
        sizeSelect.innerHTML = ''; // Xóa tùy chọn hiện có
        if (product.sizes && product.sizes.length > 0) {
            product.sizes.forEach(size => {
                const option = document.createElement('option');
                option.value = size;
                option.innerText = size;
                sizeSelect.appendChild(option);
            });
            document.getElementById('size-list').innerText = product.sizes.join(', ');
        } else {
            document.getElementById('size-list').innerText = 'Không có';
            const option = document.createElement('option');
            option.value = '';
            option.innerText = 'N/A';
            sizeSelect.appendChild(option);
        }

        // Cập nhật Product Attributes
        document.getElementById('material').innerText = product.material || 'N/A';
        document.getElementById('form').innerText = product.form || 'N/A';


    } else {
        // Xử lý khi không tìm thấy sản phẩm
        document.querySelector('.container').innerHTML = '<div class="alert alert-danger text-center" role="alert">Sản phẩm không tìm thấy.</div>';
    }
});

// Chức năng format tiền tệ
if (!Number.prototype.toLocaleString) {
    Number.prototype.toLocaleString = function(locale, options) {
        options = options || {};
        options.style = 'currency';
        options.currency = 'VND'; 
        return new Intl.NumberFormat(locale, options).format(this);
    };
}