{% extends 'layouts/main.volt' %}

{% block content %}
<div class="container">
    <div class="card">
        <div class="card-body">
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
                                    <h5 class="card-title fw-bold">{{ activity['plot_code'] }}</h5>
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
                            </div>
                        </div>
                    {% endfor %}
                {% endif %}
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

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
    });
</script>
{% endblock %}
