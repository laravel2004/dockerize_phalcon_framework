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
                <img src="<?= $this->url->get('img/logo.png') ?>" alt="logo" class="img-fluid" style="width: 150px; height:100px;">
                <h3 class="fw-bold">N1</h3>
            </div>

            <div class="text-center fw-bold">
                <?php if (($dateRange)) { ?>
                    Periode: <?= $dateRange[0] ?> - <?= $dateRange[1] ?>
                <?php } ?>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered mt-5 text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Labor</th>
                            <th>Time</th>
                            <th>Cost / Time</th>
                            <th>Cost</th>
                            <th>Total</th>
                            <th>Area(Ha)</th>
                            <th>Id Field</th>
                            <th>Project Code</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $item) { ?>
                            <?php foreach ($item['activityLogs']['activity'] as $activityLog) { ?>
                                <tr>
                                    <th><?= $activityLog['date'] ?></th>
                                    <td><?= $activityLog['name'] ?></td>
                                    <td><?= $activityLog['unit'] ?> <?= $activityLog['uom'] ?></td>
                                    <td class="format-rupiah"><?= $activityLog['cost'] ?></td>
                                   <td class="format-rupiah"><?= $activityLog['total'] ?></td>
                                    <?php if (($activityLog['row_plot'] != 0)) { ?>
                                        <td class="format-rupiah" rowspan="<?= $activityLog['row_plot'] ?>" ><?= $item['activityLogs']['total'] ?></td>
                                    <?php } ?>
                                    <?php if (($activityLog['row_plot'] != 0)) { ?>
                                        <td rowspan="<?= $activityLog['row_plot'] ?>" ><?= $item['plot']['wide'] ?> Ha</td>
                                    <?php } ?>
                                    <?php if (($activityLog['row_plot'] != 0)) { ?>
                                        <td rowspan="<?= $activityLog['row_plot'] ?>" ><?= $activityLog['plot'] ?></td>
                                    <?php } ?>
                                    <?php if (($activityLog['row_project'] != 0)) { ?>
                                        <td rowspan="<?= $activityLog['row_project'] ?>" ><?= $activityLog['project_code'] ?></td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                            <tr>
                            </tr>
                        <?php } ?>
                            <tr>
                                <th colspan="3">Grand Total</th>
                                <th colspan="8" class="format-rupiah"><?= $totalCost ?></th>
                            </tr>
                            <tr>
                                <th colspan="3">Terbilang</th>
                                <th colspan="8" class="format-rupiah"><?= $terbilangTotal ?></th>
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


            </div>
            <?= $this->partial('components/footer') ?>
        </div>
    </div>
</body>
<?= $this->partial('components/scripts') ?>
<?= $this->assets->outputJs() ?>

</html>