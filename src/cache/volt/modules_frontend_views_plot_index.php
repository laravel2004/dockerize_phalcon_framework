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
                    <form method="GET" action="/frontend/plot" class="d-flex flex-column flex-sm-row mb-3 w-100 mb-md-0">
                        <input type="text" name="search" class="form-control w-100 me-0 me-sm-2 mb-2 mb-sm-0" placeholder="Search by code" value="<?= $search ?>">
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
                            <th>Code</th>
                            <th>Project</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($this->length($page->items) == 0) { ?>
                        <tr>
                            <td colspan="4">No data available</td>
                        </tr>
                        <?php } else { ?>
                            <?php $v121851850024964852491iterator = $page->items; $v121851850024964852491incr = 0; $v121851850024964852491loop = new stdClass(); $v121851850024964852491loop->self = &$v121851850024964852491loop; $v121851850024964852491loop->length = count($v121851850024964852491iterator); $v121851850024964852491loop->index = 1; $v121851850024964852491loop->index0 = 1; $v121851850024964852491loop->revindex = $v121851850024964852491loop->length; $v121851850024964852491loop->revindex0 = $v121851850024964852491loop->length - 1; ?><?php foreach ($v121851850024964852491iterator as $plot) { ?><?php $v121851850024964852491loop->first = ($v121851850024964852491incr == 0); $v121851850024964852491loop->index = $v121851850024964852491incr + 1; $v121851850024964852491loop->index0 = $v121851850024964852491incr; $v121851850024964852491loop->revindex = $v121851850024964852491loop->length - $v121851850024964852491incr; $v121851850024964852491loop->revindex0 = $v121851850024964852491loop->length - ($v121851850024964852491incr + 1); $v121851850024964852491loop->last = ($v121851850024964852491incr == ($v121851850024964852491loop->length - 1)); ?>
                            <tr>
                                <td><?= $v121851850024964852491loop->index + (($page->current - 1) * $page->limit) ?></td>
                                <td><?= $plot->code ?></td>
                                <td><?= $plot->project->project ?> - <?= $plot->project->code ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn"
                                            data-id="<?= $plot->id ?>"
                                            data-code="<?= $plot->code ?>"
                                            data-projectCode="<?= $plot->project->code ?>"
                                            data-projectId="<?= $plot->project->id ?>">Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $plot->id ?>">Delete</button>
                                </td>
                            </tr>
                            <?php $v121851850024964852491incr++; } ?>
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
                    <?php $v121851850024964852491iterator = range(1, $page->total_pages); $v121851850024964852491incr = 0; $v121851850024964852491loop = new stdClass(); $v121851850024964852491loop->self = &$v121851850024964852491loop; $v121851850024964852491loop->length = count($v121851850024964852491iterator); $v121851850024964852491loop->index = 1; $v121851850024964852491loop->index0 = 1; $v121851850024964852491loop->revindex = $v121851850024964852491loop->length; $v121851850024964852491loop->revindex0 = $v121851850024964852491loop->length - 1; ?><?php foreach ($v121851850024964852491iterator as $i) { ?><?php $v121851850024964852491loop->first = ($v121851850024964852491incr == 0); $v121851850024964852491loop->index = $v121851850024964852491incr + 1; $v121851850024964852491loop->index0 = $v121851850024964852491incr; $v121851850024964852491loop->revindex = $v121851850024964852491loop->length - $v121851850024964852491incr; $v121851850024964852491loop->revindex0 = $v121851850024964852491loop->length - ($v121851850024964852491incr + 1); $v121851850024964852491loop->last = ($v121851850024964852491incr == ($v121851850024964852491loop->length - 1)); ?>
                    <li class="page-item <?php if ($i == $page->current) { ?>active<?php } ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php $v121851850024964852491incr++; } ?>
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

<!-- Modal for Adding/Editing Plot -->
<div class="modal fade" id="addUomModal" tabindex="-1" aria-labelledby="addUomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUomModalLabel">Add Plot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEditUomForm">
                    <input type="hidden" id="plotId">

                    <!-- Project Field -->
                    <div class="mb-3">
                        <label for="projectId" class="form-label">Project</label>
                        <select class="form-select" id="projectId" name="project_id" required>
                            <option value="">Select Project</option>
                            <?php $v121851850024964852491iterator = $projects; $v121851850024964852491incr = 0; $v121851850024964852491loop = new stdClass(); $v121851850024964852491loop->self = &$v121851850024964852491loop; $v121851850024964852491loop->length = count($v121851850024964852491iterator); $v121851850024964852491loop->index = 1; $v121851850024964852491loop->index0 = 1; $v121851850024964852491loop->revindex = $v121851850024964852491loop->length; $v121851850024964852491loop->revindex0 = $v121851850024964852491loop->length - 1; ?><?php foreach ($v121851850024964852491iterator as $project) { ?><?php $v121851850024964852491loop->first = ($v121851850024964852491incr == 0); $v121851850024964852491loop->index = $v121851850024964852491incr + 1; $v121851850024964852491loop->index0 = $v121851850024964852491incr; $v121851850024964852491loop->revindex = $v121851850024964852491loop->length - $v121851850024964852491incr; $v121851850024964852491loop->revindex0 = $v121851850024964852491loop->length - ($v121851850024964852491incr + 1); $v121851850024964852491loop->last = ($v121851850024964852491incr == ($v121851850024964852491loop->length - 1)); ?>
                                <option value="<?= $project->id ?>"
                                    <?php if ($project->id == $plot->project->id) { ?> selected <?php } ?>
                                >
                                    <?= $project->project ?>
                                </option>
                            <?php $v121851850024964852491incr++; } ?>
                        </select>
                    </div>

                    <!-- Code Field -->
                    <div class="mb-3">
                        <label for="plotCode" class="form-label">Code</label>
                        <input type="text" class="form-control" id="plotCode" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // Open modal for adding a new Plot
        $('#btnAddModal').on('click', function() {
            $('#addEditUomForm')[0].reset();
            $('#plotId').val('');  // Clear hidden ID field
            $('#addUomModalLabel').text('Add Plot');
        });

        // Open modal for editing an existing Plot
        $('.edit-btn').on('click', function() {
            const id = $(this).data('id');
            const code = $(this).data('code');
            const projectCode = $(this).data('projectCode');
            const projectId = $(this).data('projectId');

            $('#plotId').val(id);
            $('#plotCode').val(code);
            $('#projectId').val(projectId);

            $('#addUomModalLabel').text('Edit Plot');
            $('#addUomModal').modal('show');
        });

        // Handle form submission for adding/editing Plot
        $('#addEditUomForm').on('submit', function(event) {
            event.preventDefault();

            const plotId = $('#plotId').val();
            const plotCode = $('#plotCode').val();
            const projectId = $('#projectId').val();
            const url = plotId ? '/frontend/plot/save' : '/frontend/plot/save';  // Both add and edit go to the same URL

            if (plotCode && projectId) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        id: plotId,
                        code: plotCode,
                        project_id: projectId
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
                    text: 'Fields are required!',
                    confirmButtonText: 'OK'
                });
            }
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
                        url: '/frontend/plot/delete/' + id,
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