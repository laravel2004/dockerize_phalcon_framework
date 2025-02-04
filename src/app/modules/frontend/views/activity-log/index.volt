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
                    <form method="GET" action="/frontend/activity-log" class="d-flex flex-column flex-sm-row mb-3 w-100 mb-md-0">
                        <input type="text" name="search" class="form-control w-100 me-0 me-sm-2 mb-2 mb-sm-0" placeholder="Search by name" value="{{ search }}">
                        <button type="submit" class="btn btn-secondary w-sm-auto">Search</button>
                    </form>
                    <a href="{{ url.get('/frontend/activity-log/create') }}" class="btn btn-primary">Add</a>
                </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Plot Code</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Time of Work (day/hour)</th>
                            <th>Type</th>
                            <th>Date </th>
                            <th>Cost</th>
                            <th>Total Cost</th>
                            <th>Total Worker</th>
                            <th>Image All</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {% if page.items|length == 0 %}
                        <tr>
                            <td colspan="12">No data available</td>
                        </tr>
                        {% else %}
                            {% for activityLog in page.items %}
                            <tr>
                                <td>{{ loop.index + ((page.current - 1) * page.limit) }}</td>
                                <td>{{ activityLog.plot_code }}</td>
                                <td>{{ activityLog.activity_setting_name }}</td>
                                <td>{{ activityLog.description }}</td>
                                <td>{{ activityLog.time_of_work ? activityLog.time_of_work : '' }} {{ activityLog.activity_setting_type }} </td>
                                <td>{{ activityLog.activity_setting_type }}</td>
                                <td class="date-range">{{ activityLog.start_date }} - {{ activityLog.end_date }}</td>
                                <td class="cost" data-value="{{ activityLog.cost }}">{{ activityLog.cost }} / {{ activityLog.activity_setting_type }}</td>
                                <td class="total-cost" data-value="{{ activityLog.total_cost }}">{{ activityLog.total_cost }}</td>
                                <td>{{ activityLog.total_worker }}</td>
                                <td style="width: 500px; max-width: 600px;">
                                    {% if activityLog.image is not empty %}
                                        {% set imagePaths = activityLog.image|json_decode %}

                                        {% if imagePaths|length > 0 %}
                                            <div class="d-flex flex-column gap-3">
                                                {% for img in imagePaths %}
                                                    <div class="w-100 text-center">
                                                        <img src="/{{ img }}" alt="Image" class="img-fluid rounded border shadow-sm"
                                                            style="width: 400px; height: auto; object-fit: cover;">
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
                                    <button class="btn btn-danger  btn-sm delete-btn" data-id="{{ activityLog.id }}">Delete</button>
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

<script>
    $(document).ready(function() {
            function formatDateToYYYYMMDD(dateString) {
                if (!dateString) return "";
                const date = new Date(dateString);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, "0");
                const day = String(date.getDate()).padStart(2, "0");
                return `${year}-${month}-${day}`;
            }

            // Ubah format tanggal di setiap baris tabel
            $("td.date-range").each(function () {
                const dates = $(this).text().split(" - ");
                if (dates.length === 2) {
                    const formattedStartDate = formatDateToYYYYMMDD(dates[0]);
                    const formattedEndDate = formatDateToYYYYMMDD(dates[1]);
                    $(this).text(`${formattedStartDate} - ${formattedEndDate}`);
                }
            });

        function formatRupiah(number, type) {
                const formattedNumber = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(number);

                if (type == null) {
                    return `${formattedNumber}`;
                }
                return `${formattedNumber} / ${type}`;
            }

            // Apply the format to cost and total cost columns
            $('.cost').each(function() {
                const value = $(this).data('value');
                const type = $(this).closest('tr').find('td:eq(5)').text();
                $(this).text(formatRupiah(value, type));
            });

            $('.total-cost').each(function() {
                const value = $(this).data('value');
                const type = $(this).closest('tr').find('td:eq(5)').text();
                $(this).text(formatRupiah(value));
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
                        url: '/frontend/activity-log/delete/' + id,
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
