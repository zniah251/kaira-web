// phantrang.js

let currentPage = 1; // Trang hiện tại
const itemsPerPage = 8; // Số sản phẩm mỗi trang

// Hàm hiển thị sản phẩm
function loadProducts(productsData) {
  const startIndex = (currentPage - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;
  const paginatedProducts = productsData.slice(startIndex, endIndex);

  const productList = document.getElementById('product-list');
  productList.innerHTML = '';

  paginatedProducts.forEach(product => {
    const productHTML = `
      <div class="col-md-3 product" data-price="${product.priceNew}" data-rating="${product.rating}" data-category="${product.category}">
        <div class="product-card">
          <img src="${product.image}" alt="">
          <h6>${product.name}</h6>
          <p class="price-old">${product.priceOld.toLocaleString()}đ</p>
          <p class="price-new">${product.priceNew.toLocaleString()}đ</p>
        </div>
      </div>
    `;
    productList.innerHTML += productHTML;
  });

  updatePagination(productsData.length);
}

// Hàm thay đổi trang
function changePage(direction, productsData) {
  currentPage += direction;
  loadProducts(productsData);
}

// Hàm cập nhật phân trang
function updatePagination(totalItems) {
  const prevButton = document.getElementById('prev-page');
  const nextButton = document.getElementById('next-page');
  const totalPages = Math.ceil(totalItems / itemsPerPage);

  if (currentPage === 1) {
    prevButton.disabled = true;
  } else {
    prevButton.disabled = false;
  }

  if (currentPage >= totalPages) {
    nextButton.disabled = true;
  } else {
    nextButton.disabled = false;
  }
}

// Khởi tạo phân trang khi trang được tải
window.onload = function() {
  const productsData = window.productsData;  // Dữ liệu sản phẩm cho trang này
  loadProducts(productsData);
}
