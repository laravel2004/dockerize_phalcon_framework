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
                <form method="GET" action="/frontend/type-activity" class="d-flex flex-column flex-sm-row mb-3 w-100 mb-md-0">
                    <input type="text" name="search" class="form-control w-100 me-0 me-sm-2 mb-2 mb-sm-0" placeholder="Search by name" value="<?= $search ?>">
                    <button type="submit" class="btn btn-secondary w-sm-auto">Search</button>
                </form>
                <button type="button" class="btn btn-primary w-30 w-md-auto" id="btnAddModal" data-bs-toggle="modal" data-bs-target="#addTypeActivityModal">Add</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Cost</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($this->length($page->items) == 0) { ?>
                        <tr>
                            <td colspan="4">No data available</td>
                        </tr>
                        <?php } else { ?>
                        <?php $v161905481775708254381iterator = $page->items; $v161905481775708254381incr = 0; $v161905481775708254381loop = new stdClass(); $v161905481775708254381loop->self = &$v161905481775708254381loop; $v161905481775708254381loop->length = count($v161905481775708254381iterator); $v161905481775708254381loop->index = 1; $v161905481775708254381loop->index0 = 1; $v161905481775708254381loop->revindex = $v161905481775708254381loop->length; $v161905481775708254381loop->revindex0 = $v161905481775708254381loop->length - 1; ?><?php foreach ($v161905481775708254381iterator as $type) { ?><?php $v161905481775708254381loop->first = ($v161905481775708254381incr == 0); $v161905481775708254381loop->index = $v161905481775708254381incr + 1; $v161905481775708254381loop->index0 = $v161905481775708254381incr; $v161905481775708254381loop->revindex = $v161905481775708254381loop->length - $v161905481775708254381incr; $v161905481775708254381loop->revindex0 = $v161905481775708254381loop->length - ($v161905481775708254381incr + 1); $v161905481775708254381loop->last = ($v161905481775708254381incr == ($v161905481775708254381loop->length - 1)); ?>
                        <tr>
                            <td><?= $v161905481775708254381loop->index + (($page->current - 1) * $page->limit) ?></td>
                            <td><?= $type->name_type ?></td>
                            <td class="format-rupiah"><?= $type->cost ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn"
                                    data-id="<?= $type->id ?>"
                                    data-name="<?= $type->name_type ?>"
                                    data-cost="<?= $type->cost ?>">
                                    Edit
                                </button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $type->id ?>">Delete</button>
                            </td>
                        </tr>
                        <?php $v161905481775708254381incr++; } ?>
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
                    <?php $v161905481775708254381iterator = range(1, $page->total_pages); $v161905481775708254381incr = 0; $v161905481775708254381loop = new stdClass(); $v161905481775708254381loop->self = &$v161905481775708254381loop; $v161905481775708254381loop->length = count($v161905481775708254381iterator); $v161905481775708254381loop->index = 1; $v161905481775708254381loop->index0 = 1; $v161905481775708254381loop->revindex = $v161905481775708254381loop->length; $v161905481775708254381loop->revindex0 = $v161905481775708254381loop->length - 1; ?><?php foreach ($v161905481775708254381iterator as $i) { ?><?php $v161905481775708254381loop->first = ($v161905481775708254381incr == 0); $v161905481775708254381loop->index = $v161905481775708254381incr + 1; $v161905481775708254381loop->index0 = $v161905481775708254381incr; $v161905481775708254381loop->revindex = $v161905481775708254381loop->length - $v161905481775708254381incr; $v161905481775708254381loop->revindex0 = $v161905481775708254381loop->length - ($v161905481775708254381incr + 1); $v161905481775708254381loop->last = ($v161905481775708254381incr == ($v161905481775708254381loop->length - 1)); ?>
                    <li class="page-item <?php if ($i == $page->current) { ?>active<?php } ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php $v161905481775708254381incr++; } ?>
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
<div class="modal fade" id="addTypeActivityModal" tabindex="-1" aria-labelledby="addTypeActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTypeActivityModalLabel">Add/Edit Type Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEditTypeActivityForm">
                    <input type="hidden" name="id" id="uomId">
                    <div class="mb-3">
                        <label for="name_type" class="form-label">Name</label>
                        <input placeholder="Enter name type" name="name_type" type="text" class="form-control" id="name_type" required>
                    </div>
                    <div class="mb-3">
                        <label for="cost" class="form-label">Cost</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input placeholder="10.000" name="cost" type="text" class="form-control" id="cost" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#addEditTypeActivityForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: '/frontend/type-activity/save',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        confirmButtonText: 'OK',
                        timer: 1500
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
            });
        })

        $('#btnAddModal').click(function() {
            $('#addEditTypeActivityForm')[0].reset();
            $('#uomId').val('');
            $('#addTypeActivityModalLabel').text('Add Type Activity');
        });

        $('.edit-btn').click(function() {
            $('#uomId').val($(this).data('id'));
            $('#name_type').val($(this).data('name'));
            let costValue = $(this).data('cost');
            costValue = parseFloat(costValue).toFixed(2).replace(/\.00$/, '');
            $('#cost').val(costValue);
            $('#addTypeActivityModalLabel').text('Edit Type Activity');
            $('#addTypeActivityModal').modal('show');
        });

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(number);
        }

        $('.format-rupiah').each(function() {
            $(this).text(formatRupiah($(this).text()));
        });

        $('#cost').on('input', function() {
            var inputVal = $(this).val().replace(/[^\d]/g, '');
            if (inputVal) {
                inputVal = inputVal.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                $(this).val(inputVal);
            }
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
                        url: `/frontend/type-activity/delete/${id}`,
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