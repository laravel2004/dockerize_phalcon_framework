<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->partial('components/header') ?>
    <?= $this->assets->outputCss() ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="<?= $this->security->getToken() ?>">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
</head>

<body>
    <div id="app">
        <?= $this->partial('components/sidebar') ?>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-md-6 order-md-1 order-last">
                            <h3><?= $title ?></h3>
                            <p class="text-subtitle text-muted"><?= $subtitle ?></p>
                        </div>
                    </div>
                </div>
                
<div class="container my-4">
    <div class="card shadow-sm p-4">
        <div class="text-center mb-3">
            <h5 class="fw-bold">Payroll Worker Aktivitas <span class="text-danger"><?= $data->ActivityLog->activitySetting->name ?></span></h5>
        </div>
        <div class="border p-4" id="print-area">
            <div class="d-flex justify-content-between align-items-center">
                <img src="<?= $this->url->get('img/logo.png') ?>" alt="logo" class="img-fluid" style="width: 150px; height:100px;">
                <h3 class="fw-bold">N1</h3>
            </div>
            <div class="d-flex flex-column my-3">
                <div><strong>Nama:</strong> <?= $data->WorkerData->name ?></div>
                <div><strong>Lama Pengerjaan:</strong> <?= $data->ActivityLog->time_of_work ?> Hari (<?= $data->ActivityLog->start_date ?> - <?= $data->ActivityLog->end_date ?>)</div>
                <div><strong>Luasan Total :</strong> <?= $data->ActivityLog->plot->wide ?> Hectare</div>
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
                    <tr>
                        <td><?= $data->ActivityLog->activitySetting->name ?></td>
                        <td><?= $data->unit ?> Hari x <span class="format-rupiah"><?= $data->cost ?></span></td>
                        <td class="format-rupiah"><?= $data->total_cost ?></td>
                    </tr>
                    <tr>
                        <th colspan="2">Total</th>
                        <th class="format-rupiah"><?= $data->total_cost ?></th>
                    </tr>
                </tbody>
            </table>

            <p class="fw-bold">Terbilang: <span class="text-uppercase"><?= $terbilangTotal ?></span></p>

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

            </div>
            <?= $this->partial('components/footer') ?>
        </div>
    </div>
</body>
<?= $this->partial('components/scripts') ?>
<?= $this->assets->outputJs() ?>

</html>