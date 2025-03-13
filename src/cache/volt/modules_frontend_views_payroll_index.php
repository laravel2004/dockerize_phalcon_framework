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
                
<div class="container">
    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (($workerDataId)) { ?>
                <?php if (($status == 'paid')) { ?>
                <div class="row justify-content-end">
                    <div class="col-8 text-end">
                        <button  id="btn-summarize" class="btn btn-success fw-semibold">Report Payment</button>
                    </div>
                </div>
                <?php } ?>
            <?php } ?>
            <form method="GET" action="/frontend/payroll">
                <div class="row justify-content-start gap-2 gap-md-0 mt-5 ">
                    <div class="col-md-3">
                        <select name="status" id="project_id" class="form-control text-center">
                            <option value="">All Status</option>
                            <option value="unpaid">Belum Dibayar</option>
                            <option value="paid">Sudah Dibayar</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="worker_data_id" id="plot_id" class="form-control text-center">
                            <option value="" >All Worker Data</option>
                            <?php foreach ($worker as $workerData) { ?>
                                <option value="<?= $workerData->id ?>"><?= $workerData->name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="date_range" id="date_range" class="form-control text-center" placeholder="Select Date Range">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </div>
            </form>
            <hr class="mt-3" />
            <div>
                <?php if (($this->length($page->items) == 0)) { ?>
                    <div class="alert alert-warning" role="alert">
                        No data available
                    </div>
                <?php } else { ?>
                    <?php foreach ($page->items as $item) { ?>
                        <div class="container mt-3 rounded-2 border">
                            <div class="d-flex flex-column gap-3">
                                <div class="p-3 d-flex flex-row align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-light text-dark me-2">Worker</span>
                                            <h6 class="mb-0"><?= $item->WorkerData->name ?></h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong>Total Cost:</strong>
                                                <span class="text-danger format-rupiah"><?= $item->total_cost ?></span>
                                                <br>
                                                <small class="text-muted">Created: <?= $item->created_at ?></small>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Description:</strong>
                                                <p class="text-muted mb-0"><?= $item->unit ?> x <span class="format-rupiah"><?= $item->cost ?></span> = <span class="format-rupiah"><?= $item->total_cost ?></span></p>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Detail:</strong>
                                                <div>
                                                    <span class="badge bg-light text-dark me-2">Plot : <?= $item->ActivityLog->plot->code ?></span>
                                                    <span class="badge bg-light text-dark me-2">Activity : <?= $item->ActivityLog->activitySetting->name ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Payment:</strong>
                                                <div>
                                                    <?php if ($item->status == 0) { ?>
                                                        <span class="badge bg-danger text-white me-2">Belum Dibayar</span>
                                                    <?php } else { ?>
                                                        <span class="badge bg-success text-white me-2">Sudah Dibayar</span>
                                                    <?php } ?>
                                                    <span class="badge bg-light text-dark me-2">Payment : <?= $item->WorkerData->no_rekening ?>(<?= $item->WorkerData->nama_bank ?>)</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($item->status == 0) { ?>
                                        <button data-id="<?= $item->id ?>"  class="btn btn-success btn-bayar ms-3">Bayar</button>
                                    <?php } else { ?>
                                        <a href="<?= $this->url->get('/frontend/payroll/detail/') . $item->id ?>"  class="btn btn-warning btn-detail ms-3">Detail</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>

        </div>
        <div class="card-footer">
            <nav aria-label="Pagination">
                <ul class="pagination justify-content-center">
                    <?php if ($page->before) { ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page->before ?>">Previous</a>
                    </li>
                    <?php } ?>
                    <?php foreach (range(1, $page->total_pages) as $i) { ?>
                    <li class="page-item <?php if ($i == $page->current) { ?>active<?php } ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php } ?>
                    <?php if ($page->next) { ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page->next ?>">Next</a>
                    </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {


    $('.btn-bayar').click(function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to pay this worker?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, pay it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/frontend/payroll/update',
                    method: 'POST',
                    data: {
                        id: id
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(function() {
                            location.reload();
                        });
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseJSON.message,
                            confirmButtonText: 'OK'
                        });
                    },
                });
            }
        });
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

    $('#btn-summarize').click(function() {
        $query = window.location.search;
        window.location.href = '/frontend/payroll/report' + $query;
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


            </div>
            <?= $this->partial('components/footer') ?>
        </div>
    </div>
</body>
<?= $this->partial('components/scripts') ?>
<?= $this->assets->outputJs() ?>

</html>