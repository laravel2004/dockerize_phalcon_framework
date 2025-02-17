{% extends 'layouts/main.volt' %}

{% block content %}
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row gap-4 justify-content-md-between align-items-start align-items-md-center mb-3">
                <form method="GET" action="/frontend/type-activity" class="d-flex flex-column flex-sm-row mb-3 w-100 mb-md-0">
                    <input type="text" name="search" class="form-control w-100 me-0 me-sm-2 mb-2 mb-sm-0" placeholder="Search by name" value="{{ search }}">
                    <button type="submit" class="btn btn-secondary w-sm-auto">Search</button>
                </form>
                <button type="button" class="btn btn-primary w-30 w-md-auto" id="btnAddModal" data-bs-toggle="modal" data-bs-target="#addTypeActivityModal">Add</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Cost</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {% if page.items|length == 0 %}
                        <tr>
                            <td colspan="4">No data available</td>
                        </tr>
                        {% else %}
                        {% for type in page.items %}
                        <tr>
                            <td>{{ loop.index + ((page.current - 1) * page.limit) }}</td>
                            <td>{{ type.name_type }}</td>
                            <td class="format-rupiah">{{ type.cost }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn"
                                    data-id="{{ type.id }}"
                                    data-name="{{ type.name_type }}"
                                    data-cost="{{ type.cost }}">
                                    Edit
                                </button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ type.id }}">Delete</button>
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
<div class="modal fade" id="addTypeActivityModal" tabindex="-1" aria-labelledby="addTypeActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTypeActivityModalLabel">Add/Edit Type Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEditTypeActivityForm">
                    <input type="hidden" name="id" id="uomId">
                    <div class="mb-3">
                        <label for="name_type" class="form-label">Name</label>
                        <input placeholder="Enter name type" name="name_type" type="text" class="form-control" id="name_type" required>
                    </div>
                    <div class="mb-3">
                        <label for="cost" class="form-label">Cost</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input placeholder="10.000" name="cost" type="text" class="form-control" id="cost" required>
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
        $('#addEditTypeActivityForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: '/frontend/type-activity/save',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        confirmButtonText: 'OK',
                        timer: 1500
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
            });
        })

        $('#btnAddModal').click(function() {
            $('#addEditTypeActivityForm')[0].reset();
            $('#uomId').val('');
            $('#addTypeActivityModalLabel').text('Add Type Activity');
        });

        $('.edit-btn').click(function() {
            $('#uomId').val($(this).data('id'));
            $('#name_type').val($(this).data('name'));
            let costValue = $(this).data('cost');
            costValue = parseFloat(costValue).toFixed(2).replace(/\.00$/, '');
            $('#cost').val(costValue);
            $('#addTypeActivityModalLabel').text('Edit Type Activity');
            $('#addTypeActivityModal').modal('show');
        });

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(number);
        }

        $('.format-rupiah').each(function() {
            $(this).text(formatRupiah($(this).text()));
        });

        $('#cost').on('input', function() {
            var inputVal = $(this).val().replace(/[^\d]/g, '');
            if (inputVal) {
                inputVal = inputVal.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                $(this).val(inputVal);
            }
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
                        url: `/frontend/type-activity/delete/${id}`,
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