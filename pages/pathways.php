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
$pageTitle = "Clinical Pathways";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';
?>


<div class="main-content">
    <div class="page-header">
        <h1>Clinical Pathways</h1>
    </div>
 
    <div class="table-responsive">
        <table id="pathwaysTable" class="display nowrap table table-striped table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>Pathway ID</th>
                    <th>Pathway Name</th>
                    <th>Created On</th>
                    <th>Created By</th>
                    <th>Last Edit On</th>
                    <th>Edited By</th>
                    <th>Action</th>
                </tr>
                <tr class="search-row">
                    <th><input type="text" placeholder="" class="form-control form-control-sm search_role"/></th>
                    <th><input type="text" placeholder="" class="form-control form-control-sm search_role"/></th>
                    <th><input type="text" placeholder="" class="form-control form-control-sm search_role"/></th>
                    <th><input type="text" placeholder="" class="form-control form-control-sm search_role"/></th>
                    <th><input type="text" placeholder="" class="form-control form-control-sm search_role"/></th>
                    <th><input type="text" placeholder="" class="form-control form-control-sm search_role"/></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <!-- Data loaded dynamically via JS -->
            </tbody>
        </table>
    </div>
</div>

<!-- CSS -->
<link rel="stylesheet" href="../assets/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="../assets/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="../assets/css/all.min.css">
<link rel="stylesheet" href="../assets/css/role_style.css">

<!-- JS -->
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
    // Initialize DataTable
    let table = $('#pathwaysTable').DataTable({
        dom: '<"dt-toolbar"<"dt-toolbar-left">B>rt<"bottom"ip>',
        buttons: [
            { extend: 'excelHtml5', text: '<i class="fas fa-file-excel"></i>', className: 'btn btn-sm btn-outline-success' },
            { extend: 'pdfHtml5', text: '<i class="fas fa-file-pdf"></i>', className: 'btn btn-sm btn-outline-danger' },
            { extend: 'print', text: '<i class="fas fa-print"></i>', className: 'btn btn-sm btn-outline-dark' }
        ],
        responsive: true,
        orderCellsTop: true
    });

    // -----------------------------
    // Toolbar: global search + From/To dates
    // -----------------------------
    $("div.dt-toolbar-left").html(`
        <input type="text" placeholder="Search All Columns" style="margin-top:18px"class="form-control form-control-sm search_role1"/>
        <input type="date" placeholder="From" style="margin-left: 100px;" class="form-control form-control-sm date-from"/>
        <input type="date" placeholder="To" style="margin-left: 50px;" class="form-control form-control-sm date-to"/>
    `);

    // -----------------------------
    // Global search
    // -----------------------------
    $('.search_role1').on('keyup change', function() {
        table.search(this.value).draw();
    });

    // -----------------------------
    // Column-specific search
    // -----------------------------
    $('#pathwaysTable thead tr.search-row th').each(function(index) {
        $('input', this).on('keyup change', function() {
            table.column(index).search(this.value).draw();
        });
    });

    // -----------------------------
    // Date range filter for "Created On" column (index 2)
    // -----------------------------
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            let from = $('.date-from').val();
            let to = $('.date-to').val();
            let createdOn = data[2]; // "Created On" column

            if (!from && !to) return true;

            let createdDate = new Date(createdOn);
            if (from && createdDate < new Date(from)) return false;
            if (to && createdDate > new Date(to)) return false;

            return true;
        }
    );

    $('.date-from, .date-to').on('change', function() {
        table.draw();
    });

    // -----------------------------
    // Load data from API
    // -----------------------------
    $.ajax({
        url: '../api/get_pathways.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            data.forEach((item, index) => {
                table.row.add([
                    item.pathway_id || index + 1,
                    item.pathway_name,
                    item.created_on,
                    item.created_by,
                    item.last_edit_on,
                    item.edited_by,
                    `<button class="fa-regular fa-eye" title="View"><i class="fas fa-eye"></i></button>
                     <button class="btn-action btn-edit" title="Edit"><i class="fas fa-pen"></i></button>
                     <button class="btn-action btn-delete" title="Delete"><i class="fas fa-trash"></i></button>`
                ]).draw(false);
            });
        },
        error: function(xhr, status, error) {
            console.error("Error loading data:", error);
        }
    });
});

</script>

<style>
/* Toolbar inline styling */
.dt-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.dt-toolbar-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.search_role1 {
    width: 200px;
}

/* Action buttons inside table */
#pathwaysTable .btn-action {
    background: transparent !important;
    padding: 4px 8px;
    margin-right: 2px;
    cursor: pointer;
    border: none;
}

#pathwaysTable .btn-view {
    color: black !important;
}

#pathwaysTable .btn-edit {
    color: #0063a5 !important;
}

#pathwaysTable .btn-delete {
    color: red !important;
}
</style>

<?php require_once '../includes/footer.php'; ?>
