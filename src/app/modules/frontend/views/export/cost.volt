{% extends 'layouts/main.volt' %}

{% block content %}
<style>
    .table-responsive {
        -webkit-overflow-scrolling: touch;
        overflow-x: auto;
        white-space: nowrap;
    }
    .modal-body img {
        width: 100%;
        height: auto;
        margin-bottom: 10px;
    }
</style>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="card-title">Cost Details for Plot #{{ plotCode }}</h1>
            <p class="card-text">Period: {{ startDate }} - {{ endDate }} </p>
            <!-- Activity Logs -->
            <h3 class="mt-4">Activity Logs</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Activity</th>
                            <th>Description</th>
                            <th>Total Workers</th>
                            <th>Time of Work</th>
                            <th>Cost per Wages</th>
                            <th>Cost per (Day/Hour/Vendor)</th>
                            <th>Total Cost (Summary Project)</th>
                            <th>See Photo</th>
                        </tr>
                    </thead>
                    <tbody>
                        {% if activityLogs|length > 0 %}
                            {% set totalAllCost = 0 %}
                            {% for activityLog in activityLogs %}
                            {% set totalAllCost += activityLog.total_cost %}
                            <tr>
                                <td >{{ activityLog.start_date }}</td>
                                <td>{{ activityLog.activitySetting.name }} - {{ activityLog.activitySetting.description }}</td>
                                <td>{{ activityLog.description }}</td>
                                <td>{{ activityLog.total_worker }} Worker</td>
                                <td>{{ activityLog.time_of_work }} {{ activityLog.activitySetting.type }}</td>
                                <td class="cost_per_wage">{{ activityLog.cost }}</td>
                                <td class="cost_per_type">{{ activityLog.cost * activityLog.total_worker }} / {{ activityLog.activitySetting.type }}</td>
                                <td class="total_cost">{{ activityLog.total_cost }}</td>
                                <td>
                                    {% set imagePaths = activityLog.image|json_decode %}
                                    {% if imagePaths|length > 0 %}
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#photoModal" data-photo='{{ imagePaths|json_encode }}'>See Photo</button>
                                    {% else %}
                                        <span class="text-muted text-center">No Image</span>
                                    {% endif %}
                                </td>
                            </tr>
                            {% endfor %}
                        {% else %}
                            <tr><td colspan="9" class="text-center">No data available</td></tr>
                        {% endif %}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" class="text-end"><strong>Grand Total</strong></td>
                            <td id="grandTotalCost" class="text-secondary fw-semibold fs-4 "><strong>{{ totalAllCost }}</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Supporting Materials -->
            <h3 class="mt-4">Supporting Materials</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Material Name</th>
                            <th>Item Needed</th>
                            <th>Conversion UoM</th>
                            <th>See Photo</th>
                        </tr>
                    </thead>
                    <tbody>
                        {% if supportingMaterials|length > 0 %}
                            {% for supportingMaterial in supportingMaterials %}
                            <tr>
                                <td>{{ supportingMaterial.date }}</td>
                                <td>{{ supportingMaterial.material.name }}</td>
                                <td>{{ supportingMaterial.item_needed }} {{ supportingMaterial.uom }}</td>
                                <td>{{ supportingMaterial.conversion_of_uom_item }} {{ supportingMaterial.uom }} / {{ supportingMaterial.material.uom }}</td>
                                <td>
                                    {% set imagePaths = supportingMaterial.image|json_decode %}
                                    {% if imagePaths|length > 0 %}
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#photoModal" data-photo='{{ imagePaths|json_encode }}'>See Photo</button>
                                    {% else %}
                                        <span class="text-muted text-center">No Image</span>
                                    {% endif %}
                                </td>
                            </tr>
                            {% endfor %}
                        {% else %}
                            <tr><td colspan="5" class="text-center">No data available</td></tr>
                        {% endif %}
                    </tbody>
                </table>
            </div>

            <a href="/frontend/export/" class="btn btn-secondary mt-3">Back</a>
        </div>
    </div>
</div>

<!-- Modal for Viewing Photo -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">View Photos</h5>
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
    $(document).ready(function() {

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

        function formatRupiah(number) {
            if (!number || isNaN(number)) return "Rp 0";
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }

        function cleanNumber(text) {
            return parseFloat(text.replace(/[^0-9.-]+/g, '')) || 0;
        }

        $('.cost_per_wage').each(function() {
            let value = cleanNumber($(this).text());
            $(this).text(formatRupiah(value));
        });

        $('.cost_per_type').each(function() {
            let text = $(this).text().trim();
            let parts = text.split(" / ");
            if (parts.length === 2) {
                let formattedValue = formatRupiah(parts[0]);
                $(this).text(formattedValue + " / " + parts[1]);
            }
        });

        $('.total_cost').each(function() {
            let value = cleanNumber($(this).text());
            $(this).text(formatRupiah(value));
        });

        $('#grandTotalCost').text(formatRupiah(cleanNumber($('#grandTotalCost').text())));
    });
</script>
{% endblock %}
