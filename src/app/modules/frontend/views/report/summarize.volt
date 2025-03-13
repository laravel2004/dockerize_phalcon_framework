{% extends 'layouts/main.volt' %}

{% block content %}
<div class="container my-4">
    <div class="card shadow-sm p-4">
        <div class="text-center mb-3">
            <h5 class="fw-bold">Detail Aktifitas Tebu Sendiri <span class="text-danger">FTM-45828</span></h5>
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
            <div>
                {% if data|length == 0 %}
                {% else %}
                    {% for item in data %}
                        <div class="mt-5"></div>
                        <div class="d-flex flex-column my-3">
                            <div><strong>Petak Tanah (Plot):</strong> {{ item['plot']['code'] }}</div>
                            <div><strong>Luasan Plot:</strong> {{ item['plot']['wide'] }} Hectare</div>
                        </div>

                        <table class="table table-bordered text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Aktivitas / Material</th>
                                    <th>Jumlah</th>
                                    <th>Jumlah Uang</th>
                                </tr>
                            </thead>
                            <tbody>
                                {% if item['activityLogs']|length == 0 %}
                                    <tr>
                                        <td colspan="3">No data available</td>
                                    </tr>
                                {% else %}
                                    {% for activityLog in item['activityLogs']['activity']  %}
                                        <tr>
                                            <td>{{ activityLog['name'] }}</td>
                                            <td>{{ activityLog['unit'] }} {{ activityLog['uom'] }}</td>
                                            <td class="format-rupiah">{{ activityLog['total'] }}</td>
                                        </tr>
                                    {% endfor %}
                                {% endif %}
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th class="format-rupiah">{{ item['activityLogs']['total'] }}</th>
                                </tr>
                            </tbody>
                        </table>
                    {% endfor %}
                {% endif %}
            </div>

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
