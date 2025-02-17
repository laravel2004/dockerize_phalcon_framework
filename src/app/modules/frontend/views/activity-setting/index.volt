{% extends 'layouts/main.volt' %}

{% block content %}
<div class="container">
    <div class="card">
        <div class="card-header">
                <div class="d-flex flex-column flex-md-row gap-4 justify-content-md-between align-items-start align-items-md-center mb-3">
                    <form method="GET" action="/frontend/activity-setting" class="d-flex flex-column flex-sm-row mb-3 w-100 mb-md-0">
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
                            <th>Description</th>
                            <th>Type</th>
                            <th>Cost</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {% if page.items|length == 0 %}
                        <tr>
                            <td colspan="3">No data available</td>
                        </tr>
                        {% else %}
                            {% for activitySetting in page.items %}
                            <tr>
                                <td>{{ loop.index + ((page.current - 1) * page.limit) }}</td>
                                <td>{{ activitySetting.name }}</td>
                                <td>{{ activitySetting.description }}</td>
                                <td>{{ activitySetting.type }}</td>
                                <td class="format-rupiah">{{ activitySetting.typeActivity.cost }} / {{ activitySetting.type }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn"
                                            data-id="{{ activitySetting.id }}"
                                            data-name="{{ activitySetting.name }}"
                                            data-type="{{ activitySetting.type_activity_id }}"
                                            data-description="{{ activitySetting.description }}">Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ activitySetting.id }}">Delete</button>
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
                    <input type="hidden" name="id" id="uomId">

                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="uomName" class="form-label">Name</label>
                        <input name="name" type="text" class="form-control" id="uomName" required>
                    </div>

                    <!-- Type Field -->
                    <div class="mb-3">
                        <label for="uomType" class="form-label">Type</label>
                        <select name="type_activity_id" class="form-select" id="uomType" required>
                            <option value="">Select Type</option>
                            {% for typeActivity in typeActivities %}
                                <option value="{{ typeActivity.id }}">{{ typeActivity.name_type }} [{{ typeActivity.cost }} / {{ typeActivity.name_type }}]</option>
                            {% endfor %}
                        </select>
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label for="uomDescription" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="uomDescription" rows="3" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

        function formatRupiah(number, type){
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);

            return type ? `${formatter} / ${type}` : formatter;
        }

        $('.format-rupiah').each(function() {
            const value = $(this).text();
            const [number, type] = value.split('/');
            $(this).text(formatRupiah(number.trim(), type.trim()));
        });

        $('#btnAddModal').on('click', function() {
            $('#addEditUomForm')[0].reset();
            $('#addUomModalLabel').text('Add Activity Setting');
        });

        // Handle form submission for adding/editing
        $('#addEditUomForm').on('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: '/frontend/activity-setting/save',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
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
{% endblock %}
