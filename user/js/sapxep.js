
    function sortProducts() {
      const sortOption = document.getElementById('sort-options').value;
      const products = document.querySelectorAll('.product');
      const productArray = Array.from(products);

      switch (sortOption) {
        case 'price-asc':
          productArray.sort((a, b) => {
            return parseInt(a.getAttribute('data-price')) - parseInt(b.getAttribute('data-price'));
          });
          break;

        case 'price-desc':
          productArray.sort((a, b) => {
            return parseInt(b.getAttribute('data-price')) - parseInt(a.getAttribute('data-price'));
          });
          break;

        case 'rating':
          productArray.sort((a, b) => {
            return b.getAttribute('data-rating') - a.getAttribute('data-rating');
          });
          break;

        case 'category':
          productArray.sort((a, b) => {
            return a.getAttribute('data-category').localeCompare(b.getAttribute('data-category'));
          });
          break;
      }

      // Clear the existing products and append the sorted ones
      const productList = document.getElementById('product-list');
      productList.innerHTML = '';
      productArray.forEach(product => {
        productList.appendChild(product);
      });
    }
  