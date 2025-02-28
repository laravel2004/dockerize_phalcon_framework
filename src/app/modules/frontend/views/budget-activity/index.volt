{% extends 'layouts/main.volt' %}

{% block content %}
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
                    <form method="GET" action="/frontend/budget-activity" class="d-flex flex-column flex-sm-row mb-3 w-100 mb-md-0">
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
                            <th>Project</th>
                            <th>Activity Name</th>
                            <th>Period</th>
                            <th>Nominal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {% if page.items|length == 0 %}
                        <tr>
                            <td colspan="6">No data available</td>
                        </tr>
                        {% else %}
                            {% for budgetActivity in page.items %}
                            <tr>
                                <td>{{ loop.index + ((page.current - 1) * page.limit) }}</td>
                                <td>{{ budgetActivity.project_name }}</td>
                                <td>{{ budgetActivity.activity_name }}</td>
                                <td>{{ budgetActivity.period }}</td>
                                <td class="format-rupiah">{{ budgetActivity.nominal }}</td>
                                <td>
                                    <button class="btn btn-danger  btn-sm delete-btn" data-id="{{ budgetActivity.id }}">Delete</button>
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
                <form id="addEditBudgetActivityForm">
                    <input type="hidden" name="id" id="uomId">

                    <!-- Project Field -->
                    <div class="mb-3">
                        <label for="project" class="form-label">Project</label>
                        <select name="project_id" class="form-select" id="project" required>
                            <option value="">Select Project</option>
                            {% for project in projects %}
                            <option value="{{ project.id }}">{{ project.project }}</option>
                            {% endfor %}
                        </select>
                    </div>

                    <!-- Activity Name Field -->
                    <div class="mb-3">
                        <label for="activitySetting" class="form-label" >Activity</label>
                        <select name="activity_setting_id" class="form-select" id="activitySetting" required>
                            <option value="">Select Activity</option>
                            {% for activity in activitySettings %}
                            <option value="{{ activity.id }}">{{ activity.name }}</option>
                            {% endfor %}
                        </select>
                    </div>

                    <!-- Period Field -->
                    <div class="mb-3">
                        <label for="period" class="form-label">Period</label>
                        <input name="period" type="number" class="form-control" id="period" required>
                    </div>

                    <!-- Nominal Field -->
                    <div class="mb-3">
                        <label for="nominal" class="form-label">Nominal</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input name="nominal" type="text" class="form-control" id="nominal" required>
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

        $('#nominal').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            let formattedValue = new Intl.NumberFormat("id-ID").format(value);
            $(this).val(formattedValue);
        });
        $(".format-rupiah").each(function() {
            const nominal = $(this).text();
            const formattedNominal = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
            }).format(nominal);
            $(this).text(formattedNominal);
        });

        $('#btnAddModal').on('click', function() {
        $('#addUomModal').show();
            $('#addEditBudgetActivityForm')[0].reset();
            $('#addUomModalLabel').text('Add Budget Activity');
        });

        $('#addEditBudgetActivityForm').on('submit', function(e) {
            e.preventDefault();

            let nominalField = $('#nominal');
            let formattedNominal = nominalField.val();
            let cleanNominal = formattedNominal.replace(/\./g, '');

            let data = new FormData(this);
            data.set('nominal', cleanNominal);

            $.ajax({
                url: '/frontend/budget-activity/save',
                type: 'POST',
                data: data,
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
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred. Please try again later.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

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
                        url: '/frontend/budget-activity/delete/' + id,
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
                                title: 'Error!',
                                text: 'An error occurred. Please try again later.',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
{% endblock %}
