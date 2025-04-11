<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->partial('components/header') ?>
    <?= $this->assets->outputCss() ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="<?= $this->security->getToken() ?>">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>

<body>
    <div id="app">
        <?= $this->partial('components/sidebar') ?>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-md-6 order-md-1 order-last">
                            <h3><?= $title ?></h3>
                            <p class="text-subtitle text-muted"><?= $subtitle ?></p>
                        </div>
                    </div>
                </div>
                
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row gap-4 justify-content-md-between align-items-start align-items-md-center mb-3">
                <form method="GET" action="/frontend/material" class="d-flex flex-column flex-sm-row mb-3 w-100 mb-md-0">
                    <input type="text" name="search" class="form-control w-100 me-0 me-sm-2 mb-2 mb-sm-0" placeholder="Search by name" value="<?= $search ?>">
                    <button type="submit" class="btn btn-secondary w-sm-auto">Search</button>
                </form>
                <button type="button" class="btn btn-primary w-30 w-md-auto" id="btnAddModal" data-bs-toggle="modal" data-bs-target="#addUomModal">Add</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>UoM</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($this->length($page->items) == 0) { ?>
                        <tr>
                            <td colspan="6">No data available</td>
                        </tr>
                        <?php } else { ?>
                        <?php $v149528936500298041451iterator = $page->items; $v149528936500298041451incr = 0; $v149528936500298041451loop = new stdClass(); $v149528936500298041451loop->self = &$v149528936500298041451loop; $v149528936500298041451loop->length = count($v149528936500298041451iterator); $v149528936500298041451loop->index = 1; $v149528936500298041451loop->index0 = 1; $v149528936500298041451loop->revindex = $v149528936500298041451loop->length; $v149528936500298041451loop->revindex0 = $v149528936500298041451loop->length - 1; ?><?php foreach ($v149528936500298041451iterator as $material) { ?><?php $v149528936500298041451loop->first = ($v149528936500298041451incr == 0); $v149528936500298041451loop->index = $v149528936500298041451incr + 1; $v149528936500298041451loop->index0 = $v149528936500298041451incr; $v149528936500298041451loop->revindex = $v149528936500298041451loop->length - $v149528936500298041451incr; $v149528936500298041451loop->revindex0 = $v149528936500298041451loop->length - ($v149528936500298041451incr + 1); $v149528936500298041451loop->last = ($v149528936500298041451incr == ($v149528936500298041451loop->length - 1)); ?>
                        <tr>
                            <td><?= $v149528936500298041451loop->index + (($page->current - 1) * $page->limit) ?></td>
                            <td><?= $material->name ?></td>
                            <td><?= $material->stock ?> <?= $material->conversion_uom->uom_end->name ?></td>
                            <td ><span class="format-rupiah" ><?= $material->price ?></span>/<?= $material->conversion_uom->uom_end->name ?></td>
                            <td><?= $material->uom ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn"
                                    data-id="<?= $material->id ?>"
                                    data-name="<?= $material->name ?>"
                                    data-uom="<?= $material->conversion_uom_id ?>"
                                    data-price="<?= $material->price ?>"
                                    data-stock="<?= $material->stock ?>">
                                    Edit
                                </button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $material->id ?>">Delete</button>
                            </td>
                        </tr>
                        <?php $v149528936500298041451incr++; } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <nav aria-label="Pagination">
                <ul class="pagination justify-content-center">
                    <?php if ($page->before) { ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page->before ?>">Previous</a>
                    </li>
                    <?php } ?>
                    <?php $v149528936500298041451iterator = range(1, $page->total_pages); $v149528936500298041451incr = 0; $v149528936500298041451loop = new stdClass(); $v149528936500298041451loop->self = &$v149528936500298041451loop; $v149528936500298041451loop->length = count($v149528936500298041451iterator); $v149528936500298041451loop->index = 1; $v149528936500298041451loop->index0 = 1; $v149528936500298041451loop->revindex = $v149528936500298041451loop->length; $v149528936500298041451loop->revindex0 = $v149528936500298041451loop->length - 1; ?><?php foreach ($v149528936500298041451iterator as $i) { ?><?php $v149528936500298041451loop->first = ($v149528936500298041451incr == 0); $v149528936500298041451loop->index = $v149528936500298041451incr + 1; $v149528936500298041451loop->index0 = $v149528936500298041451incr; $v149528936500298041451loop->revindex = $v149528936500298041451loop->length - $v149528936500298041451incr; $v149528936500298041451loop->revindex0 = $v149528936500298041451loop->length - ($v149528936500298041451incr + 1); $v149528936500298041451loop->last = ($v149528936500298041451incr == ($v149528936500298041451loop->length - 1)); ?>
                    <li class="page-item <?php if ($i == $page->current) { ?>active<?php } ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php $v149528936500298041451incr++; } ?>
                    <?php if ($page->next) { ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page->next ?>">Next</a>
                    </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Modal for Adding/Editing -->
<div class="modal fade" id="addUomModal" tabindex="-1" aria-labelledby="addUomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUomModalLabel">Add Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEditUomForm">
                    <input name="id" type="hidden" id="uomId">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input name="name" type="text" class="form-control" id="materialName" placeholder="Enter name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">UoM</label>
                        <select class="form-select" id="materialUom" name="conversion_uom_id" required>
                            <option value="">Select UoM</option>
                            <?php $v149528936500298041451iterator = $conversionUoms; $v149528936500298041451incr = 0; $v149528936500298041451loop = new stdClass(); $v149528936500298041451loop->self = &$v149528936500298041451loop; $v149528936500298041451loop->length = count($v149528936500298041451iterator); $v149528936500298041451loop->index = 1; $v149528936500298041451loop->index0 = 1; $v149528936500298041451loop->revindex = $v149528936500298041451loop->length; $v149528936500298041451loop->revindex0 = $v149528936500298041451loop->length - 1; ?><?php foreach ($v149528936500298041451iterator as $uom) { ?><?php $v149528936500298041451loop->first = ($v149528936500298041451incr == 0); $v149528936500298041451loop->index = $v149528936500298041451incr + 1; $v149528936500298041451loop->index0 = $v149528936500298041451incr; $v149528936500298041451loop->revindex = $v149528936500298041451loop->length - $v149528936500298041451incr; $v149528936500298041451loop->revindex0 = $v149528936500298041451loop->length - ($v149528936500298041451incr + 1); $v149528936500298041451loop->last = ($v149528936500298041451incr == ($v149528936500298041451loop->length - 1)); ?>
                            <option value="<?= $uom->id ?>"><?= $uom->name ?></option>
                            <?php $v149528936500298041451incr++; } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price (Price/UoM)</label>
                        <input placeholder="Enter Price" name="price" type="number" class="form-control" id="materialPrice" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stock (Large UoM) </label>
                        <input placeholder="Enter stock" name="stock" type="number" class="form-control" id="materialStock" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(number);
    }

    $('.format-rupiah').each(function() {
        $(this).text(formatRupiah($(this).text()));
    });

    $('#btnAddModal').click(function() {
        $('#addEditUomForm')[0].reset();
        $('#uomId').val('');
        $('#addUomModalLabel').text('Add Material');
    });

    $('.edit-btn').click(function() {
        $('#uomId').val($(this).data('id'));
        $('#materialName').val($(this).data('name'));
        $('#materialUom').val($(this).data('uom'));
        $('#materialPrice').val($(this).data('price'));
        $('#materialStock').val($(this).data('stock'));
        $('#addUomModalLabel').text('Edit Material');
        $('#addUomModal').modal('show');
    });

    $('#addEditUomForm').submit(function(event) {
        event.preventDefault();
        let formData = $(this).serialize();
        let id = $('#uomId').val();
        let url = id ? `/frontend/material/update/${id}` : '/frontend/material/save';
        let method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: formData,
            success: function(response) {
                Swal.fire('Success', response.message, 'success').then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON.message, 'error');
            }
        });
    });

    $('.delete-btn').click(function() {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/frontend/material/delete/${id}`,
                    type: 'DELETE',
                    success: function(response) {
                        Swal.fire('Deleted!', response.message, 'success').then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON.message, 'error');
                    }
                });
            }
        });
    });
});
</script>

            </div>
            <?= $this->partial('components/footer') ?>
        </div>
    </div>
</body>
<?= $this->partial('components/scripts') ?>
<?= $this->assets->outputJs() ?>

</html>