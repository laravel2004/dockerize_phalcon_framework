{% extends 'layouts/main.volt' %}

{% block content %}
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

    <!-- Activity Log Table -->
    <div class="card">
        <div class="card-header">
            <h5>Latest Activity Logs</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Activity</th>
                            <th>Number of Workers</th>
                            <th>Total Cost</th>
                            <th>Description</th>
                            <th>Code Petak</th>
                            <th>Code Project</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Material Transport</td>
                            <td>5</td>
                            <td>Rp. 500.000</td>
                            <td>Transport materials to site</td>
                            <td>P123</td>
                            <td>A001</td>
                        </tr>
                        <tr>
                            <td>Foundation Setup</td>
                            <td>10</td>
                            <td>Rp. 1.000.000</td>
                            <td>Lay concrete foundation</td>
                            <td>P124</td>
                            <td>A001</td>
                        </tr>
                        <tr>
                            <td>Wiring Installation</td>
                            <td>8</td>
                            <td>Rp. 1.500.000</td>
                            <td>Install wiring for electrical system</td>
                            <td>P125</td>
                            <td>B002</td>
                        </tr>
                        <tr>
                            <td>Plumbing Work</td>
                            <td>6</td>
                            <td>Rp. 4.550.000</td>
                            <td>Setup water pipelines</td>
                            <td>P126</td>
                            <td>C003</td>
                        </tr>
                        <tr>
                            <td>Roof Installation</td>
                            <td>7</td>
                            <td>Rp. 230.000</td>
                            <td>Install roof panels</td>
                            <td>P127</td>
                            <td>B002</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{% endblock %}
