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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
                            <?php foreach ($dates as $date) { ?>
                                <th><?= $date ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $res) { ?>
                            <tr>
                                <td><?= $res['nama'] ?></td>
                                <?php foreach ($res['attendance'] as $attendance) { ?>
                                    <td><?= $attendance ?></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
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


            </div>
            <?= $this->partial('components/footer') ?>
        </div>
    </div>
</body>
<?= $this->partial('components/scripts') ?>
<?= $this->assets->outputJs() ?>

</html>