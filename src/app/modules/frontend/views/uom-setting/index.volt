{% extends 'layouts/main.volt' %}

{% block content %}
<div class="container">
    <div class="card">
        <div class="card-header">
                <div class="d-flex flex-column flex-md-row gap-4 justify-content-md-between align-items-start align-items-md-center mb-3">
                    <form method="GET" action="/frontend/uom-setting" class="d-flex flex-column flex-sm-row mb-3 w-100 mb-md-0">
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {% if page.items|length == 0 %}
                        <tr>
                            <td colspan="3">No data available</td>
                        </tr>
                        {% else %}
                            {% for uom in page.items %}
                            <tr>
                                <td>{{ loop.index + ((page.current - 1) * page.limit) }}</td>
                                <td>{{ uom.name }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="{{ uom.id }}" data-name="{{ uom.name }}">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ uom.id }}">Delete</button>
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
                <h5 class="modal-title" id="addUomModalLabel">Add/Edit UoM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEditUomForm">
                    <input type="hidden" id="uomId">
                    <div class="mb-3">
                        <label for="uomName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="uomName" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Handle form submission for adding/editing
        $('#addEditUomForm').on('submit', function(event) {
            event.preventDefault();

            const uomId = $('#uomId').val();
            const uomName = $('#uomName').val();
            const url = uomId ? '/frontend/uom-setting/save' : '/frontend/uom-setting/save';

            if (uomName) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        id: uomId,
                        name: uomName
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

            $('#uomId').val(id);
            $('#uomName').val(name);
            $('#addUomModalLabel').text('Edit UoM');
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
                        url: '/frontend/uom-setting/delete/' + id,
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
