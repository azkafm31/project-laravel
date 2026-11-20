<!-- Modal Tambah Product -->
<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="createProductModalLabel">
          <i class="bi bi-plus-circle me-2"></i>Tambah Product
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="mb-3 row">
            <label for="create_name" class="col-sm-3 col-form-label">Product Name <span class="text-danger">*</span></label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="create_name" name="name" required autofocus>
            </div>
          </div>
          <div class="mb-3 row">
            <label for="create_price" class="col-sm-3 col-form-label">Price (Rp) <span class="text-danger">*</span></label>
            <div class="col-sm-9">
              <input type="number" class="form-control" id="create_price" name="price" min="0" required>
            </div>
          </div>
          <div class="mb-3 row">
            <label for="create_stock" class="col-sm-3 col-form-label">Stock <span class="text-danger">*</span></label>
            <div class="col-sm-9">
              <input type="number" class="form-control" id="create_stock" name="stock" min="0" required>
            </div>
          </div>
          <div class="mb-3 row">
            <label for="create_description" class="col-sm-3 col-form-label">Description <span class="text-danger">*</span></label>
            <div class="col-sm-9">
              <textarea class="form-control" id="create_description" name="description" rows="3" required></textarea>
            </div>
          </div>
          <div class="mb-3 row">
            <label for="create_image" class="col-sm-3 col-form-label">Image</label>
            <div class="col-sm-9">
              <input type="file" class="form-control" id="create_image" name="image" accept="image/*">
              <div class="form-text text-muted small">Format: JPG, PNG, WEBP, maks. 2MB</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i>Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
