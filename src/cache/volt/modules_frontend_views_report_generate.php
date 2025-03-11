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
            <h5 class="fw-bold">Detail Aktifitas Tebu Sendiri <span class="text-danger">FTM-45828</span></h5>
        </div>
        <div class="border p-4" id="print-area">
            <div class="d-flex justify-content-between align-items-center">
                <img src="<?= $this->url->get('img/logo.png') ?>" alt="logo" class="img-fluid" style="width: 150px; height:100px;">
                <h3 class="fw-bold">N1</h3>
            </div>

            <div class="text-center fw-bold">
                Periode: <?= $dateRange[0] ?> - <?= $dateRange[1] ?>
            </div>

            <div>
                <?php if ($this->length($data) == 0) { ?>
                <?php } else { ?>
                    <?php foreach ($data as $item) { ?>
                        <div class="mt-5"></div>
                        <div class="d-flex flex-column my-3">
                            <div><strong>Petak Tanah (Plot):</strong> <?= $item['plot']['code'] ?></div>
                            <div><strong>Luasan Plot:</strong> <?= $item['plot']['wide'] ?> Hectare</div>
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
                                <?php if ($this->length($item['activityLogs']) == 0) { ?>
                                    <tr>
                                        <td colspan="3">No data available</td>
                                    </tr>
                                <?php } else { ?>
                                    <?php foreach ($item['activityLogs']['activity'] as $activityLog) { ?>
                                        <tr>
                                            <td><?= $activityLog['name'] ?></td>
                                            <td><?= $activityLog['unit'] ?> <?= $activityLog['uom'] ?></td>
                                            <td class="format-rupiah"><?= $activityLog['total'] ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th class="format-rupiah"><?= $item['activityLogs']['total'] ?></th>
                                </tr>
                            </tbody>
                        </table>
                    <?php } ?>
                <?php } ?>
            </div>

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