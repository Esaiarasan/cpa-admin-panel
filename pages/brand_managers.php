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


$pageTitle = "Brand Manager List";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

// Fetch brand_manager with their Role/Access name from role_management
$stmt = $pdo->prepare("
    SELECT 
        t.brand_manager_id,
        t.user_id,
        t.first_name,
        t.last_name,
        t.email,
        t.role_access_id,
        t.created_at,
        n.role AS role_access
    FROM brand_manager t
    LEFT JOIN role_management n ON t.role_access_id = n.r_id
    ORDER BY t.created_at DESC
");
$stmt->execute();
$brand_manager = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="main-content container-fluid">
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center">
        <h1 class="mb-2">Brand Manager List</h1>
    </div>

    <a href="add_brandmanager.php" class="btn btn-primary mb-2 create_button">Create</a>
     <br><br>

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-striped table-hover align-middle nowrap" 
               id="BrandManagerTable" style="width:100%">
            <thead class="table-light">
                <tr>
                    <th style="width: 5%;">S.No</th>
                    <th style="width: 15%;">User ID</th>
                    <th style="width: 20%;">Name</th>
                    <th style="width: 25%;">Role/Access</th>
                    <th style="width: 15%;">DOJ</th>
                    <th style="width: 20%;">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php $sn = 1; foreach ($brand_manager as $row): ?>
            <tr>
                <td><?= $sn++ ?></td>
                <td><?= htmlspecialchars($row['user_id'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row['role_access'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= !empty($row['created_at']) ? date('d-F-Y', strtotime($row['created_at'])) : '' ?></td>
                <td>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn-icon" title="View"><i class="fa-regular fa-eye" style="color:black;"></i></button>
                        <button class="btn-icon" title="Edit"><i class="fa-solid fa-pen" style="color:#0063a5;"></i></button>
                        <button class="btn-icon" title="Delete"><i class="fa-solid fa-trash" style="color:red;"></i></button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>

<!-- ✅ DataTables CSS -->
<link rel="stylesheet" href="../assets/css/jquery.dataTables.min.css?v=4.0">
<link rel="stylesheet" href="../assets/css/buttons.dataTables.min.css?v=4.0">
<link rel="stylesheet" href="../assets/css/all.min.css?v=4.0">
<link rel="stylesheet" href="../assets/css/style.css?v=4.0">

<!-- ✅ jQuery & DataTables JS -->
<!-- jQuery & DataTables JS -->
<script src="../assets/js/jquery.min.js?v=4.0"></script>
<script src="../assets/js/jquery.dataTables.min.js?v=4.0"></script>
<script src="../assets/js/dataTables.buttons.min.js?v=4.0"></script>
<script src="../assets/js/buttons.html5.min.js?v=4.0"></script>
<script src="../assets/js/buttons.print.min.js?v=4.0"></script>
<script src="../assets/js/jszip.min.js?v=4.0"></script>
<script src="../assets/js/pdfmake.min.js?v=4.0"></script>
<script src="../assets/js/vfs_fonts.js?v=4.0"></script>

<!-- ✅ jQuery UI for Datepicker -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css?v=4.0">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js?v=4.0"></script>

<script>
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#BrandManagerTable')) {
        $('#BrandManagerTable').DataTable().destroy();
    }

    // Clone header row for search
    $('#BrandManagerTable thead tr').clone(true).appendTo('#BrandManagerTable thead').addClass('search-row');
    $('#BrandManagerTable thead tr.search-row th').each(function(i) {
        if ([1,2,3,4].includes(i)) {
            $(this).html('<input type="text" placeholder="Search" class="form-control form-control-sm" />');
        } else {
            $(this).html('');
        }
    });

    let table = $('#BrandManagerTable').DataTable({
        responsive: true,
        dom:
          '<"d-flex flex-wrap justify-content-between mb-2"<"search-bar"f><"dt-buttons"B>>' +
          'rt' +
          '<"d-flex flex-wrap justify-content-between align-items-center mt-3"lip>',
        buttons: [
            { extend: 'print', text: '<i class="fa fa-print"></i>', className: 'btn btn-sm btn-dark' },
            { extend: 'pdfHtml5', text: '<i class="fa fa-file-pdf"></i>', className: 'btn btn-sm btn-dark' },
            { extend: 'excelHtml5', text: '<i class="fa fa-file-excel"></i>', className: 'btn btn-sm btn-dark' }
        ],
        pageLength: 10,
        orderCellsTop: true,
        fixedHeader: true,
        autoWidth: false
    });
  // Set placeholder for global search box
  $('.dataTables_filter input').attr('placeholder', '      Search');
    // Column search
    $('#BrandManagerTable thead tr.search-row th').each(function(i) {
        let input = $(this).find('input');
        if (input.length) {
            if(i === 4){ 
                input.datepicker({ dateFormat: 'dd-MM-yy' });
            }
            input.on('keyup change clear', function() {
                if (table.column(i).search() !== this.value) {
                    table.column(i).search(this.value).draw();
                }
            });
        }
    });

    // Global search
    $('.dataTables_filter input').unbind().bind('keyup change', function() {
        table.search(this.value).draw();
    });

    // Dynamic S.No
    table.on('order.dt search.dt', function() {
        table.column(0, {search:'applied', order:'applied'}).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
});

</script>

<?php require_once '../includes/footer.php'; ?>
