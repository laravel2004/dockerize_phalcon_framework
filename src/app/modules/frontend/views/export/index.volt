{% extends 'layouts/main.volt' %}

{% block content %}
<div class="container">
    <h1>Data Of Plot</h1>

    <form method="GET" action="/frontend/export" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by code plot">
            <button class="btn btn-primary" type="submit">Cari</button>
        </div>
    </form>

    <div class="list-group">
        {% for plot in plots %}
        <div class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1"># {{ plot.code }}</h5>
                <p class="mb-1">Project : {{ plot.project.project }}</p>
                <small>Project Code: {{ plot.project.code }}</small>
            </div>
            <button class="btn btn-info open-modal" data-id="{{ plot.id }}" data-code="{{ plot.code }}">Lihat</button>
        </div>
        {% else %}
        <p class="text-muted">Tidak ada data ditemukan.</p>
        {% endfor %}
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="plotModal" tabindex="-1" aria-labelledby="plotModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="plotModalLabel">Pilih Periode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="filterForm">
                    <input type="hidden" name="plot_id" id="plotId">
                    <div class="mb-3">
                        <label for="startDate" class="form-label">Start Date <small class="text-danger fw-semibold" >*calculated from the start date</small> </label>
                        <input type="date" class="form-control" name="start_date" id="startDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="endDate" class="form-label">End Date <small class="text-danger fw-semibold" >*calculated from the start date</small> </label>
                        <input type="date" class="form-control" name="end_date" id="endDate" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Lihat Biaya</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $(".open-modal").click(function () {
            let plotId = $(this).data("id");
            let plotCode = $(this).data("code");

            $("#plotId").val(plotId);
            $("#plotModalLabel").text("Pilih Periode untuk #" + plotCode);

            $("#plotModal").modal("show");
        });

        $("#filterForm").submit(function (event) {
            event.preventDefault();

            let plotId = $("#plotId").val();
            let startDate = $("#startDate").val();
            let endDate = $("#endDate").val();

            window.location.href = "/export/cost/" + plotId + "?start_date=" + startDate + "&end_date=" + endDate;
        });
    });
</script>
{% endblock %}
