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

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3>Create Activity Log</h3>
        </div>
        <div class="card-body">
            <form id="createActivityLogForm" method="POST" enctype="multipart/form-data">
                <div class="mb-4 mt-4">
                    <label for="activity-setting" class="form-label">Activity Setting</label>
                    <select name="activity_setting_id" id="activity-setting" class="form-select" required>
                        <option value="">Select Activity Setting</option>
                        <?php foreach ($activitySettings as $activitySetting) { ?>
                            <option value="<?= $activitySetting->id ?>" data-type="<?= $activitySetting->type ?>"><?= $activitySetting->name ?> - [ TYPE(<?= $activitySetting->type ?>) ]</option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="plot" class="form-label">Plot</label>
                    <select name="plot_id" id="plot" class="form-select" required>
                        <option value="">Select Plot</option>
                        <?php foreach ($plots as $plot) { ?>
                            <option value="<?= $plot->id ?>"><?= $plot->code ?> -  <?= $plot->project->project ?></option>
                        <?php } ?>
                    </select>
                </div>
                <!-- Time of Work (conditionally displayed) -->
                <div class="mb-4 time-of-work-container d-none">
                    <label for="time-of-work" class="form-label" id="time-of-work-label">Time of Work (days)</label>
                    <div class="input-group">
                        <input type="number" name="time_of_work" id="time-of-work" class="form-control">
                        <span class="input-group-text" id="time-of-work-type"></span>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="total-worker" class="form-label">Total Worker</label>
                    <input type="number" name="total_worker" id="total-worker" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label for="cost" class="form-label">Cost</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp.</span>
                        <input type="text" name="cost" id="cost" class="form-control" required>
                    </div>
                    <!-- Note for cost input -->
                    <small id="cost-note" class="form-text text-muted">For Harian, fill in the daily wage per worker. For Jam, fill in the hourly wage per worker.</small>
                </div>

                <div class="row" >
                    <div class="mb-4 col-6">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                    </div>

                    <div class="mb-4 col-6">
                        <label for="end_date" class="form-label">End Time</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                </div>

                <!-- Workers Details Section -->
                <div id="workers-container" class="mb-4 d-none">
                    <h5 class="fw-bold mb-3">Enter Worker Details</h5>
                    <div id="worker-forms"></div>
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

                <button type="submit" class="btn btn-primary w-100 ">Create</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
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

        // Toggle time of work based on selected activity setting type
        $('#activity-setting').on('change', function() {
            const selectedOption = $(this).find(':selected');
            const type = selectedOption.data('type');

            if (type === 'Jam') {
                $('#time-of-work-label').text('Time of Work (hours)');
                $('.time-of-work-container').removeClass('d-none');
                $('#cost-note').removeClass('d-none').text('For Jam, fill in the hourly wage per worker.');
                $('#time-of-work-type').text('hours');
            } else {
                $('.time-of-work-container').addClass('d-none');
                $('#cost-note').addClass('d-none');
            }
        });

        // Handle Total Workers input change
        $('#total-worker').on('change', function() {
            const totalWorkers = $(this).val();
            const workersContainer = $('#workers-container');
            const workerForms = $('#worker-forms');

            workerForms.empty();

            if (totalWorkers > 0) {
                workersContainer.removeClass('d-none');
                for (let i = 0; i < totalWorkers; i++) {
                    const workerForm = `
                    <div class="mb-3">
                        <label for="worker-${i}" class="form-label">Worker ${i + 1} Name</label>
                        <input type="text" name="workers[${i}][name]" id="worker-${i}" class="form-control" required>
                    </div>`;
                    workerForms.append(workerForm);
                }
            } else {
                workersContainer.addClass('d-none');
            }
        });

        // Format the cost input field to show currency
        $('#cost').on('input', function() {
            var inputVal = $(this).val().replace(/[^\d]/g, ''); // Remove non-numeric characters
            if (inputVal) {
                // Format with comma as thousand separator
                inputVal = inputVal.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                $(this).val(inputVal);
            }
        });

        // Handle form submission with AJAX
        $('#createActivityLogForm').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(this); // Get form data

            // Show SweetAlert confirmation before submission
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to create this activity log?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, create it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/frontend/activity-log/save',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Created!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location.href = '/frontend/activity-log'; // Redirect after success
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: xhr.responseJSON.message,
                                confirmButtonText: 'OK'
                            });
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