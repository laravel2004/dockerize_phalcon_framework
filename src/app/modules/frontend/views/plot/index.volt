{% extends 'layouts/main.volt' %}

{% block content %}
<div class="container">
    <div class="card">
        <div class="card-header">
                <div class="d-flex flex-column flex-md-row gap-4 justify-content-md-between align-items-start align-items-md-center mb-3">
                    <form method="GET" action="/frontend/plot" class="d-flex flex-column flex-sm-row mb-3 w-100 mb-md-0">
                        <input type="text" name="search" class="form-control w-100 me-0 me-sm-2 mb-2 mb-sm-0" placeholder="Search by code" value="{{ search }}">
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
                            <th>Wide </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {% if page.items|length == 0 %}
                        <tr>
                            <td colspan="4">No data available</td>
                        </tr>
                        {% else %}
                            {% for plot in page.items %}
                            <tr>
                                <td>{{ loop.index + ((page.current - 1) * page.limit) }}</td>
                                <td>{{ plot.code }}</td>
                                <td>{{ plot.project.project }} - {{ plot.project.code }}</td>
                                <td>{{ plot.wide }} Hectare</td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn"
                                            data-id="{{ plot.id }}"
                                            data-code="{{ plot.code }}"
                                            data-wide="{{ plot.wide }}"
                                            data-project="{{ plot.project.id }}">Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ plot.id }}">Delete</button>
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
                            {% for project in projects %}
                                <option value="{{ project.id }}"
                                >
                                    {{ project.project }}
                                </option>
                            {% endfor %}
                        </select>
                    </div>

                    <!-- Code Field -->
                    <div class="mb-3">
                        <label for="plotCode" class="form-label">Code</label>
                        <input placeholder="Enter the code" type="text" name="code" class="form-control" id="plotCode" required>
                    </div>

                    <!-- Wide Field -->
                    <div class="mb-3">
                        <label for="plotWide" class="form-label">Wide</label>
                        <input placeholder="10" type="number" step="0.01" min="0" name="wide" class="form-control" id="plotWide" required>
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

            $('#plotId').val($(this).data('id'));
            $('#projectId').val($(this).data('project'));
            $('#plotCode').val($(this).data('code'));
            $('#plotWide').val($(this).data('wide'));

            $('#addUomModalLabel').text('Edit Plot');
            $('#addUomModal').modal('show');
        });

        // Handle form submission for adding/editing Plot
        $('#addEditUomForm').on('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: '/frontend/plot/save',
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
{% endblock %}
