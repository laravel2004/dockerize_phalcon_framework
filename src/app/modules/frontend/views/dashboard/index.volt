{% extends 'layouts/main.volt' %}

{% block content %}
<style>
    .card {
        border-radius: 15px;
    }
    .card-footer {
        border-top: none;
        border-radius: 0 0 15px 15px;
    }
</style>

<div class="container my-4">
    <!-- Dashboard Cards Row -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="bi bi-briefcase-fill text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title">Projects</h5>
                    <p class="card-text">Total Projects: <strong>{{ project }}</strong></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="bi bi-box-seam text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title">Supporting Materials</h5>
                    <p class="card-text">Logs: <strong>{{ supportingMaterial }}</strong></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="bi bi-clipboard-data-fill text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title">Activity Logs</h5>
                    <p class="card-text">Total Logs: <strong>{{ activityLog }}</strong></p>
                </div>
            </div>
        </div>
    </div>
    <div>
        <h4 class="fw-bold">Current Actual Budget</h4>
        <select name="project_id" id="project_id" class="form-control text-center my-4">
            <option value="">All Project</option>
                {% for project in projects %}
                    <option value="{{ project.id }}" {% if project.id == project_id %}selected{% endif %}>
                        {{ project.project }} - {{ project.code }}
                    </option>
                {% endfor %}
        </select>
        <div class="row mb-4">
            {% if(activities|length  === 0)%}
                <div class="col-md-12">
                    <div class="alert alert-warning" role="alert">
                        No data available
                    </div>
                </div>
            {% else %}
                {% for activity in activities %}
                    <div class="col-md-4 d-flex align-items-stretch">
                        <div class="card shadow-lg border-0 w-100">
                            <div class="card-body text-center">
                                <h5 class="card-title fw-bold">{{ activity['activity_setting_name'] }}</h5>
                                <hr class="my-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="mb-1">Budget Cost:</p>
                                    <strong class="text-success format-rupiah">{{ activity['budget_cost'] }}</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="mb-1">Actual Cost:</p>
                                    <strong class="text-danger format-rupiah">{{ activity['actual_cost'] }}</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="mb-1">Budget Labor:</p>
                                    <strong class="text-success format-rupiah">{{ activity['budget_cost_activity'] }}</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="mb-1">Actual Labor:</p>
                                    <strong class="text-danger format-rupiah">{{ activity['actual_cost_activity'] }}</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="mb-1">Budget Factor:</p>
                                    <strong class="text-success format-rupiah">{{ activity['budget_cost_material'] }}</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="mb-1">Actual Factor:</p>
                                    <strong class="text-danger format-rupiah">{{ activity['actual_cost_material'] }}</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="mb-1">Status Budget:</p>
                                    <strong class="text-warning format-rupiah">{{ activity['status'] }}</strong>
                                </div>
                            </div>
                            <div class="card-footer bg-light text-center">
                                <a href="/frontend/dashboard/detail/{{ activity['id'] }}" class="btn btn-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                {% endfor %}
            {% endif %}
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="fw-bold">Statistic Budget Actual</h4>
            <div id="chart"></div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $('#project_id').change(function () {
            const project_id = $(this).val();
            window.location.href = `/frontend/dashboard?project_id=${project_id}`
//             console.log(project_id)
        })

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

        $.ajax({
            url: "/frontend/dashboard/data",
            method: "GET",
            dataType: "json",
            success: function (response) {
            console.log(response);
                var options = {
                    chart: {
                        type: 'line'
                    },
                    series: response.series,
                    xaxis: {
                        categories: response.categories
                    },
                    yaxis: {
                        labels: {
                            formatter: function (value) {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0
                                }).format(value);
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function (value) {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0
                                }).format(value);
                            }
                        }
                    }
                };

                var chart = new ApexCharts($("#chart")[0], options);
                chart.render();
            },
            error: function (xhr, status, error) {
                console.log("Error loading chart data:", error);
            }
        });

    });
</script>

{% endblock %}
