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
                    <form method="GET" action="/frontend/worker-data" class="d-flex flex-column flex-sm-row mb-3 w-100 mb-md-0">
                        <input type="text" name="search" class="form-control w-100 me-0 me-sm-2 mb-2 mb-sm-0" placeholder="Search by name" value="{{ search }}">
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
                            <th>Address</th>
                            <th>Rekening(Bank)</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {% if page.items|length == 0 %}
                        <tr>
                            <td colspan="6">No data available</td>
                        </tr>
                        {% else %}
                            {% for workerData in page.items %}
                            <tr>
                                <td>{{ loop.index + ((page.current - 1) * page.limit) }}</td>
                                <td>{{ workerData.name }}</td>
                                <td>{{ workerData.address }}</td>
                                <td>{{ workerData.no_rekening }} ({{ workerData.nama_bank }})</td>
                                <td>
                                    {% if workerData.image is not empty %}
                                        {% set imagePaths = workerData.image|json_decode %}

                                        {% if imagePaths|length > 0 %}
                                            <div class="d-flex flex-column gap-3">
                                                {% for img in imagePaths %}
                                                    <div class=" text-center">
                                                        <img src="/{{ img }}" alt="Image" class="img-fluid rounded border shadow-sm"
                                                            style="width: 80px; height: auto; object-fit: cover;">
                                                    </div>
                                                {% endfor %}
                                            </div>
                                        {% else %}
                                            <span class="text-muted">No Image</span>
                                        {% endif %}
                                    {% else %}
                                        <span class="text-muted">No Image</span>
                                    {% endif %}
                                </td>
                                <td>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ workerData.id }}">Delete</button>
                                </td>
                            </tr>
                            {% endfor %}
                        {% endif %}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <nav aria-label="Pagination">
                <ul class="pagination justify-content-center">
                    {% if page.before %}
                    <li class="page-item">
                        <a class="page-link" href="?page={{ page.before }}">Previous</a>
                    </li>
                    {% endif %}
                    {% for i in 1..page.total_pages %}
                    <li class="page-item {% if i == page.current %}active{% endif %}">
                        <a class="page-link" href="?page={{ i }}">{{ i }}</a>
                    </li>
                    {% endfor %}
                    {% if page.next %}
                    <li class="page-item">
                        <a class="page-link" href="?page={{ page.next }}">Next</a>
                    </li>
                    {% endif %}
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
                <h5 class="modal-title" id="addUomModalLabel">Add/Edit Worker Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEditUomForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="workerId">
                    <div class="mb-3">
                        <label for="uomName" class="form-label">Name</label>
                        <input placeholder="Enter name" type="text" name="name" class="form-control" id="workerName" required>
                    </div>
                    <div class="mb-3">
                        <label for="workerAddress" class="form-label">Address</label>
                        <input placeholder="Enter address" name="address" type="text" class="form-control" id="workerAddress" required>
                    </div>
                    <div class="mb-3">
                        <label for="workerBank" class="form-label ">Bank</label>
                        <input placeholder="Enter name bank" name="nama_bank" type="text" class="form-control" id="workerBank" required>
                    </div>
                    <div class="mb-3">
                        <label for="workerRekening" class="form-label ">Rekening</label>
                        <input placeholder="Enter rekening" name="no_rekening" type="text" class="form-control" id="workerRekening" required>
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
        $('#btnAddModal').click(function() {
            $('#addEditUomForm')[0].reset();
            $('#addUomModalLabel').text('Add Worker Data');
        });

        $('#addEditUomForm').on('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            $.ajax({
                url: '/frontend/worker-data/save',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
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
                        url: `/frontend/worker-data/delete/${id}`,
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
{% endblock %}