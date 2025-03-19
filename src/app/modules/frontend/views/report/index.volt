{% extends 'layouts/main.volt' %}

{% block content %}
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
<div class="container">
    <form id="createReportForm" method="POST" enctype="multipart/form-data">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-4">
                    <label class="form-label">Include Supporting Material</label>
                    <div class="form-check form-switch">
                        <input value="on" class="form-check-input" name="is_include" type="checkbox" id="toggleSupportingMaterial">
                        <label class="form-check-label" for="toggleSupportingMaterial">Yes</label>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="activitySetting" class="form-label">Activity</label>
                    <select name="activity_setting_id" id="activitySetting" class="form-select" required>
                        <option value="">Select Activity</option>
                        {% for activitySetting in activitySettings %}
                            <option value="{{ activitySetting.id }}">{{ activitySetting.name }}[Rp. {{ activitySetting.typeActivity.cost }}]</option>
                        {% endfor %}
                    </select>
                </div>
                <div class="mb-4 row">
                    <div class="col-md-6">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Project & Plot</label>
                    <div id="plotContainer">
                        <div class="input-group plot-clone row mb-2">
                            <div class="col-4">
                                <select id="project_id" name="project_id[]" class="form-select" required>
                                    <option value="">Select Project</option>
                                    {% for project in projects %}
                                        <option value="{{ project.id }}">{{ project.project }} [{{ project.code }}]</option>
                                    {% endfor %}
                                </select>
                            </div>
                            <div class="col-8">
                                <div class="input-group">
                                    <select name="plot_id[]" class="form-select" required>
                                        <option value="">Select Plot</option>
                                        {% for plot in plots %}
                                            <option value="{{ plot.id }}">{{ plot.code }} [{{ plot.project.project }}]</option>
                                        {% endfor %}
                                    </select>
                                    <button type="button" class="btn btn-success addPlot">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4" placeholder="the description" required></textarea>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h3>Worker</h3>
            </div>
            <div class="card-body">
                <div class="mb-4 mt-4">
                    <div id="workerContainer">
                        <div class="input-group mb-2">
                            <select name="worker_id[]" class="form-select" required>
                                <option value="">Select Worker</option>
                                {% for worker in workers %}
                                    <option value="{{ worker.id }}">{{ worker.name }}</option>
                                {% endfor %}
                            </select>
                            <button type="button" class="btn btn-success addWorker">+</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm" id="supportingMaterialCard" style="display: none;">
            <div class="card-header">
                <h3>Supporting Material</h3>
            </div>
            <div class="card-body">
                <div id="supportingMaterialContainer">
                    <div class="input-group mb-2">
                        <select name="supporting_material_id[]" class="form-select">
                            <option value="">Select Material</option>
                            {% for material in materials %}
                                <option value="{{ material.id }}">{{ material.name }}[{{ material.conversion_uom.name }}]</option>
                            {% endfor %}
                        </select>
                        <input type="number" name="item_needed[]" class="form-control" placeholder="Item Needed">
                        <button type="button" class="btn btn-success addSupportingMaterial">+</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h3>Image Upload</h3>
            </div>
            <div class="card-body">
                <div class="mb-4 mt-4">
                    <div id="image-upload-wrapper" class="d-flex align-items-center gap-2 flex-wrap">
                        <label for="image-upload" class="image-upload-box">
                            <i class="fa fa-camera"></i>
                            <span>Browse</span>
                            <input type="file" id="image-upload" name="image[]" class="d-none" accept="image/*" multiple>
                        </label>
                        <div id="previewContainer" class="preview-container d-flex gap-2 flex-wrap"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
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

                        imageUploadBox.css('order', '1');
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        $('#toggleSupportingMaterial').change(function() {
            if ($(this).is(':checked')) {
                $('#supportingMaterialCard').show();
            } else {
                $('#supportingMaterialCard').hide();
            }
        });

        function fetchPlots(projectId, plotSelect) {
            $.ajax({
                url: `/frontend/report/search-project/${projectId}`,
                method: 'GET',
                success: function(response) {
                    plotSelect.empty().append('<option value="">Select Plot</option>');
                    response.plots.forEach(plot => {
                        plotSelect.append(`<option value="${plot.id}">${plot.code}</option>`);
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to fetch plots.',
                    });
                }
            });
        }

        $(document).on('change', '[name="project_id[]"]', function() {
            let projectId = $(this).val();
            let plotSelect = $(this).closest('.plot-clone').find('[name="plot_id[]"]');
            if (projectId) {
                fetchPlots(projectId, plotSelect);
            } else {
                plotSelect.empty().append('<option value="">Select Plot</option>');
            }
        });

        $(document).on('click', '.addPlot', function() {
            let plotClone = $(this).closest('.plot-clone').clone();
            plotClone.find('button').removeClass('btn-success addPlot').addClass('btn-danger removePlot').text('-');
            plotClone.find('select').val('');
            $('#plotContainer').append(plotClone);
        });

        $(document).on('click', '.removePlot', function() {
            $(this).closest('.plot-clone').remove();
        });

        $(document).on('click', '.addWorker', function() {
            let workerSelect = $(this).closest('.input-group').clone();
            workerSelect.find('button').removeClass('btn-success addWorker').addClass('btn-danger removeWorker').text('-');
            $('#workerContainer').append(workerSelect);
        });

        $(document).on('click', '.removeWorker', function() {
            $(this).closest('.input-group').remove();
        });

        $(document).on('click', '.addSupportingMaterial', function() {
            let materialSelect = $(this).closest('.input-group').clone();
            materialSelect.find('button').removeClass('btn-success addSupportingMaterial').addClass('btn-danger removeSupportingMaterial').text('-');
            $('#supportingMaterialContainer').append(materialSelect);
        });

        $(document).on('click', '.removeSupportingMaterial', function() {
            $(this).closest('.input-group').remove();
        });

        $('#createReportForm').submit(function(event) {
            event.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: '/frontend/report/save',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(function() {
                            location.href = '/frontend/report/history';
                        });
                    }
                    else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                    }
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
        });
    });
</script>
{% endblock %}
