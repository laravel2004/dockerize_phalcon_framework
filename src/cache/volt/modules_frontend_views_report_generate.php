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
    <for
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
                <table class="table fw-bold table-bordered mt-5 text-center">
                    <thead class="bg-primary">
                        <tr class="fw-bold">
                            <th class="text-dark">Date</th>
                            <th class="text-dark">Labor</th>
                            <th class="text-dark">Cost/Worker</th>
                            <th class="text-dark">Total Worker</th>
                            <th class="text-dark"> Total Time</th>
                            <th class="text-dark">Cost</th>
                            <th class="text-dark">Total</th>
                            <th class="text-dark">Area(Ha)</th>
                            <th class="text-dark">Id Field</th>
                            <th class="text-dark">Project Code</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $item) { ?>
                            <?php foreach ($item['activityLogs']['activity'] as $activityLog) { ?>
                                <tr>
                                    <th class="text-dark"><?= $activityLog['date'] ?></th>
                                    <td class="text-dark"><?= $activityLog['name'] ?></td>
                                    <td class="text-dark"><span class="format-rupiah text-dark"><?= $activityLog['cost'] ?></span></td>
                                    <td class="text-dark"><?= $activityLog['worker'] ?> Worker</td>
                                    <td class="text-dark"><?= $activityLog['unit'] ?> <?= $activityLog['uom'] ?></td>
                                   <td class="format-rupiah text-dark"><?= $activityLog['total'] ?></td>
                                    <?php if (($activityLog['row_plot'] != 0)) { ?>
                                        <td class="format-rupiah text-dark" rowspan="<?= $activityLog['row_plot'] ?>" ><?= $item['activityLogs']['total'] ?></td>
                                    <?php } ?>
                                    <?php if (($activityLog['row_plot'] != 0)) { ?>
                                        <td class="text-dark" rowspan="<?= $activityLog['row_plot'] ?>" ><?= $item['plot']['wide'] ?> Ha</td>
                                    <?php } ?>
                                    <?php if (($activityLog['row_plot'] != 0)) { ?>
                                        <td class="text-dark" rowspan="<?= $activityLog['row_plot'] ?>" ><?= $activityLog['plot'] ?></td>
                                    <?php } ?>
                                    <?php if (($activityLog['row_project'] != 0)) { ?>
                                        <td class="text-dark" rowspan="<?= $activityLog['row_project'] ?>" ><?= $activityLog['project_code'] ?></td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                            <tr>
                            </tr>
                        <?php } ?>
                            <tr>
                                <th class="text-dark bg-warning" colspan="3">Grand Total</th>
                                <th class="text-dark bg-warning format-rupiah" colspan="8"><?= $totalCost ?></th>
                            </tr>
                            <tr>
                                <th class="text-dark bg-success" colspan="3">Terbilang</th>
                                <th class="text-dark bg-success" colspan="8"><?= $terbilangTotal ?></th>
                            </tr>
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                <div class="row text-center mt-2 justify-content-center">
                    <p class="col-4"><strong>Diajukan oleh:</strong></p>
                    <p class="col-4"><strong>Diketahui oleh:</strong></p>
                    <p class="col-4"><strong>Disetujui oleh:</strong></p>
                    <div class="col-2">
                        <br /><br /><br /><br />
                        <div class="d-flex justify-content-center">
                            <span class="border-bottom border-dark border-1"><strong>Fuat Agus Setiawan</strong></span>
                        </div>
                        <p>Petugas TS</p>
                    </div>
                    <div class="col-2">
                        <br /><br /><br /><br />
                        <div class="d-flex justify-content-center">
                            <span class="border-bottom border-dark border-1"><strong>Praphan Wetbanphot</strong></span>
                        </div>
                        <p>Sugarcane Supply Officer</p>
                    </div>
                    <div class="col-2">
                        <br /><br /><br /><br />
                        <div class="d-flex justify-content-center">
                            <span class="border-bottom border-dark border-1"><strong>Rahmat Jainuri</strong></span>
                        </div>
                        <p>Section Head</p>
                    </div>
                    <div class="col-2">
                        <br /><br /><br /><br />
                        <div class="d-flex justify-content-center">
                            <span class="border-bottom border-dark border-1"><strong>Syahrul Istad</strong></span>
                        </div>
                        <p>Plant Manager Dept</p>
                    </div>
                    <div class="col-4">
                        <br /><br /><br /><br />
                        <div class="d-flex justify-content-center">
                            <span class="border-bottom border-dark border-1"><strong>Apichart Sreewarome</strong></span>
                        </div>
                        <p>Plantation Manager</p>
                    </div>
                </div>
            </div>
            <div class="page-break"></div>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <?php foreach ($data as $item) { ?>
                    <?php foreach ($item['activityLogs']['activity'] as $activityLog) { ?>
                        <?php if (!empty($activityLog['image'])) { ?>
                            <?php $imagePaths = json_decode($activityLog['image']); ?>
                            <?php if ($this->length($imagePaths) > 0) { ?>
                                    <?php foreach ($imagePaths as $img) { ?>
                                        <div class="text-center">
                                             <img src="/<?= $img ?>" alt="Image" class="img-fluid rounded border shadow-sm"
                                             style="height: auto; object-fit: cover;">
                                             <p class="text-secondary"><?= $activityLog['plot'] ?> (<?= $activityLog['start_date'] ?>)</p>
                                        </div>
                                    <?php } ?>
                            <?php } else { ?>
                                <span class="text-muted">No Image</span>
                            <?php } ?>
                        <?php } else { ?>
                            <span class="text-muted">No Image</span>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
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