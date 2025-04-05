<style>
  #searchResults img {
  max-width: 50px; /* Giới hạn chiều rộng tối đa */
  max-height: 50px; /* Giới hạn chiều cao tối đa */
  object-fit: cover; /* Đảm bảo ảnh không bị méo */
  margin-right: 10px; /* Khoảng cách giữa ảnh và nội dung */
  border-radius: 5px; /* Tùy chọn: bo góc ảnh */
}
</style>

<!-- Modal tìm kiếm -->
<div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="searchModalLabel">Tìm kiếm sản phẩm</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="text" id="searchInput" class="form-control" placeholder="Nhập tên sản phẩm...">
        <ul id="searchResults" class="list-group mt-3"></ul>
      </div>
    </div>
  </div>
</div>
<script>
document.getElementById('searchInput').addEventListener('input', function () {
    const query = this.value;

    if (query.length > 2) { // Chỉ tìm kiếm khi người dùng nhập từ 3 ký tự trở lên
        fetch(`/api/get-products?query=${query}`)
            .then(response => response.json())
            .then(data => {
                const resultsContainer = document.getElementById('searchResults');
                resultsContainer.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(product => {
                        const listItem = document.createElement('li');
                        listItem.classList.add('list-group-item', 'search-result-item');
                        listItem.innerHTML = `
                            <img src="${product.image_url ? product.image_url : '/default-image.jpg'}" alt="${product.name}">
                            <div>
                                <a href="/shop/product/${product.id}">
                                    ${product.name}
                                </a>
                                <p class="text-muted">Giá: ${product.price.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}</p>
                            </div>
                        `;
                        resultsContainer.appendChild(listItem);
                    });
                } else {
                    resultsContainer.innerHTML = '<li class="list-group-item">Không tìm thấy sản phẩm</li>';
                }
            })
            .catch(error => console.error('Error fetching search results:', error));
    } else {
        document.getElementById('searchResults').innerHTML = '';
    }
});
</script>