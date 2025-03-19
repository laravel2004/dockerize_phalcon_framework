{% extends 'layouts/main.volt' %}

{% block content %}
<div class="container">
    <div class="row mb-3">
        <form class="col-10" method="GET" action="/frontend/absensi">
            <div class="row">
                <div class="col-md-10">
                    <input type="text" name="date_range" id="date_range" class="form-control text-center" placeholder="Select Date Range">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </div>
        </form>
        <div class=" col-2">
            <button class="btn btn-primary w-100" id="print-btn">Print</button>
        </div>
    </div>

    <div class="card" id="print-area">
        <div class="text-center my-3">
            <h5 class="fw-bold">REKAP ABSENSI PEKERJA LAHAN TS KAULON PLANT CANE</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 200px;">Name</th>
                            {% for date in dates %}
                                <th>{{ date }}</th>
                            {% endfor %}
                        </tr>
                    </thead>
                    <tbody>
                        {% for res in data %}
                            <tr>
                                <td>{{ res['nama'] }}</td>
                                {% for attendance in res['attendance'] %}
                                    <td>{{ attendance }}</td>
                                {% endfor %}
                            </tr>
                        {% endfor %}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
        $('#print-btn').click(function() {
            window.print();
        });

        $('#date_range').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('#date_range').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        });

        $('#date_range').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });

    });
</script>

<style>
/* CSS untuk hanya mencetak card */
    @media print {
        body {
            visibility: hidden;
        }

        #print-area {
            visibility: visible !important;
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 10px !important;
        }

        @page {
            size: A4 landscape !important;
            margin: 5mm !important;
        }

        .page-break {
            display: block !important;
            page-break-before: always !important;
        }

        header, footer, nav, .btn, .no-print {
            display: none !important;
        }
    }
</style>

{% endblock %}
