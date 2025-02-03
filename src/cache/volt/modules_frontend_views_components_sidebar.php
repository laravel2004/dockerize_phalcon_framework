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
                <li class="sidebar-title">Menu</li>
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
                <li
                    <?php if ($routeName == 'export') { ?>
                         class="sidebar-item active"
                    <?php } else { ?>
                        class="sidebar-item"
                    <?php } ?>
                >
                    <a href="<?= $this->url->get('/frontend/export') ?>" class="sidebar-link">
                        <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                        <span>Export View</span>
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
            </ul>
        </div>
    </div>
</div>
