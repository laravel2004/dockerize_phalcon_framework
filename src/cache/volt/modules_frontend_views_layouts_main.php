a:3:{i:0;s:1709:"<!DOCTYPE html>
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
                ";s:7:"content";a:3:{i:0;a:4:{s:4:"type";i:357;s:5:"value";s:22:"
                    ";s:4:"file";s:58:"/var/www/html/app/modules/frontend/views/layouts/main.volt";s:4:"line";i:39;}i:1;a:4:{s:4:"type";i:359;s:4:"expr";a:4:{s:4:"type";i:350;s:4:"name";a:4:{s:4:"type";i:265;s:5:"value";s:7:"content";s:4:"file";s:58:"/var/www/html/app/modules/frontend/views/layouts/main.volt";s:4:"line";i:39;}s:4:"file";s:58:"/var/www/html/app/modules/frontend/views/layouts/main.volt";s:4:"line";i:39;}s:4:"file";s:58:"/var/www/html/app/modules/frontend/views/layouts/main.volt";s:4:"line";i:40;}i:2;a:4:{s:4:"type";i:357;s:5:"value";s:18:"
                ";s:4:"file";s:58:"/var/www/html/app/modules/frontend/views/layouts/main.volt";s:4:"line";i:40;}}i:1;s:203:"
            </div>
            <?= $this->partial('components/footer') ?>
        </div>
    </div>
</body>
<?= $this->partial('components/scripts') ?>
<?= $this->assets->outputJs() ?>

</html>";}