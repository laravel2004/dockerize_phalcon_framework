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
                
    <style>
        .image-upload-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100px;
            height: 100px;
            border: 2px dashed #ccc;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            color: #666;
            text-align: center;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        .image-upload-box:hover {
            background-color: #e9ecef;
        }

        .image-upload-box i {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .preview-box {
            position: relative;
            width: 100px;
            height: 100px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 2px 5px rgba(0,0,0,0.2);
        }

        .preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

    </style>
    <style>
        .table-responsive {
            -webkit-overflow-scrolling: touch;
            overflow-x: auto;
            white-space: nowrap;
        }
    </style>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column flex-md-row gap-4 justify-content-md-between align-items-start align-items-md-center mb-3">
                    <form method="GET" action="/frontend/supporting-material" class="d-flex flex-column flex-sm-row mb-3 w-100 mb-md-0">
                        <input type="text" name="search" class="form-control w-100 me-0 me-sm-2 mb-2 mb-sm-0" placeholder="Search by item needed , plot and material" value="<?= $search ?>">
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
                                <th>Plot Code</th>
                                <th>Name Material</th>
                                <th>Activity Logs</th>
                                <th>Item Needed</th>
                                <th>Conversion UoM</th>
                                <th>Date</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($this->length($page->items) == 0) { ?>
                            <tr>
                                <td colspan="7">No data available</td>
                            </tr>
                            <?php } else { ?>
                                <?php $v137912291028539360251iterator = $page->items; $v137912291028539360251incr = 0; $v137912291028539360251loop = new stdClass(); $v137912291028539360251loop->self = &$v137912291028539360251loop; $v137912291028539360251loop->length = count($v137912291028539360251iterator); $v137912291028539360251loop->index = 1; $v137912291028539360251loop->index0 = 1; $v137912291028539360251loop->revindex = $v137912291028539360251loop->length; $v137912291028539360251loop->revindex0 = $v137912291028539360251loop->length - 1; ?><?php foreach ($v137912291028539360251iterator as $supporting) { ?><?php $v137912291028539360251loop->first = ($v137912291028539360251incr == 0); $v137912291028539360251loop->index = $v137912291028539360251incr + 1; $v137912291028539360251loop->index0 = $v137912291028539360251incr; $v137912291028539360251loop->revindex = $v137912291028539360251loop->length - $v137912291028539360251incr; $v137912291028539360251loop->revindex0 = $v137912291028539360251loop->length - ($v137912291028539360251incr + 1); $v137912291028539360251loop->last = ($v137912291028539360251incr == ($v137912291028539360251loop->length - 1)); ?>
                                <tr>
                                    <td><?= $v137912291028539360251loop->index + (($page->current - 1) * $page->limit) ?></td>
                                    <td><?= $supporting->code ?></td>
                                    <td><?= $supporting->name ?></td>
                                    <td><?= $supporting->activity_name ?></td>
                                    <td><?= $supporting->item_needed ?> <?= $supporting->uom ?></td>
                                    <td><?= $supporting->conversion_of_uom_item ?></td>
                                    <td><?= $supporting->date ?></td>
                                    <td>
                                        <?php if (!empty($supporting->image)) { ?>
                                            <?php $imagePaths = json_decode($supporting->image); ?>

                                            <?php if ($this->length($imagePaths) > 0) { ?>
                                                <div class="d-flex flex-column gap-3">
                                                    <?php foreach ($imagePaths as $img) { ?>
                                                        <div class=" text-center">
                                                            <img src="/<?= $img ?>" alt="Image" class="img-fluid rounded border shadow-sm"
                                                                style="width: 80px; height: auto; object-fit: cover;">
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            <?php } else { ?>
                                                <span class="text-muted">No Image</span>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <span class="text-muted">No Image</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $supporting->id ?>">Delete</button>
                                    </td>
                                </tr>
                                <?php $v137912291028539360251incr++; } ?>
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
                        <?php $v137912291028539360251iterator = range(1, $page->total_pages); $v137912291028539360251incr = 0; $v137912291028539360251loop = new stdClass(); $v137912291028539360251loop->self = &$v137912291028539360251loop; $v137912291028539360251loop->length = count($v137912291028539360251iterator); $v137912291028539360251loop->index = 1; $v137912291028539360251loop->index0 = 1; $v137912291028539360251loop->revindex = $v137912291028539360251loop->length; $v137912291028539360251loop->revindex0 = $v137912291028539360251loop->length - 1; ?><?php foreach ($v137912291028539360251iterator as $i) { ?><?php $v137912291028539360251loop->first = ($v137912291028539360251incr == 0); $v137912291028539360251loop->index = $v137912291028539360251incr + 1; $v137912291028539360251loop->index0 = $v137912291028539360251incr; $v137912291028539360251loop->revindex = $v137912291028539360251loop->length - $v137912291028539360251incr; $v137912291028539360251loop->revindex0 = $v137912291028539360251loop->length - ($v137912291028539360251incr + 1); $v137912291028539360251loop->last = ($v137912291028539360251incr == ($v137912291028539360251loop->length - 1)); ?>
                        <li class="page-item <?php if ($i == $page->current) { ?>active<?php } ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                        <?php $v137912291028539360251incr++; } ?>
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

    <!-- Modal for Adding/Editing Supporting Material -->
    <div class="modal fade" id="addUomModal" tabindex="-1" aria-labelledby="addUomModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUomModalLabel">Add Supporting Material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addEditUomForm" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="supportingMaterialId">

                        <div class="mb-3">
                            <label for="plotId" class="form-label">Plot Code</label>
                            <select name="plot_id" class="form-control" id="plotId" required>
                                <option value="">Select Plot Code</option>
                                <?php $v137912291028539360251iterator = $plots; $v137912291028539360251incr = 0; $v137912291028539360251loop = new stdClass(); $v137912291028539360251loop->self = &$v137912291028539360251loop; $v137912291028539360251loop->length = count($v137912291028539360251iterator); $v137912291028539360251loop->index = 1; $v137912291028539360251loop->index0 = 1; $v137912291028539360251loop->revindex = $v137912291028539360251loop->length; $v137912291028539360251loop->revindex0 = $v137912291028539360251loop->length - 1; ?><?php foreach ($v137912291028539360251iterator as $plot) { ?><?php $v137912291028539360251loop->first = ($v137912291028539360251incr == 0); $v137912291028539360251loop->index = $v137912291028539360251incr + 1; $v137912291028539360251loop->index0 = $v137912291028539360251incr; $v137912291028539360251loop->revindex = $v137912291028539360251loop->length - $v137912291028539360251incr; $v137912291028539360251loop->revindex0 = $v137912291028539360251loop->length - ($v137912291028539360251incr + 1); $v137912291028539360251loop->last = ($v137912291028539360251incr == ($v137912291028539360251loop->length - 1)); ?>
                                    <option value="<?= $plot->id ?>">
                                        <?= $plot->code ?> - [ <?= $plot->project->project ?> (<?= $plot->project->code ?>) ]
                                    </option>
                                <?php $v137912291028539360251incr++; } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="activityLogs" class="form-label">Activity Log</label>
                            <select name="activity_log_id" class="form-control" id="activityLogs" required>
                                <option value="">Select Activity Log</option>
                                <?php $v137912291028539360251iterator = $activityLogs; $v137912291028539360251incr = 0; $v137912291028539360251loop = new stdClass(); $v137912291028539360251loop->self = &$v137912291028539360251loop; $v137912291028539360251loop->length = count($v137912291028539360251iterator); $v137912291028539360251loop->index = 1; $v137912291028539360251loop->index0 = 1; $v137912291028539360251loop->revindex = $v137912291028539360251loop->length; $v137912291028539360251loop->revindex0 = $v137912291028539360251loop->length - 1; ?><?php foreach ($v137912291028539360251iterator as $activityLog) { ?><?php $v137912291028539360251loop->first = ($v137912291028539360251incr == 0); $v137912291028539360251loop->index = $v137912291028539360251incr + 1; $v137912291028539360251loop->index0 = $v137912291028539360251incr; $v137912291028539360251loop->revindex = $v137912291028539360251loop->length - $v137912291028539360251incr; $v137912291028539360251loop->revindex0 = $v137912291028539360251loop->length - ($v137912291028539360251incr + 1); $v137912291028539360251loop->last = ($v137912291028539360251incr == ($v137912291028539360251loop->length - 1)); ?>
                                    <option value="<?= $activityLog->id ?>">
                                        <?= $activityLog->activitySetting->name ?> - [ <?= $activityLog->description ?> ]
                                    </option>
                                <?php $v137912291028539360251incr++; } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="materialId" class="form-label">Name Material</label>
                            <select name="material_id" class="form-control" id="materialId" required>
                                <option value="">Select Material</option>
                                <?php $v137912291028539360251iterator = $materials; $v137912291028539360251incr = 0; $v137912291028539360251loop = new stdClass(); $v137912291028539360251loop->self = &$v137912291028539360251loop; $v137912291028539360251loop->length = count($v137912291028539360251iterator); $v137912291028539360251loop->index = 1; $v137912291028539360251loop->index0 = 1; $v137912291028539360251loop->revindex = $v137912291028539360251loop->length; $v137912291028539360251loop->revindex0 = $v137912291028539360251loop->length - 1; ?><?php foreach ($v137912291028539360251iterator as $material) { ?><?php $v137912291028539360251loop->first = ($v137912291028539360251incr == 0); $v137912291028539360251loop->index = $v137912291028539360251incr + 1; $v137912291028539360251loop->index0 = $v137912291028539360251incr; $v137912291028539360251loop->revindex = $v137912291028539360251loop->length - $v137912291028539360251incr; $v137912291028539360251loop->revindex0 = $v137912291028539360251loop->length - ($v137912291028539360251incr + 1); $v137912291028539360251loop->last = ($v137912291028539360251incr == ($v137912291028539360251loop->length - 1)); ?>
                                    <option value="<?= $material->id ?>">
                                        <?= $material->name ?> - [ UoM (<?= $material->uom ?>) ] - [ STOCK (<?= $material->stock ?> <?= $material->uom ?>) ]
                                    </option>
                                <?php $v137912291028539360251incr++; } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="uom" class="form-label">UoM</label>
                            <select name="uom" class="form-control" id="uom" required>
                                <option value="">Select UoM Conversion</option>
                                <?php $v137912291028539360251iterator = $uoms; $v137912291028539360251incr = 0; $v137912291028539360251loop = new stdClass(); $v137912291028539360251loop->self = &$v137912291028539360251loop; $v137912291028539360251loop->length = count($v137912291028539360251iterator); $v137912291028539360251loop->index = 1; $v137912291028539360251loop->index0 = 1; $v137912291028539360251loop->revindex = $v137912291028539360251loop->length; $v137912291028539360251loop->revindex0 = $v137912291028539360251loop->length - 1; ?><?php foreach ($v137912291028539360251iterator as $uom) { ?><?php $v137912291028539360251loop->first = ($v137912291028539360251incr == 0); $v137912291028539360251loop->index = $v137912291028539360251incr + 1; $v137912291028539360251loop->index0 = $v137912291028539360251incr; $v137912291028539360251loop->revindex = $v137912291028539360251loop->length - $v137912291028539360251incr; $v137912291028539360251loop->revindex0 = $v137912291028539360251loop->length - ($v137912291028539360251incr + 1); $v137912291028539360251loop->last = ($v137912291028539360251incr == ($v137912291028539360251loop->length - 1)); ?>
                                    <option data-uom-setting="<?= $uom->uom_end->name ?>" value="<?= $uom->id ?>">
                                        <?= $uom->name ?>
                                    </option>
                                <?php $v137912291028539360251incr++; } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="itemNeeded" class="form-label">Item Needed <small class="text-danger fw-semibold" >*UoM units follow below</small> </label>
                            <div class="input-group">
                                <input name="item_needed" type="text" class="form-control" id="itemNeeded" required>
                                <span class="input-group-text input-group-item-needed">NULL</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Date <small class="text-danger fw-semibold" >*date of use</small> </label>
                            <input name="date" type="date" class="form-control" id="date" required>
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label">Image Upload</label>
                            <div id="image-upload-wrapper" class="d-flex align-items-center gap-2 flex-wrap">
                                <label for="image-upload" class="image-upload-box">
                                    <i class="fa fa-camera"></i>
                                    <span>Browse</span>
                                    <input type="file" id="image-upload" name="image[]" class="d-none" accept="image/*" multiple>
                                </label>
                                <div id="previewContainer" class="preview-container d-flex gap-2 flex-wrap"></div>
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

            $('#plotId').on('change', function() {
                const plotId = $(this).val();

                if (plotId) {
                    $.ajax({
                        url: `/frontend/supporting-material/activity-logs?plot_id=${plotId}`,
                        type: 'GET',
                        success: function(response) {
                            $('#activityLogs').empty();
                            $('#activityLogs').append('<option value="">Select Activity Log</option>');
                            $.each(response.activityLogs, function(index, activityLog) {
                            console.log(activityLog)
                                $('#activityLogs').append(`<option value="${activityLog.log_id}">${activityLog.activity_name} - [${activityLog.start_date} - ${activityLog.end_date}]</option>`);
                            });
                        },
                        error: function(xhr) {
                            console.error('Failed to fetch activity logs', xhr);
                        }
                    });
                }
            });


            $('#uom').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const uomSetting = selectedOption.data('uom-setting');
                const uom = $(this).val();

                if(uomSetting) {
                    $('.input-group-item-needed').text(uomSetting);
                } else {
                    $('.input-group-item-needed').text('NULL');
                }
            });

            $('#image-upload').on('change', function(event) {
                let files = event.target.files;
                let previewContainer = $('#previewContainer');
                let imageUploadBox = $('.image-upload-box');

                $.each(files, function(index, file) {
                    if (file.type.match('image.*')) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            let previewBox = $('<div>').addClass('preview-box');
                            let img = $('<img>').attr('src', e.target.result);
                            let removeBtn = $('<button>').addClass('remove-btn').text('X');

                            removeBtn.on('click', function() {
                                $(this).parent().remove();
                                if ($('#previewContainer').children().length === 0) {
                                    imageUploadBox.css('order', '0'); // Balikin ke kiri kalau ga ada gambar
                                }
                            });

                            previewBox.append(img).append(removeBtn);
                            previewContainer.append(previewBox);

                            // Pindahin Browse ke kanan setelah ada preview
                            imageUploadBox.css('order', '1');
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });

            $('#btnAddModal').on('click', function() {
                $('#addEditUomForm')[0].reset();
                $('#addUomModalLabel').text('Add Supporting Material');
            });

            // Handle form submission for adding/editing
            $('#addEditUomForm').on('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(this);

                $.ajax({
                    url: '/frontend/supporting-material/save',
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function(response) {
                    console.log(response);
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            $('#addUomModal').modal('hide');
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON.message,
                            confirmButtonText: 'OK'
                        });
                    }
                });
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
                            url: '/frontend/supporting-material/delete/' + id,
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