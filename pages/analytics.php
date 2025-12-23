<?php

require_once '../config.php';

/* -----------------------------------------
   🔒 SESSION PROTECTION
------------------------------------------ */

// BLOCK access if session not set
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// PREVENT browser back/forward caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

/* -----------------------------------------
   🔚 END OF PROTECTION
------------------------------------------ */

$pageTitle = "Analytics";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

// Dummy summary values
$activeTherapists = 18;
$frequentPathway = '2fih';
$frequentSummary = '3.1.1.1.2.1.0';

// Dummy table data
$analyticsRows = [
    ['16-October-2024','James',   '4','4.1.2.1.3.1.0'],
    ['17-October-2024','Diana',   '5','5.1.2.1.3.1.0'],
    ['18-October-2024','Nina',    '3','3.1.2.1.3.1.0'],
    ['19-October-2024','Paul',    '5','5.1.2.1.3.1.0'],
    ['20-October-2024','Dani',    '3','3.1.2.1.3.1.0'],
    ['16-October-2024','James',   '4','4.1.2.1.3.1.0'],
    ['17-October-2024','Diana',   '5','5.1.2.1.3.1.0'],
    ['18-October-2024','Nina',    '3','3.1.2.1.3.1.0'],
    ['19-October-2024','Paul',    '5','5.1.2.1.3.1.0'],
    ['20-October-2024','Dani',    '3','3.1.2.1.3.1.0']
];
?>

<div class="main-content container-fluid">
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center">
        <h1 class="mb-2">Analytics</h1>
    </div>

    <!-- ✅ Summary Section -->
    <div class="summary-box mb-3">
        <div>
            <span class="summary-label">Active Therapists</span>
            <input type="text" value="<?= $activeTherapists ?>" readonly class="form-summary">
        </div>
        <div>
            <span class="summary-label">Frequently Used Pathway</span>
            <input type="text" value="<?= $frequentPathway ?>" readonly class="form-summary">
        </div>
        <div>
            <span class="summary-label">Frequently Used Summary</span>
            <input type="text" value="<?= $frequentSummary ?>" readonly class="form-summary">
        </div>
    </div>

    <!-- ✅ Analytics Table -->
    <div class="table-responsive mt-3">
        <table id="AnalyticsTable" class="display nowrap table table-striped table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Therapist</th>
                    <th>Pathway ID</th>
                    <th>Usage</th>
                </tr>
                <tr class="search-row">
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Date"/></th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Therapist"/></th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Pathway ID"/></th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Usage"/></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($analyticsRows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row[0]) ?></td>
                        <td><?= htmlspecialchars($row[1]) ?></td>
                        <td><?= htmlspecialchars($row[2]) ?></td>
                        <td><?= htmlspecialchars($row[3]) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ✅ DataTables CSS -->
<link rel="stylesheet" href="../assets/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="../assets/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="../assets/css/all.min.css">
<link rel="stylesheet" href="../assets/css/role_style.css">

<!-- ✅ jQuery & DataTables JS -->
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/jquery.dataTables.min.js"></script>
<script src="../assets/js/dataTables.buttons.min.js"></script>
<script src="../assets/js/buttons.html5.min.js"></script>
<script src="../assets/js/buttons.print.min.js"></script>
<script src="../assets/js/jszip.min.js"></script>
<script src="../assets/js/pdfmake.min.js"></script>
<script src="../assets/js/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    let table = $('#AnalyticsTable').DataTable({
        dom: '<"dt-toolbar"<"dt-toolbar-left">B>rt<"bottom"ip>',
        buttons: [
            { extend: 'excelHtml5', text: '<i class="fas fa-file-excel"></i>', className: 'btn btn-sm btn-outline-success' },
            { extend: 'pdfHtml5', text: '<i class="fas fa-file-pdf"></i>', className: 'btn btn-sm btn-outline-danger' },
            { extend: 'print', text: '<i class="fas fa-print"></i>', className: 'btn btn-sm btn-outline-dark' }
        ],
        responsive: true,
        orderCellsTop: true,
        pageLength: 10
    });

    // ✅ Toolbar layout
    $("div.dt-toolbar-left").html(`
        <div class="global-search-wrapper">
            <input type="text" class="form-control form-control-sm global-search" placeholder="   Search"/>
        </div>
        <input type="date" class="form-control form-control-sm date-from" placeholder="From">
        <input type="date" class="form-control form-control-sm date-to" placeholder="To">
    `);

    // ✅ Global search
    $('.global-search').on('keyup change', function() {
        table.search(this.value).draw();
    });

    // ✅ Column-wise search
    $('#AnalyticsTable thead tr.search-row th').each(function(index) {
        $('input', this).on('keyup change', function() {
            table.column(index).search(this.value).draw();
        });
    });

    // ✅ Date range filter (first column = Date)
    $.fn.dataTable.ext.search.push(function(settings, data) {
        let from = $('.date-from').val();
        let to = $('.date-to').val();
        let date = data[0];

        if (!from && !to) return true;
        let parsedDate = new Date(date);
        if (from && parsedDate < new Date(from)) return false;
        if (to && parsedDate > new Date(to)) return false;
        return true;
    });

    $('.date-from, .date-to').on('change', function() {
        table.draw();
    });
});
</script>

<style>
/* ✅ Toolbar layout */
.dt-toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 10px;
}

.dt-toolbar-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

/* ✅ Global search with icon */
.global-search-wrapper {
    position: relative;
    width: 250px;
}

.global-search-wrapper input.global-search {
    padding-left: 30px;
    height: 33px;
    border-radius: 4px;
    border: 1px solid #ced4da;
    color: black;
    width: 12rem;
    margin-bottom:0px
}

.global-search-wrapper::before {
    content: "\f002";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    position: absolute;
    left: 8px;
    top: 50%;
    transform: translateY(-50%);
    color: #18b1fc;
}

/* ✅ Date filters */
.date-from, .date-to {
    height: 34px;
    border-radius: 4px;
    border: 1px solid #ced4da;
}
.date-from { margin-left: 117px; }
.date-to { margin-left: 10px; }

/* ✅ Summary box */
.summary-box div { margin-bottom: -5px; }
.summary-label { min-width: 220px; display: inline-block; }
.form-summary {
    display: inline-block;
    max-width: 20%;
    text-align: center;
    background: #fafbfd;
    border: 1px solid #d1d5db;
    border-radius: 5px;
    margin-left: 4px;
    font-weight: bold;
    color: #333;
}

/* ✅ Export buttons alignment */
.dt-toolbar .dt-buttons { margin-left: 20px; }
.dt-buttons .btn { display: inline-flex; align-items: center; justify-content: center; height: 34px; padding: 0 8px; }
</style>

<?php require_once '../includes/footer.php'; ?>
