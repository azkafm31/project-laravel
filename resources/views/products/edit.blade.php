@extends('templates.layoutApp')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h1 class="mb-0 fs-3">Edit Product</h1>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">

                {{-- Alert Error Global --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <div class="d-flex align-items-center mb-1">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                            <strong>Terdapat kesalahan pada pengisian form:</strong>
                        </div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!--begin::Card-->
                <div class="card card-primary card-outline shadow-sm mb-4">
                    <div class="card-header">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-pencil-square me-2"></i>Form Edit Data Product #{{ $product->id }}
                        </h3>
                    </div>
                    <!-- /.card-header -->

                    <!--begin::Form-->
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            {{-- Product Name --}}
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">
                                    Product Name <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    id="name"
                                    name="name"
                                    value="{{ old('name', $product->name) }}"
                                    placeholder="Masukkan nama produk"
                                    required
                                    autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Price & Stock --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label fw-semibold">
                                        Price (Rp) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text">Rp</span>
                                        <input
                                            type="number"
                                            class="form-control @error('price') is-invalid @enderror"
                                            id="price"
                                            name="price"
                                            value="{{ old('price', $product->price) }}"
                                            placeholder="Contoh: 1500000"
                                            min="0"
                                            step="1"
                                            required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="stock" class="form-label fw-semibold">
                                        Stock <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="number"
                                        class="form-control @error('stock') is-invalid @enderror"
                                        id="stock"
                                        name="stock"
                                        value="{{ old('stock', $product->stock) }}"
                                        placeholder="Contoh: 10"
                                        min="0"
                                        step="1"
                                        required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="mb-3">
                                <label for="description" class="form-label fw-semibold">
                                    Description <span class="text-danger">*</span>
                                </label>
                                <textarea
                                    class="form-control @error('description') is-invalid @enderror"
                                    id="description"
                                    name="description"
                                    rows="4"
                                    placeholder="Masukkan deskripsi produk"
                                    required>{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Image --}}
                            <div class="mb-3">
                                <label for="image" class="form-label fw-semibold">Product Image</label>
                                @if ($product->image)
                                    <div class="p-2 border rounded bg-light mb-2 d-flex align-items-center gap-3">
                                        @if (file_exists(public_path('uploads/products/' . $product->image)))
                                            <img
                                                src="{{ asset('uploads/products/' . $product->image) }}"
                                                alt="{{ $product->name }}"
                                                class="img-thumbnail"
                                                style="max-height: 80px; max-width: 120px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <div class="small fw-semibold text-secondary">Gambar saat ini:</div>
                                            <code class="text-dark">{{ $product->image }}</code>
                                        </div>
                                    </div>
                                @endif

                                <input
                                    type="file"
                                    class="form-control @error('image') is-invalid @enderror"
                                    id="image"
                                    name="image"
                                    accept="image/*">
                                <div class="form-text text-muted">
                                    Format: JPG, JPEG, PNG, GIF, SVG, WEBP (Maksimal 2MB). Biarkan kosong jika tidak ingin mengubah gambar.
                                </div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <!--begin::Card Footer-->
                        <div class="card-footer d-flex gap-2 align-items-center bg-transparent py-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Update
                            </button>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                        <!--end::Card Footer-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Card-->

            </div>
            <!-- /.col -->
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->
@endsection
