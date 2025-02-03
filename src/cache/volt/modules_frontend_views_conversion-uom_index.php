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
                    <form method="GET" action="/frontend/conversion-uom" class="d-flex flex-column flex-sm-row mb-3 w-100 mb-md-0">
                        <input type="text" name="search" class="form-control w-100 me-0 me-sm-2 mb-2 mb-sm-0" placeholder="Search by name" value="<?= $search ?>">
                        <button type="submit" class="btn btn-secondary w-sm-auto">Search</button>
                    </form>
                    <button type="button" class="btn btn-primary w-30 w-md-auto" id="btnAddModal" data-bs-toggle="modal" data-bs-target="#addUomModal">
                        Add
                    </button>
                </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>UoM Start</th>
                            <th>UoM End</th>
                            <th>Conversion Value</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($this->length($page->items) == 0) { ?>
                        <tr>
                            <td colspan="6">No data available</td>
                        </tr>
                        <?php } else { ?>
                            <?php $v134030822742616319101iterator = $page->items; $v134030822742616319101incr = 0; $v134030822742616319101loop = new stdClass(); $v134030822742616319101loop->self = &$v134030822742616319101loop; $v134030822742616319101loop->length = count($v134030822742616319101iterator); $v134030822742616319101loop->index = 1; $v134030822742616319101loop->index0 = 1; $v134030822742616319101loop->revindex = $v134030822742616319101loop->length; $v134030822742616319101loop->revindex0 = $v134030822742616319101loop->length - 1; ?><?php foreach ($v134030822742616319101iterator as $conversion) { ?><?php $v134030822742616319101loop->first = ($v134030822742616319101incr == 0); $v134030822742616319101loop->index = $v134030822742616319101incr + 1; $v134030822742616319101loop->index0 = $v134030822742616319101incr; $v134030822742616319101loop->revindex = $v134030822742616319101loop->length - $v134030822742616319101incr; $v134030822742616319101loop->revindex0 = $v134030822742616319101loop->length - ($v134030822742616319101incr + 1); $v134030822742616319101loop->last = ($v134030822742616319101incr == ($v134030822742616319101loop->length - 1)); ?>
                            <tr>
                                <td><?= $v134030822742616319101loop->index + (($page->current - 1) * $page->limit) ?></td>
                                <td><?= $conversion->name ?></td>
                                <td><?= $conversion->uom_start->name ?></td>
                                <td><?= $conversion->uom_end->name ?></td>
                                <td>1 <?= $conversion->uom_start->name ?> = <?= $conversion->conversion ?> <?= $conversion->uom_end->name ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn"
                                        data-id="<?= $conversion->id ?>"
                                        data-name="<?= $conversion->name ?>" \
                                        data-master_uom_start="<?= $conversion->master_uom_start ?>"
                                        data-master_uom_end="<?= $conversion->master_uom_end ?>"
                                        data-conversion="<?= $conversion->conversion ?>">
                                    Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $conversion->id ?>">Delete</button>
                                </td>
                            </tr>
                            <?php $v134030822742616319101incr++; } ?>
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
                    <?php $v134030822742616319101iterator = range(1, $page->total_pages); $v134030822742616319101incr = 0; $v134030822742616319101loop = new stdClass(); $v134030822742616319101loop->self = &$v134030822742616319101loop; $v134030822742616319101loop->length = count($v134030822742616319101iterator); $v134030822742616319101loop->index = 1; $v134030822742616319101loop->index0 = 1; $v134030822742616319101loop->revindex = $v134030822742616319101loop->length; $v134030822742616319101loop->revindex0 = $v134030822742616319101loop->length - 1; ?><?php foreach ($v134030822742616319101iterator as $i) { ?><?php $v134030822742616319101loop->first = ($v134030822742616319101incr == 0); $v134030822742616319101loop->index = $v134030822742616319101incr + 1; $v134030822742616319101loop->index0 = $v134030822742616319101incr; $v134030822742616319101loop->revindex = $v134030822742616319101loop->length - $v134030822742616319101incr; $v134030822742616319101loop->revindex0 = $v134030822742616319101loop->length - ($v134030822742616319101incr + 1); $v134030822742616319101loop->last = ($v134030822742616319101incr == ($v134030822742616319101loop->length - 1)); ?>
                    <li class="page-item <?php if ($i == $page->current) { ?>active<?php } ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php $v134030822742616319101incr++; } ?>
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

<!-- Modal for Adding/Editing UoM -->
<div class="modal fade" id="addUomModal" tabindex="-1" aria-labelledby="addUomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUomModalLabel">Add/Edit UoM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEditUomForm">
                    <input type="hidden" id="conversionId" name="id">
                    <div class="mb-3">
                        <label for="conversionName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="conversionName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="conversionUomStart" class="form-label">UoM Start</label>
                        <select class="form-select" id="conversionUomStart" name="master_uom_start" required>
                            <option value="">Select UoM Start</option>
                            <?php $v134030822742616319101iterator = $uoms; $v134030822742616319101incr = 0; $v134030822742616319101loop = new stdClass(); $v134030822742616319101loop->self = &$v134030822742616319101loop; $v134030822742616319101loop->length = count($v134030822742616319101iterator); $v134030822742616319101loop->index = 1; $v134030822742616319101loop->index0 = 1; $v134030822742616319101loop->revindex = $v134030822742616319101loop->length; $v134030822742616319101loop->revindex0 = $v134030822742616319101loop->length - 1; ?><?php foreach ($v134030822742616319101iterator as $uom) { ?><?php $v134030822742616319101loop->first = ($v134030822742616319101incr == 0); $v134030822742616319101loop->index = $v134030822742616319101incr + 1; $v134030822742616319101loop->index0 = $v134030822742616319101incr; $v134030822742616319101loop->revindex = $v134030822742616319101loop->length - $v134030822742616319101incr; $v134030822742616319101loop->revindex0 = $v134030822742616319101loop->length - ($v134030822742616319101incr + 1); $v134030822742616319101loop->last = ($v134030822742616319101incr == ($v134030822742616319101loop->length - 1)); ?>
                            <option value="<?= $uom->id ?>"><?= $uom->name ?></option>
                            <?php $v134030822742616319101incr++; } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="conversionUomEnd" class="form-label
                        ">UoM End</label>
                        <select class="form-select" id="conversionUomEnd" name="master_uom_end" required>
                            <option value="">Select UoM End</option>
                            <?php $v134030822742616319101iterator = $uoms; $v134030822742616319101incr = 0; $v134030822742616319101loop = new stdClass(); $v134030822742616319101loop->self = &$v134030822742616319101loop; $v134030822742616319101loop->length = count($v134030822742616319101iterator); $v134030822742616319101loop->index = 1; $v134030822742616319101loop->index0 = 1; $v134030822742616319101loop->revindex = $v134030822742616319101loop->length; $v134030822742616319101loop->revindex0 = $v134030822742616319101loop->length - 1; ?><?php foreach ($v134030822742616319101iterator as $uom) { ?><?php $v134030822742616319101loop->first = ($v134030822742616319101incr == 0); $v134030822742616319101loop->index = $v134030822742616319101incr + 1; $v134030822742616319101loop->index0 = $v134030822742616319101incr; $v134030822742616319101loop->revindex = $v134030822742616319101loop->length - $v134030822742616319101incr; $v134030822742616319101loop->revindex0 = $v134030822742616319101loop->length - ($v134030822742616319101incr + 1); $v134030822742616319101loop->last = ($v134030822742616319101incr == ($v134030822742616319101loop->length - 1)); ?>
                            <option value="<?= $uom->id ?>"><?= $uom->name ?></option>
                            <?php $v134030822742616319101incr++; } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="conversionValue" class="form-label
                        ">Conversion Value</label>
                        <input type="number" class="form-control" id="conversionValue" name="conversion" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Handle form submission for adding/editing
        $('#addEditUomForm').on('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);
            const url = $('#conversionId').val() ? '/frontend/conversion-uom/save' : '/frontend/conversion-uom/save';

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        confirmButtonText: 'OK'
                    }).then(function() {
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                        confirmButtonText: 'OK'
                    });
                },
                dataType: 'json'
            });
        });

        // Open modal for editing
        $('.edit-btn').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');

            $('#conversionId').val(id);
            $('#conversionName').val(name);
            $('#conversionUomStart').val($(this).data('master_uom_start'));
            $('#conversionUomEnd').val($(this).data('master_uom_end'));
            $('#conversionValue').val($(this).data('conversion'));
            $('#addUomModalLabel').text('Edit Conversion UoM');
            $('#addUomModal').modal('show');
        });

        // Handle delete button
        $('.delete-btn').on('click', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/frontend/conversion-uom/delete/' + id,
                        type: 'POST',
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(function() {
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: xhr.responseJSON.message,
                                confirmButtonText: 'OK'
                            });
                        },
                        dataType: 'json'
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