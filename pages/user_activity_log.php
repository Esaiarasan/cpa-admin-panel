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


$pageTitle = "User Activity Log";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

?>

<div class="main-content container-fluid">
    <div class="page-header">
        <h1>User Activity Log List</h1>
    </div>

    <div class="table-responsive">
        <table id="userActivityTable" class="display nowrap table table-striped table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Created On</th>
                    <th>View</th>
                </tr>
                <tr class="search-row">
                    <th></th>
                    <th><input type="text" class="form-control form-control-sm column-search"/></th>
                    <th><input type="text" class="form-control form-control-sm column-search"/></th>
                    <th><input type="text" class="form-control form-control-sm column-search"/></th>
                    <th><input type="text" class="form-control form-control-sm column-search date-column"/></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>SID0001</td>
                    <td>James</td>
                    <td>Therapist</td>
                    <td>22-October-2025</td>
                    <td>
                        <a class="" title="View" onclick="window.location.href='view_user_activity.php?user_id=SID0001'">
                            <i class="fas fa-eye"></i>
                         </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- CSS -->
<link rel="stylesheet" href="../assets/css/jquery.dataTables.min.css?v=5.0">
<link rel="stylesheet" href="../assets/css/buttons.dataTables.min.css?v=5.0">
<link rel="stylesheet" href="../assets/css/all.min.css?v=5.0">
<link rel="stylesheet" href="../assets/css/style.css?v=5.0">

<!-- JS -->
<script src="../assets/js/jquery.min.js?v=5.0"></script>
<script src="../assets/js/jquery.dataTables.min.js?v=5.0"></script>
<script src="../assets/js/dataTables.buttons.min.js?v=5.0"></script>
<script src="../assets/js/buttons.html5.min.js?v=5.0"></script>
<script src="../assets/js/buttons.print.min.js?v=5.0"></script>
<script src="../assets/js/jszip.min.js?v=5.0"></script>
<script src="../assets/js/pdfmake.min.js?v=5.0"></script>
<script src="../assets/js/vfs_fonts.js?v=5.0"></script>

<script>
$(document).ready(function() {
    let table = $('#userActivityTable').DataTable({
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

    // Toolbar: Global search + date filters
    $("div.dt-toolbar-left").html(`
        <div class="global-search-wrapper">
            <input type="text" class="form-control form-control-sm global-search" placeholder="   Search"/>
        </div>
        <input type="date" class="form-control form-control-sm date-from" placeholder="From">
        <input type="date" class="form-control form-control-sm date-to" placeholder="To">
    `);

    // Global search
    $('.global-search').on('keyup change', function() {
        table.search(this.value).draw();
    });

    // Column search
    $('#userActivityTable thead tr.search-row th').each(function(index) {
    // Skip S.No (0) and View (5)
    if (index === 0 || index === 5) return;

    $('input', this).on('keyup change', function() {
        table.column(index).search(this.value).draw();
    });
});

    // Date range filter (Created On column index 4)
    $.fn.dataTable.ext.search.push(function(settings, data) {
        let from = $('.date-from').val();
        let to = $('.date-to').val();
        let createdOn = data[4];
        if (!from && !to) return true;
        let createdDate = new Date(createdOn);
        if (from && createdDate < new Date(from)) return false;
        if (to && createdDate > new Date(to)) return false;
        return true;
    });

    $('.date-from, .date-to').on('change', function() {
        table.draw();
    });
});
</script>

<style>

table.dataTable>thead>tr>th, table.dataTable>thead>tr>td {
    padding: 0px!important;

}

/* Toolbar layout */
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

/* Global search wrapper with icon */
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
    margin-bottom: 0px;
    width: 12rem;
}

/* Font Awesome search icon only colored */
.global-search-wrapper::before {
    content: "\f002";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    position: absolute;
    left: 8px;
    top: 50%;
    transform: translateY(-50%);
    color: #18b1fc; /* icon color */
    pointer-events: none;
}

/* Date inputs */
/* Date From input */
.date-from {
    height: 34px;
    border-radius: 4px;
    border: 1px solid #ced4da;
    margin-left: 117px; /* spacing from the left */
    color:grey;
}

/* Date To input */
.date-to {
    height: 34px;
    border-radius: 4px;
    border: 1px solid #ced4da;
    margin-left: 10px; /* spacing between From and To inputs */
    color:grey;
}


/* Action buttons inside table */
#userActivityTable .btn-action {
    background: transparent !important;
    padding: 4px 8px;
    margin-right: 2px;
    cursor: pointer;
    border: none;
}

#userActivityTable .btn-view { color: black !important; }

/* Export buttons alignment */
.dt-toolbar .dt-buttons {
    margin-left: 18.5rem;
    margin-top: 10px;
}
.dt-buttons .btn { display: inline-flex; align-items: center; justify-content: center; height: 34px; padding: 0 8px; }
</style>

<?php require_once '../includes/footer.php'; ?>
