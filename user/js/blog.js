fetch('get-blogs.php')
  .then(res => res.json())
  .then(data => {
    const container = document.getElementById('blog-container');
    if (!container) return;

    data.forEach(post => {
      const item = document.createElement('div');
      item.className = 'post-item';
      item.innerHTML = `
        <div class="post-image">
          <img src="../admin/assets/images/${post.image}" alt="">
        </div>
        <div class="post-title"><a href="#">${post.title}</a></div>
        <div class="post-meta">${new Date(post.created_at).toLocaleDateString()}</div>
        <div class="post-content"><p>${post.content.substring(0, 100)}...</p></div>
      `;
      container.appendChild(item);
    });
  });
