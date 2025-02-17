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
                <form method="GET" action="/frontend/project" class="d-flex flex-column flex-sm-row mb-3 w-100 mb-md-0">
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
                            <th>Code</th>
                            <th>Wide</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($this->length($page->items) == 0) { ?>
                        <tr>
                            <td colspan="3">No data available</td>
                        </tr>
                        <?php } else { ?>
                            <?php $v101669741761047984571iterator = $page->items; $v101669741761047984571incr = 0; $v101669741761047984571loop = new stdClass(); $v101669741761047984571loop->self = &$v101669741761047984571loop; $v101669741761047984571loop->length = count($v101669741761047984571iterator); $v101669741761047984571loop->index = 1; $v101669741761047984571loop->index0 = 1; $v101669741761047984571loop->revindex = $v101669741761047984571loop->length; $v101669741761047984571loop->revindex0 = $v101669741761047984571loop->length - 1; ?><?php foreach ($v101669741761047984571iterator as $project) { ?><?php $v101669741761047984571loop->first = ($v101669741761047984571incr == 0); $v101669741761047984571loop->index = $v101669741761047984571incr + 1; $v101669741761047984571loop->index0 = $v101669741761047984571incr; $v101669741761047984571loop->revindex = $v101669741761047984571loop->length - $v101669741761047984571incr; $v101669741761047984571loop->revindex0 = $v101669741761047984571loop->length - ($v101669741761047984571incr + 1); $v101669741761047984571loop->last = ($v101669741761047984571incr == ($v101669741761047984571loop->length - 1)); ?>
                            <tr>
                                <td><?= $v101669741761047984571loop->index + (($page->current - 1) * $page->limit) ?></td>
                                <td><?= $project->project ?></td>
                                <td><?= $project->code ?></td>
                                <td><?= $project->wide ?> Hectare</td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="<?= $project->id ?>" data-project="<?= $project->project ?>" data-code="<?= $project->code ?>" >Edit</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $project->id ?>">Delete</button>
                                </td>
                            </tr>
                            <?php $v101669741761047984571incr++; } ?>
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
                    <?php $v101669741761047984571iterator = range(1, $page->total_pages); $v101669741761047984571incr = 0; $v101669741761047984571loop = new stdClass(); $v101669741761047984571loop->self = &$v101669741761047984571loop; $v101669741761047984571loop->length = count($v101669741761047984571iterator); $v101669741761047984571loop->index = 1; $v101669741761047984571loop->index0 = 1; $v101669741761047984571loop->revindex = $v101669741761047984571loop->length; $v101669741761047984571loop->revindex0 = $v101669741761047984571loop->length - 1; ?><?php foreach ($v101669741761047984571iterator as $i) { ?><?php $v101669741761047984571loop->first = ($v101669741761047984571incr == 0); $v101669741761047984571loop->index = $v101669741761047984571incr + 1; $v101669741761047984571loop->index0 = $v101669741761047984571incr; $v101669741761047984571loop->revindex = $v101669741761047984571loop->length - $v101669741761047984571incr; $v101669741761047984571loop->revindex0 = $v101669741761047984571loop->length - ($v101669741761047984571incr + 1); $v101669741761047984571loop->last = ($v101669741761047984571incr == ($v101669741761047984571loop->length - 1)); ?>
                    <li class="page-item <?php if ($i == $page->current) { ?>active<?php } ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php $v101669741761047984571incr++; } ?>
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
                <h5 class="modal-title" id="addUomModalLabel">Add Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEditUomForm">
                    <input type="hidden" id="uomId">
                    <div class="mb-3">
                        <label for="uomName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="uomName" required>
                    </div>
                    <div class="mb-3">
                        <label for="uomCode" class="form-label">Code</label>
                        <input type="text" class="form-control" id="uomCode" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#btnAddModal').on('click', function() {
            $('#addEditUomForm')[0].reset();
            $('#addUomModalLabel').text('Add Project');
        });

        // Handle form submission for adding/editing
        $('#addEditUomForm').on('submit', function(event) {
            event.preventDefault();

            const uomId = $('#uomId').val();
            const uomName = $('#uomName').val();
            const uomCode = $('#uomCode').val();
            const url = uomId ? '/frontend/project/save' : '/frontend/project/save';

            if (uomName) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        id: uomId,
                        project: uomName,
                        code: uomCode
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(function() {
                            $('#addUomModal').modal('hide');
                            $('#addEditUomForm')[0].reset();
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
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Name field is required!',
                    confirmButtonText: 'OK'
                });
            }
        });

        // Open modal for editing
        $('.edit-btn').on('click', function() {
            console.log($(this).data('project'));
            const id = $(this).data('id');
            const name = $(this).data('project');
            const code = $(this).data('code');

            $('#uomId').val(id);
            $('#uomName').val(name);
            $('#uomCode').val(code);
            $('#addUomModalLabel').text('Edit Project');
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
                        url: '/frontend/project/delete/' + id,
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