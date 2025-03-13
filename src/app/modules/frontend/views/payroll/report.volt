{% extends 'layouts/main.volt' %}

{% block content %}
<div class="container my-4">
    <div class="card shadow-sm p-4">
        <div class="text-center mb-3">
            <h5 class="fw-bold">Payroll Aktivitas Worker</h5>
        </div>
        <div class="border p-4" id="print-area">
            <div class="d-flex justify-content-between align-items-center">
                <img src="{{ url.get('img/logo.png') }}" alt="logo" class="img-fluid" style="width: 150px; height:100px;">
                <h3 class="fw-bold">N1</h3>
            </div>
            <div class="text-center fw-bold">
                {% if(dateRange)%}
                    Periode: {{ dateRange[0] }} - {{ dateRange[1] }}
                {% endif %}
            </div>
            <div class="d-flex flex-column my-3">
                <div><strong>Nama:</strong> {{ data[0].WorkerData.name }}</div>
                <div><strong>Lama Pengerjaan:</strong> {{ longWorker }} Hari</div>
                <div><strong>Luasan Total :</strong> {{ wideTotal }} Hectare</div>
            </div>

            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th>Aktivitas</th>
                        <th>Hitungan Hari</th>
                        <th>Jumlah Uang</th>
                    </tr>
                </thead>
                <tbody>
                    {% if data|length == 0 %}
                        <tr>
                            <td colspan="3">No data available</td>
                        </tr>
                    {% else %}
                        {% for worker in data %}
                            <tr>
                                <td>{{ worker.ActivityLog.activitySetting.name }}</td>
                                <td>{{ worker.unit }} Hari x <span class="format-rupiah">{{ worker.cost }}</span></td>
                                <td class="format-rupiah">{{ worker.total_cost }}</td>
                            </tr>
                        {% endfor %}
                    {% endif %}
                    <tr>
                        <th colspan="2">Total</th>
                        <th class="format-rupiah">{{ totalCost }}</th>
                    </tr>
                </tbody>
            </table>

            <p class="fw-bold">Terbilang: <span class="text-uppercase">{{ terbilangTotal }}</span></p>

            <div class="row justify-content-center align-items-center">
                <div class="col-6 text-center">
                    <p><strong>Dikirim oleh:</strong></p>
                    <br /><br /><br />
                    <div class="d-flex justify-content-center">
                        <hr class="border border-dark border-1 w-50">
                    </div>
                </div>
                <div class="col-6 text-center">
                    <p><strong>Disaksikan oleh:</strong></p>
                    <br /><br /><br />
                    <div class="d-flex justify-content-center">
                        <hr class="border border-dark border-1 w-50">
                    </div>
                </div>
            </div>

            <p class="text-center mt-4">&copy; On Farm PT Rejoso Manis Indo</p>
        </div>
    </div>

    <div class="text-center mt-4">
        <button class="btn btn-primary" id="print-btn">Print PDF</button>
    </div>
</div>

<style>
    @media print {
        body {
            visibility: hidden;
        }

        .page-break {
            page-break-before: always;
        }

        #print-area {
            visibility: visible;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        @page {
            size: A4;
            margin: 0;
        }

        header, footer, nav, .btn, .no-print {
            display: none !important;
        }
    }
</style>

<script>
    $(document).ready(function() {
        $('#print-btn').click(function() {
            var printContent = document.getElementById('print-area').innerHTML;
            var originalContent = document.body.innerHTML;
            document.body.innerHTML = '<div class="container">' + printContent + '</div>';
            window.print();
            document.body.innerHTML = originalContent;
            location.reload();
        });
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
