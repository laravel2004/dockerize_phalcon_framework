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
    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <?php if (($this->length($activities) === 0)) { ?>
                    <div class="col-md-12">
                        <div class="alert alert-warning" role="alert">
                            No data available
                        </div>
                    </div>
                <?php } else { ?>
                    <?php foreach ($activities as $activity) { ?>
                        <div class="col-md-4 d-flex align-items-stretch">
                            <div class="card shadow-lg border-0 w-100">
                                <div class="card-body text-center">
                                    <h5 class="card-title fw-bold"><?= $activity['plot_code'] ?></h5>
                                    <hr class="my-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-1">Budget Cost:</p>
                                        <strong class="text-success format-rupiah"><?= $activity['budget_cost'] ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-1">Actual Cost:</p>
                                        <strong class="text-danger format-rupiah"><?= $activity['actual_cost'] ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-1">Budget Labor:</p>
                                        <strong class="text-success format-rupiah"><?= $activity['budget_cost_activity'] ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-1">Actual Labor:</p>
                                        <strong class="text-danger format-rupiah"><?= $activity['actual_cost_activity'] ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-1">Budget Factor:</p>
                                        <strong class="text-success format-rupiah"><?= $activity['budget_cost_material'] ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-1">Actual Factor:</p>
                                        <strong class="text-danger format-rupiah"><?= $activity['actual_cost_material'] ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-1">Status Budget:</p>
                                        <strong class="text-warning format-rupiah"><?= $activity['status'] ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

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