@extends('templates.layoutApp')

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.6.1/dist/css/tabulator_bootstrap5.min.css" crossorigin="anonymous" />
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h1 class="mb-0 fs-3">Product List</h1>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('products') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Products</li>
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
        <!--begin::Row-->
        <div class="row">
            <div class="col-12">
                {{-- Flash Message Notification --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!--begin::Card-->
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#createProductModal">
                            <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>
                            PRODUCT
                        </button>
                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 16rem">
                                <span class="input-group-text">
                                    <i class="bi bi-search" aria-hidden="true"></i>
                                </span>
                                <input
                                    id="table-filter"
                                    type="search"
                                    class="form-control"
                                    placeholder="Filter rows&hellip;"
                                    aria-label="Filter rows" />
                            </div>
                        </div>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="d-flex gap-2 mb-3">
                            <button id="export-csv" type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-filetype-csv me-1" aria-hidden="true"></i>
                                Export CSV
                            </button>
                            <button id="export-json" type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-filetype-json me-1" aria-hidden="true"></i>
                                Export JSON
                            </button>
                            <button id="print-table" type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-printer me-1" aria-hidden="true"></i>
                                Print
                            </button>
                        </div>
                        <div id="products-table" aria-label="products"></div>
                        <div
                            id="table-pagination"
                            class="d-flex flex-wrap gap-2 justify-content-end mt-2"></div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer text-muted small">Data Products ZieMarket</div>
                    <!-- /.card-footer -->
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

{{-- Form Hapus Product (Method DELETE & CSRF Protection) --}}
<form id="delete-product-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@include('products.modal')
@endsection


@push('script')
<script
    src="https://cdn.jsdelivr.net/npm/tabulator-tables@6.6.1/dist/js/tabulator.min.js"
    crossorigin="anonymous">
</script>
<script>
    // Fungsi konfirmasi dan kirim request hapus produk
    window.deleteProduct = function(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data product ini?')) {
            const form = document.getElementById('delete-product-form');
            form.action = "{{ url('products') }}/" + id;
            form.submit();
        }
    };

    const statusBadge = (cell) => {
        const value = cell.getValue();
        const map = {
            Active: 'success',
            Inactive: 'info',
            Suspended: 'secondary'
        };
        const color = map[value] || 'secondary';
        return `<span class="badge text-bg-${color}">${value}</span>`;
    }

    document.addEventListener('DOMContentLoaded', () => {

        const table = new Tabulator('#products-table', {
            ajaxURL: "{{ route('products.api') }}", // Mengambil data langsung dari Route JSON Laravel,
            layout: "fitColumns",
            filterMode: 'local',
            pagination: 'local',
            paginationElement: document.getElementById('table-pagination'),
            paginationSize: 30,
            paginationSizeSelector: [10, 25, 50, 100],
            movableColumns: true,
            columns: [{
                    title: 'ID',
                    field: 'id',
                    width: 80,
                    headerSort: true
                },
                {
                    title: 'Product Name',
                    field: 'name',
                },
                {
                    title: 'Price',
                    field: 'price',
                    formatter: function(cell) {
                        var val = cell.getValue();
                        return val ? 'Rp ' + Number(val).toLocaleString('id-ID') : '-';
                    }
                },
                {
                    title: 'Stock',
                    field: 'stock',
                    width: 120,
                },
                {
                    title: 'Aksi',
                    field: 'id',
                    formatter: function(cell) {
                        var id = cell.getValue();
                        var editUrl = "{{ url('products') }}" + '/' + id + "/edit";

                        // Tombol Edit dan Hapus
                        return `
                    <div class="d-inline-flex gap-1 justify-content-center">
                        <a href="${editUrl}" class="btn btn-sm btn-success">
                            <i class="bi bi-pencil-square me-1"></i>Edit
                        </a>
                        <button type="button" onclick="deleteProduct(${id})" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash me-1"></i>Hapus
                        </button>
                    </div>`;
                    },
                    headerSort: false,
                    resizable: true,
                    width: 200,
                    hozAlign: 'center',
                },
            ],
        });

        table.on('tableBuilt', () => {
            const grid = document.getElementById('products-table');
            if (grid) {
                grid.removeAttribute('aria-owns');
                const tableHolder = grid.querySelector('.tabulator-tableholder');
                if (tableHolder) {
                    tableHolder.setAttribute('role', 'rowgroup');
                }
                const innerTable = grid.querySelector('.tabulator-table');
                if (innerTable) {
                    innerTable.setAttribute('role', 'presentation');
                }
            }
        });

        table.on('renderComplete', () => {
            document.querySelectorAll('#table-pagination button').forEach((button) => {
                button.classList.add('btn', 'btn-sm', 'btn-outline-secondary');
            });
        });

        document.getElementById('table-filter').addEventListener('input', (e) => {
            const value = e.target.value;
            if (value) {
                table.setFilter(
                    [{
                        field: 'name',
                        type: 'like',
                        value: value
                    }, ]);
            } else {
                table.clearFilter();
            }
        });

        document
            .getElementById('export-csv')
            .addEventListener('click', () => table.download('csv', 'products.csv'));
        document
            .getElementById('export-json')
            .addEventListener('click', () => table.download('json', 'products.json'));
        document
            .getElementById('print-table')
            .addEventListener('click', () => table.print(false, true));
    });
</script>
@endpush
