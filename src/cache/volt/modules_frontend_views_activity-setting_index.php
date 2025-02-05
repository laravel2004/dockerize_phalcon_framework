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
                    <form method="GET" action="/frontend/activity-setting" class="d-flex flex-column flex-sm-row mb-3 w-100 mb-md-0">
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
                            <th>Description</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($this->length($page->items) == 0) { ?>
                        <tr>
                            <td colspan="3">No data available</td>
                        </tr>
                        <?php } else { ?>
                            <?php $v138591492476648313221iterator = $page->items; $v138591492476648313221incr = 0; $v138591492476648313221loop = new stdClass(); $v138591492476648313221loop->self = &$v138591492476648313221loop; $v138591492476648313221loop->length = count($v138591492476648313221iterator); $v138591492476648313221loop->index = 1; $v138591492476648313221loop->index0 = 1; $v138591492476648313221loop->revindex = $v138591492476648313221loop->length; $v138591492476648313221loop->revindex0 = $v138591492476648313221loop->length - 1; ?><?php foreach ($v138591492476648313221iterator as $activitySetting) { ?><?php $v138591492476648313221loop->first = ($v138591492476648313221incr == 0); $v138591492476648313221loop->index = $v138591492476648313221incr + 1; $v138591492476648313221loop->index0 = $v138591492476648313221incr; $v138591492476648313221loop->revindex = $v138591492476648313221loop->length - $v138591492476648313221incr; $v138591492476648313221loop->revindex0 = $v138591492476648313221loop->length - ($v138591492476648313221incr + 1); $v138591492476648313221loop->last = ($v138591492476648313221incr == ($v138591492476648313221loop->length - 1)); ?>
                            <tr>
                                <td><?= $v138591492476648313221loop->index + (($page->current - 1) * $page->limit) ?></td>
                                <td><?= $activitySetting->name ?></td>
                                <td><?= $activitySetting->description ?></td>
                                <td><?= $activitySetting->type ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn"
                                            data-id="<?= $activitySetting->id ?>"
                                            data-name="<?= $activitySetting->name ?>"
                                            data-type="<?= $activitySetting->type ?>"
                                            data-description="<?= $activitySetting->description ?>">Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $activitySetting->id ?>">Delete</button>
                                </td>
                            </tr>
                            <?php $v138591492476648313221incr++; } ?>
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
                    <?php $v138591492476648313221iterator = range(1, $page->total_pages); $v138591492476648313221incr = 0; $v138591492476648313221loop = new stdClass(); $v138591492476648313221loop->self = &$v138591492476648313221loop; $v138591492476648313221loop->length = count($v138591492476648313221iterator); $v138591492476648313221loop->index = 1; $v138591492476648313221loop->index0 = 1; $v138591492476648313221loop->revindex = $v138591492476648313221loop->length; $v138591492476648313221loop->revindex0 = $v138591492476648313221loop->length - 1; ?><?php foreach ($v138591492476648313221iterator as $i) { ?><?php $v138591492476648313221loop->first = ($v138591492476648313221incr == 0); $v138591492476648313221loop->index = $v138591492476648313221incr + 1; $v138591492476648313221loop->index0 = $v138591492476648313221incr; $v138591492476648313221loop->revindex = $v138591492476648313221loop->length - $v138591492476648313221incr; $v138591492476648313221loop->revindex0 = $v138591492476648313221loop->length - ($v138591492476648313221incr + 1); $v138591492476648313221loop->last = ($v138591492476648313221incr == ($v138591492476648313221loop->length - 1)); ?>
                    <li class="page-item <?php if ($i == $page->current) { ?>active<?php } ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php $v138591492476648313221incr++; } ?>
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

<!-- Modal for Adding/Editing Project -->
<div class="modal fade" id="addUomModal" tabindex="-1" aria-labelledby="addUomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUomModalLabel">Add Activity Setting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEditUomForm">
                    <input type="hidden" id="uomId">

                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="uomName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="uomName" required>
                    </div>

                    <!-- Type Field -->
                    <div class="mb-3">
                        <label for="uomType" class="form-label">Type</label>
                        <select class="form-select" id="uomType" required>
                            <option value="">Select Type</option>
                            <option value="Borongan">Borongan</option>
                            <option value="Harian">Harian</option>
                            <option value="Jam">Jam</option>
                            <option value="Vendor">Vendor</option>
                        </select>
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label for="uomDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="uomDescription" rows="3" required></textarea>
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
            $('#addUomModalLabel').text('Add Activity Setting');
        });

        // Handle form submission for adding/editing
        $('#addEditUomForm').on('submit', function(event) {
        console.log("test")
            event.preventDefault();

            const uomId = $('#uomId').val();
            const uomName = $('#uomName').val();
            const uomType = $('#uomType').val();
            const uomDescription = $('#uomDescription').val();
            const url = uomId ? '/frontend/activity-setting/save' : '/frontend/activity-setting/save';

            if (uomName) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        id: uomId,
                        name : uomName,
                        type: uomType,
                        description : uomDescription
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
            const id = $(this).data('id');
            const name = $(this).data('name');
            const type = $(this).data('type');
            const description = $(this).data('description');

            $('#uomId').val(id);
            $('#uomName').val(name);
            $('#uomType').val(type);
            $('#uomDescription').val(description);

            $('#addUomModalLabel').text('Edit Activity Setting');
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
                        url: '/frontend/activity-setting/delete/' + id,
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