<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="img-fluid align-items-center">
                    <img src="<?= $this->url->get('img/logo.png') ?>" alt="logo" class="img-fluid ms-3" style="width: 200px; height:100px;">
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Core</li>
                <li
                    <?php if ($routeName == 'dashboard') { ?>
                         class="sidebar-item active"
                    <?php } else { ?>
                        class="sidebar-item"
                    <?php } ?>
                >
                    <a href="<?= $this->url->get('/frontend/dashboard') ?>" class="sidebar-link">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li
                    <?php if ($routeName == 'payroll') { ?>
                         class="sidebar-item active"
                    <?php } else { ?>
                        class="sidebar-item"
                    <?php } ?>
                >
                    <a href="<?= $this->url->get('/frontend/payroll') ?>" class="sidebar-link">
                        <i class="bi bi-cash-stack"></i>
                        <span>Payroll</span>
                    </a>
                </li>
                <li
                    <?php if ($routeName == 'report') { ?>
                         class="sidebar-item active"
                    <?php } else { ?>
                        class="sidebar-item"
                    <?php } ?>
                >
                    <a href="<?= $this->url->get('/frontend/report') ?>" class="sidebar-link">
                        <i class="bi bi-flag-fill"></i>
                        <span>Report</span>
                    </a>
                </li>
                <li
                    <?php if ($routeName == 'report-history') { ?>
                         class="sidebar-item active"
                    <?php } else { ?>
                        class="sidebar-item"
                    <?php } ?>
                >
                    <a href="<?= $this->url->get('/frontend/report/history') ?>" class="sidebar-link">
                        <i class="bi bi-clock-history"></i>
                        <span>History</span>
                    </a>
                </li>
                <li class="sidebar-title">Setting</li>
                <li
                    <?php if ($routeName == 'budget-activity') { ?>
                         class="sidebar-item active"
                    <?php } else { ?>
                        class="sidebar-item"
                    <?php } ?>
                >
                    <a href="<?= $this->url->get('/frontend/budget-activity') ?>" class="sidebar-link">
                        <i class="bi bi-cash-coin"></i>
                        <span>Budget Activity</span>
                    </a>
                </li>
                <li 
                    <?php if ($routeName == 'uom-setting') { ?>
                         class="sidebar-item active"
                    <?php } else { ?>
                        class="sidebar-item"
                    <?php } ?>
                >
                    <a href="<?= $this->url->get('/frontend/uom-setting') ?>" class="sidebar-link">
                        <i class="bi bi-speedometer"></i>
                        <span>UoM Setting</span>
                    </a>
                </li>
                <li
                    <?php if ($routeName == 'conversion-uom') { ?>
                         class="sidebar-item active"
                    <?php } else { ?>
                        class="sidebar-item"
                    <?php } ?>
                >
                    <a href="<?= $this->url->get('/frontend/conversion-uom') ?>" class="sidebar-link">
                        <i class="bi bi-calculator"></i>
                        <span>Conversion UoM</span>
                    </a>
                </li>
                <li
                    <?php if ($routeName == 'type-activity') { ?>
                         class="sidebar-item active"
                    <?php } else { ?>
                        class="sidebar-item"
                    <?php } ?>
                >
                    <a href="<?= $this->url->get('/frontend/type-activity') ?>" class="sidebar-link">
                        <i class="bi bi-journal-text"></i>
                        <span>Type Activity</span>
                    </a>
                </li>
                <li
                    <?php if ($routeName == 'worker-data') { ?>
                         class="sidebar-item active"
                    <?php } else { ?>
                        class="sidebar-item"
                    <?php } ?>
                >
                    <a href="<?= $this->url->get('/frontend/worker-data') ?>" class="sidebar-link">
                        <i class="bi bi-person"></i>
                        <span>Worker Data</span>
                    </a>
                </li>
                <li
                    <?php if ($routeName == 'activity-setting') { ?>
                         class="sidebar-item active"
                    <?php } else { ?>
                        class="sidebar-item"
                    <?php } ?>
                >
                    <a href="<?= $this->url->get('/frontend/activity-setting') ?>" class="sidebar-link">
                        <i class="bi bi-sliders2-vertical"></i>
                        <span>Activity Setting</span>
                    </a>
                </li>
                <li
                    <?php if ($routeName == 'material') { ?>
                         class="sidebar-item active"
                    <?php } else { ?>
                        class="sidebar-item"
                    <?php } ?>
                >
                    <a href="<?= $this->url->get('/frontend/material') ?>" class="sidebar-link">
                        <i class="bi bi-box"></i>
                        <span>Material</span>
                    </a>
                </li>
                <li class="sidebar-title">Project & Plot</li>
                <li
                    <?php if ($routeName == 'project') { ?>
                         class="sidebar-item active"
                    <?php } else { ?>
                        class="sidebar-item"
                    <?php } ?>
                >
                    <a href="<?= $this->url->get('/frontend/project') ?>" class="sidebar-link">
                        <i class="bi bi-kanban"></i>
                        <span>Project</span>
                    </a>
                </li>
                <li
                    <?php if ($routeName == 'plot') { ?>
                         class="sidebar-item active"
                    <?php } else { ?>
                        class="sidebar-item"
                    <?php } ?>
                >
                    <a href="<?= $this->url->get('/frontend/plot') ?>" class="sidebar-link">
                        <i class="bi bi-clipboard-data"></i>
                        <span>Plot</span>
                    </a>
                </li>
                <li class="sidebar-title">Logging</li>
                <li
                    <?php if ($routeName == 'supporting-material') { ?>
                         class="sidebar-item active"
                    <?php } else { ?>
                        class="sidebar-item"
                    <?php } ?>
                >
                    <a href="<?= $this->url->get('/frontend/supporting-material') ?>" class="sidebar-link">
                        <i class="bi bi-box"></i>
                        <span>Supporting Material</span>
                    </a>
                </li>
                <li
                    <?php if ($routeName == 'activity-log') { ?>
                         class="sidebar-item active"
                    <?php } else { ?>
                        class="sidebar-item"
                    <?php } ?>
                >
                    <a href="<?= $this->url->get('/frontend/activity-log') ?>" class="sidebar-link">
                        <i class="bi bi-journal-text"></i>
                        <span>Activity Log</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
