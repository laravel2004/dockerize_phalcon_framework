{% extends 'layouts/main.volt' %}

{% block content %}
<div class="container">
    <div class="card">
        <div class="card-header">
                <div class="d-flex flex-column flex-md-row gap-4 justify-content-md-between align-items-start align-items-md-center mb-3">
                    <form method="GET" action="/frontend/material" class="d-flex flex-column flex-sm-row mb-3 w-100 mb-md-0">
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
                            <th>Stock</th>
                            <th>UoM</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {% if page.items|length == 0 %}
                        <tr>
                            <td colspan="5">No data available</td>
                        </tr>
                        {% else %}
                            {% for material in page.items %}
                            <tr>
                                <td>{{ loop.index + ((page.current - 1) * page.limit) }}</td>
                                <td>{{ material.name }}</td>
                                <td>{{ material.stock }}</td>
                                <td>{{ material.uom }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn"
                                            data-id="{{ material.id }}"
                                            data-name="{{ material.name }}"
                                            data-uom="{{ material.uom }}"
                                            data-stock="{{ material.stock }}">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ material.id }}">Delete</button>
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
                <h5 class="modal-title" id="addUomModalLabel">Add Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEditUomForm">
                    <input type="hidden" id="uomId">
                    <div class="mb-3">
                        <label for="uomName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="materialName" required>
                    </div>
                    <div class="mb-3">
                        <label for="materialUom" class="form-label text-left">UoM</label>
                        <select class="form-select" id="materialUom" name="project_id" required>
                            <option value="">Select UoM</option>
                            {% for uom in uoms %}
                                <option value="{{ uom.name }}">
                                    {{ uom.name }}
                                </option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="materialStock" class="form-label">Stock</label>
                        <input type="text" class="form-control" id="materialStock" required>
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
            $('#addUomModalLabel').text('Add Material');
        });

        // Handle form submission for adding/editing
        $('#addEditUomForm').on('submit', function(event) {
            event.preventDefault();

            const uomId = $('#uomId').val();
            const materialName = $('#materialName').val();
            const materialUom = $('#materialUom').val();
            const materialStock = $('#materialStock').val();
            const url = uomId ? '/frontend/material/save' : '/frontend/material/save';

            if (materialName) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        id: uomId,
                        name: materialName,
                        uom: materialUom,
                        stock: materialStock
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
        console.log($(this).data('name'));
            const id = $(this).data('id');
            const materialName = $(this).data('name');
            const materialUom = $(this).data('uom');
            const materialStock = $(this).data('stock');

            $('#uomId').val(id);
            $('#materialName').val(materialName);
            $('#materialUom').val(materialUom);
            $('#materialStock').val(materialStock);
            $('#addUomModalLabel').text('Edit Material');
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
                        url: '/frontend/material/delete/' + id,
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
{% endblock %}
