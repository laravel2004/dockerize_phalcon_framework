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
</style>
<div class="container">
    <div class="card">
        <div class="card-header">
                <div class="d-flex flex-column flex-md-row gap-4 justify-content-md-between align-items-start align-items-md-center mb-3">
                    <form method="GET" action="/frontend/activity-log" class="d-flex flex-column flex-sm-row mb-3 w-100 mb-md-0">
                        <input type="text" name="search" class="form-control w-100 me-0 me-sm-2 mb-2 mb-sm-0" placeholder="Search by name" value="<?= $search ?>">
                        <button type="submit" class="btn btn-secondary w-sm-auto">Search</button>
                    </form>
                    <a href="<?= $this->url->get('/frontend/activity-log/create') ?>" class="btn btn-primary">Add</a>
                </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Plot Code</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Time of Work (day/hour)</th>
                            <th>Type</th>
                            <th>Date </th>
                            <th>Cost</th>
                            <th>Total Cost</th>
                            <th>Total Worker</th>
                            <th>Image All</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($this->length($page->items) == 0) { ?>
                        <tr>
                            <td colspan="12">No data available</td>
                        </tr>
                        <?php } else { ?>
                            <?php $v114146942739798027821iterator = $page->items; $v114146942739798027821incr = 0; $v114146942739798027821loop = new stdClass(); $v114146942739798027821loop->self = &$v114146942739798027821loop; $v114146942739798027821loop->length = count($v114146942739798027821iterator); $v114146942739798027821loop->index = 1; $v114146942739798027821loop->index0 = 1; $v114146942739798027821loop->revindex = $v114146942739798027821loop->length; $v114146942739798027821loop->revindex0 = $v114146942739798027821loop->length - 1; ?><?php foreach ($v114146942739798027821iterator as $activityLog) { ?><?php $v114146942739798027821loop->first = ($v114146942739798027821incr == 0); $v114146942739798027821loop->index = $v114146942739798027821incr + 1; $v114146942739798027821loop->index0 = $v114146942739798027821incr; $v114146942739798027821loop->revindex = $v114146942739798027821loop->length - $v114146942739798027821incr; $v114146942739798027821loop->revindex0 = $v114146942739798027821loop->length - ($v114146942739798027821incr + 1); $v114146942739798027821loop->last = ($v114146942739798027821incr == ($v114146942739798027821loop->length - 1)); ?>
                            <tr>
                                <td><?= $v114146942739798027821loop->index + (($page->current - 1) * $page->limit) ?></td>
                                <td><?= $activityLog->plot_code ?></td>
                                <td><?= $activityLog->activity_setting_name ?></td>
                                <td><?= $activityLog->description ?></td>
                                <td><?= ($activityLog->time_of_work ? $activityLog->time_of_work : '') ?> <?= $activityLog->activity_setting_type ?> </td>
                                <td><?= $activityLog->activity_setting_type ?></td>
                                <td class="date-range"><?= $activityLog->start_date ?> - <?= $activityLog->end_date ?></td>
                                <td class="cost" data-value="<?= $activityLog->cost ?>"><?= $activityLog->cost ?> / <?= $activityLog->activity_setting_type ?></td>
                                <td class="total-cost" data-value="<?= $activityLog->total_cost ?>"><?= $activityLog->total_cost ?></td>
                                <td><?= $activityLog->total_worker ?></td>
                                <td style="width: 500px; max-width: 600px;">
                                    <?php if (!empty($activityLog->image)) { ?>
                                        <?php $imagePaths = json_decode($activityLog->image); ?>

                                        <?php if ($this->length($imagePaths) > 0) { ?>
                                            <div class="d-flex flex-column gap-3">
                                                <?php foreach ($imagePaths as $img) { ?>
                                                    <div class="w-100 text-center">
                                                        <img src="/<?= $img ?>" alt="Image" class="img-fluid rounded border shadow-sm"
                                                            style="width: 400px; height: auto; object-fit: cover;">
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                            <span class="text-muted">No Image</span>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <span class="text-muted">No Image</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <button class="btn btn-danger  btn-sm delete-btn" data-id="<?= $activityLog->id ?>">Delete</button>
                                </td>
                            </tr>
                            <?php $v114146942739798027821incr++; } ?>
                        <?php } ?>
                    </tbody>
                </table>
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
                    <?php $v114146942739798027821iterator = range(1, $page->total_pages); $v114146942739798027821incr = 0; $v114146942739798027821loop = new stdClass(); $v114146942739798027821loop->self = &$v114146942739798027821loop; $v114146942739798027821loop->length = count($v114146942739798027821iterator); $v114146942739798027821loop->index = 1; $v114146942739798027821loop->index0 = 1; $v114146942739798027821loop->revindex = $v114146942739798027821loop->length; $v114146942739798027821loop->revindex0 = $v114146942739798027821loop->length - 1; ?><?php foreach ($v114146942739798027821iterator as $i) { ?><?php $v114146942739798027821loop->first = ($v114146942739798027821incr == 0); $v114146942739798027821loop->index = $v114146942739798027821incr + 1; $v114146942739798027821loop->index0 = $v114146942739798027821incr; $v114146942739798027821loop->revindex = $v114146942739798027821loop->length - $v114146942739798027821incr; $v114146942739798027821loop->revindex0 = $v114146942739798027821loop->length - ($v114146942739798027821incr + 1); $v114146942739798027821loop->last = ($v114146942739798027821incr == ($v114146942739798027821loop->length - 1)); ?>
                    <li class="page-item <?php if ($i == $page->current) { ?>active<?php } ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php $v114146942739798027821incr++; } ?>
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
    $(document).ready(function() {
            function formatDateToYYYYMMDD(dateString) {
                if (!dateString) return "";
                const date = new Date(dateString);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, "0");
                const day = String(date.getDate()).padStart(2, "0");
                return `${year}-${month}-${day}`;
            }

            // Ubah format tanggal di setiap baris tabel
            $("td.date-range").each(function () {
                const dates = $(this).text().split(" - ");
                if (dates.length === 2) {
                    const formattedStartDate = formatDateToYYYYMMDD(dates[0]);
                    const formattedEndDate = formatDateToYYYYMMDD(dates[1]);
                    $(this).text(`${formattedStartDate} - ${formattedEndDate}`);
                }
            });

            function formatRupiah(number, type) {
                    const formattedNumber = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(number);

                    if (type == null) {
                        return `${formattedNumber}`;
                    }
                    return `${formattedNumber} / ${type}`;
            }

            // Apply the format to cost and total cost columns
            $('.cost').each(function() {
                const value = $(this).data('value');
                const type = $(this).closest('tr').find('td:eq(5)').text();
                $(this).text(formatRupiah(value, type));
            });

            $('.total-cost').each(function() {
                const value = $(this).data('value');
                const type = $(this).closest('tr').find('td:eq(5)').text();
                $(this).text(formatRupiah(value));
            });

        // Handle delete button
        $('.delete-btn').on('click', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/frontend/activity-log/delete/' + id,
                        type: 'POST',
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(function() {
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: xhr.responseJSON.message,
                                confirmButtonText: 'OK'
                            });
                        },
                        dataType: 'json'
                    });
                }
            });
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