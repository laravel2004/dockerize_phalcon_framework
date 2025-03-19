{% extends 'layouts/main.volt' %}

{% block content %}
<div class="container">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col-4">
                    <a href="/frontend/report" class="btn btn-primary fw-semibold">+ Create Activity Input</a>
                </div>
                <div class="col-8 text-end">
                    <button  id="btn-summarize" class="btn btn-success fw-semibold">Summarize</button>
                    <button  id="btn-generate" class="btn btn-warning text-white fw-semibold">Generate Report</button>
                </div>
            </div>
            <form method="GET" action="/frontend/report/history">
                <div class="row justify-content-start gap-2 gap-md-0 mt-5 ">
                    <div class="col-md-3">
                        <select name="project_id" id="project_id" class="form-control text-center">
                            <option value="">All Project</option>
                            {% for project in projects %}
                                <option value="{{ project.id }}">{{ project.project }}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="plot_id" id="plot_id" class="form-control text-center">
                            <option value="" >All Plot</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="date_range" id="date_range" class="form-control text-center" placeholder="Select Date Range">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </div>
            </form>
            <hr class="mt-3" />
            <div>
                {% if(activityLogs|length == 0) %}
                {% else %}
                    {% for activityLog in activityLogs %}
                        <div class="container mt-3 rounded-2 border">
                            <div class="d-flex flex-column gap-3">
                                <div class="p-3 d-flex flex-row align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-light text-dark me-2">{{ activityLog.plot.code }}</span>
                                            <h6 class="mb-0">{{ activityLog.activitySetting.name }}</h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong>Total Cost:</strong>
                                                <span class="text-danger format-rupiah">{{ activityLog.total_cost }}</span>
                                                <br>
                                                <small class="text-muted">Created: {{ activityLog.created_at }}</small>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Description:</strong>
                                                <p class="text-muted mb-0">{{ activityLog.description }}</p>
                                            </div>
                                            <div class="col-md-6 text-center">
                                                <strong class="text-center">Material:</strong>
                                                <div class="text-center">
                                                    {% if(activityLog.supportingMaterials|length == 0) %}
                                                        <span class="badge bg-danger text-dark me-2">No Material</span>
                                                    {% else %}
                                                        {% for supportingMaterial in activityLog.supportingMaterials %}
                                                            <span class="badge bg-light text-dark me-2">{{ supportingMaterial.material.name }}</span>
                                                        {% endfor %}
                                                    {% endif %}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                          Action
                                        </button>
                                        <ul class="dropdown-menu">
                                            {% set imagePaths = activityLog.image|json_decode %}
                                            <li class="dropdown-item">
                                                {% if imagePaths|length > 0 %}
                                                    <button class="btn btn-secondary w-100" id="btnAddModal" data-bs-toggle="modal" data-bs-target="#photoModal" data-photo='{{ imagePaths|json_encode }}'>See Photo</button>
                                                {% else %}
                                                    <span class="text-muted text-center">No Image</span>
                                                {% endif %}
                                            </li>
                                            <li class="dropdown-item"><a href="{{ url('/frontend/report/detail/') ~ activityLog.id }}" class="btn btn-warning w-100">Detail</a></li>
                                            <li class="dropdown-item"><button data-id={{ activityLog.id }} class="btn-delete btn btn-danger w-100">Delete</button></li>
                                        </ul>
                                      </div>
                                </div>
                            </div>
                        </div>
                    {% endfor %}
                {% endif %}
            </div>

        </div>
    </div>
</div>

<!-- Modal for Adding/Editing UoM -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="addUomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUomModalLabel">Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body ">
                <div class="container">
                    <div class="row justify-content-center flex-wrap" id="photoContainer">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {

        $('#photoModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var photoData = button.data('photo');
            var photoContainer = $('#photoContainer');
            photoContainer.empty();

            try {
                if (Array.isArray(photoData) && photoData.length > 0) {
                    photoData.forEach(function(photo) {
                        var colDiv = $('<div>').addClass('col-6 col-md-4 d-flex justify-content-center mb-3');
                        var imgElement = $('<img>').attr('src', `/${photo}`)
                            .addClass('img-fluid')
                            .css({
                                'width': '300px',
                                'height': '300px',
                                'object-fit': 'cover',
                                'border': '4px solid #000',
                                'border-radius': '8px'
                            });
                        colDiv.append(imgElement);
                        photoContainer.append(colDiv);
                    });

                    if (photoData.length < 3) {
                        photoContainer.addClass('justify-content-center');
                    } else {
                        photoContainer.removeClass('justify-content-center');
                    }
                } else {
                    photoContainer.append('<p class="text-center">No images available</p>');
                }
            } catch (e) {
                photoContainer.append('<p class="text-center">Invalid image data</p>');
            }
        });

        $('#btnAddModal').click(function() {
            const data = $(this).data('photo');
            console.log(data)
        });

        $('.btn-delete').click(function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/frontend/report/delete/${id}`,
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

    $('#project_id').change(function () {
        var projectId = $(this).val();

        $.ajax({
            url: `/frontend/report/search-plot/${projectId}`,
            type: 'GET',
            success: function (response) {
                var select = $('#plot_id');
                select.empty();

                select.append('<option value="">All Plot</option>');

                $.each(response.data, function (index, item) {
                    select.append(`<option value="${item.id}">${item.code}</option>`);
                });
            },
            error: function () {
                alert('Gagal mengambil data');
            }
        });
    });

    $('.format-rupiah').each(function () {
        var angka = parseFloat($(this).text().trim());
        if (!isNaN(angka)) {
            var formatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
            $(this).text(formatted);
        }
    });

    $('#btn-summarize').click(function() {
        $query = window.location.search;
        window.location.href = '/frontend/report/summarize' + $query;
    });

    $('#btn-generate').click(function() {
        $query = window.location.search;
        window.location.href = '/frontend/report/generate' + $query;
    });

        $('#date_range').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('#date_range').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        });

        $('#date_range').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });

});
</script>

{% endblock %}
