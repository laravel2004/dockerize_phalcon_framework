{% extends 'layouts/main.volt' %}

{% block content %}

<style>
    .table-responsive {
        -webkit-overflow-scrolling: touch;
        overflow-x: auto;
        white-space: nowrap;
    }
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

<div class="container my-4">
    <div class="card shadow-sm p-4" id="print-area">
        <div class="text-center mb-3">
            <h5 class="fw-bold">REKAP KEGIATAN LAHAN TS KAULON BIBIT PLANT CANE</h5>
        </div>
        <div class="border p-4">
            <div class="d-flex justify-content-between align-items-center">
                <img src="{{ url.get('img/logo.png') }}" alt="logo" class="img-fluid" style="width: 150px; height:100px;">
                <h3 class="fw-bold">N1</h3>
            </div>

            <div class="text-center fw-bold">
                {% if(dateRange)%}
                    Periode: {{ dateRange[0] }} - {{ dateRange[1] }}
                {% endif %}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered mt-5 text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Labor</th>
                            <th>Id Field</th>
                            <th>Area(Ha)</th>
                            <th>Project Code</th>
                            <th>Time</th>
                            <th>Cost / Time</th>
                            <th>Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        {% for item in data%}
                            {% for activityLog in item['activityLogs']['activity']  %}
                                <tr>
                                    <th>{{ activityLog['date'] }}</th>
                                    <td>{{ activityLog['name'] }}</td>
                                    <td>{{ activityLog['plot'] }}</td>
                                    <td>{{ item['plot']['wide'] }} Ha</td>
                                    <td>{{ activityLog['project_code'] }}</td>
                                    <td>{{ activityLog['unit'] }} {{ activityLog['uom'] }}</td>
                                    <td class="format-rupiah">{{ activityLog['cost'] }}</td>
                                   <td class="format-rupiah">{{ activityLog['total'] }}</td>
                                </tr>
                            {% endfor %}
                            <tr>
                                <th colspan="7">Total</th>
                                <th class="format-rupiah">{{ item['activityLogs']['total'] }}</th>
                            </tr>
                        {% endfor %}
                            <tr>
                                <th colspan="2">Grand Total</th>
                                <th colspan="7" class="format-rupiah">{{ totalCost }}</th>
                            </tr>
                            <tr>
                                <th colspan="2">Terbilang</th>
                                <th colspan="7" class="format-rupiah">{{ terbilangTotal }}</th>
                            </tr>
                    </tbody>
                </table>
            </div>
            <div class="page-break"></div>
            <div class="p-4">
                <div class="row text-center mt-2 justify-content-center">
                    <p><strong>Diajukan oleh:</strong></p>
                    <div class="col-5">
                        <br /><br /><br /><br />
                        <div class="d-flex justify-content-center">
                            <span class="border-bottom border-dark border-1 w-75"><strong>Fuat Agus Setiawan</strong></span>
                        </div>
                        <p>Petugas TS</p>
                    </div>
                    <div class="col-5">
                        <br /><br /><br /><br />
                        <div class="d-flex justify-content-center">
                            <span class="border-bottom border-dark border-1 w-75"><strong>Praphan Wetbanphot</strong></span>
                        </div>
                        <p>Sugarcane Supply Officer</p>
                    </div>
                </div>

                <div class="row text-center mt-2 justify-content-center">
                    <p><strong>Diketahui oleh:</strong></p>
                    <div class="col-5">
                        <br /><br /><br /><br />
                        <div class="d-flex justify-content-center">
                            <span class="border-bottom border-dark border-1 w-75"><strong>Rahmat Jainuri</strong></span>
                        </div>
                        <p>Section Head</p>
                    </div>
                    <div class="col-5">
                        <br /><br /><br /><br />
                        <div class="d-flex justify-content-center">
                            <span class="border-bottom border-dark border-1 w-75"><strong>Syahrul Istad</strong></span>
                        </div>
                        <p>Plat Manager Dept</p>
                    </div>
                </div>

                <div class="row text-center mt-2 justify-content-center">
                    <p><strong>Disetujui oleh:</strong></p>
                    <div class="col-5">
                        <br /><br /><br /><br />
                        <div class="d-flex justify-content-center">
                            <span class="border-bottom border-dark border-1 w-75"><strong>Apichart Sreewarome</strong></span>
                        </div>
                        <p>Plantation Manager</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <button class="btn btn-primary" id="print-btn">Print PDF</button>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#print-btn').click(function() {
            window.print();
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
