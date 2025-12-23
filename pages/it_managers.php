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


$pageTitle = "IT Managers";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

// Fetch IT Managers with Role/Access name
$stmt = $pdo->prepare("
    SELECT 
        t.it_manager_id,
        t.user_id,
        t.first_name,
        t.last_name,
        t.email,
        t.role_access_id,
        t.profile_image,
        t.created_at,
        n.role AS role_access
    FROM it_managers t
    LEFT JOIN role_management n ON t.role_access_id = n.r_id
    ORDER BY t.created_at ASC
");
$stmt->execute();
$it_managers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="main-content container-fluid">
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center">
        <h1 class="mb-2">IT Managers List</h1>
    </div>
    <a href="add_it_manager.php" class="btn btn-primary mb-2 create_button">Create</a>
    <br><br>

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-striped table-hover align-middle nowrap" id="itManagersTable" style="width:100%">
            <thead class="table-light">
                <tr>
                    <th>S.No</th>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Role/Access</th>
                    <th>DOJ</th>
                    <th>Action</th>
                </tr>
                <tr class="search-row">
                    <th></th>
                    <th><input type="text" placeholder="Search User ID" class="form-control form-control-sm" /></th>
                    <th><input type="text" placeholder="Search Name" class="form-control form-control-sm" /></th>
                    <th><input type="text" placeholder="Search Role" class="form-control form-control-sm" /></th>
                    <th><input type="text" placeholder="Search DOJ" class="form-control form-control-sm" /></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($it_managers as $row): ?>
                <tr>
                    <td></td> <!-- Dynamic S.No -->
                    <td><?= htmlspecialchars($row['user_id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <div class="d-flex align-items-center">
                   
                            <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name'], ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($row['role_access'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= date('d-F-Y', strtotime($row['created_at'])) ?></td>
                    <td>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="view_it_manager.php?id=<?= $row['it_manager_id'] ?>"><button class="btn-icon" title="View"><i class="fa-regular fa-eye" style="color:black;"></i></button></a>
                            <a href="edit_it_manager.php?id=<?= $row['it_manager_id'] ?>"><button class="btn-icon" title="Edit"><i class="fa-solid fa-pen" style="color:#0063a5;"></i></button></a>
                            <button class="btn-icon delete-btn" data-id="<?= $row['it_manager_id'] ?>" title="Delete">
                                <i class="fa-solid fa-trash" style="color:red;"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ✅ DataTables + Global CSS -->
<link rel="stylesheet" href="../assets/css/jquery.dataTables.min.css?v=4.0">
<link rel="stylesheet" href="../assets/css/buttons.dataTables.min.css?v=4.0">
<link rel="stylesheet" href="../assets/css/all.min.css?v=4.0">
<link rel="stylesheet" href="../assets/css/style.css?v=4.0"> <!-- Your custom styles -->

<!-- ✅ jQuery + DataTables JS -->
<script src="../assets/js/jquery.min.js?v=4.0"></script>
<script src="../assets/js/jquery.dataTables.min.js?v=4.0"></script>
<script src="../assets/js/dataTables.buttons.min.js?v=4.0"></script>
<script src="../assets/js/buttons.html5.min.js?v=4.0"></script>
<script src="../assets/js/buttons.print.min.js?v=4.0"></script>
<script src="../assets/js/jszip.min.js?v=4.0"></script>
<script src="../assets/js/pdfmake.min.js?v=4.0"></script>
<script src="../assets/js/vfs_fonts.js?v=4.0"></script>
<!-- <script src="../assets/js/roles.js?v=4.0"></script> -->



<script>
$(document).ready(function() {

    // Initialize DataTable
    let table = $('#itManagersTable').DataTable({
        responsive: true,
        dom: '<"d-flex flex-wrap justify-content-between mb-2"<"search-bar"f><"dt-buttons"B>>rt<"bottom d-flex flex-wrap justify-content-between"ip>',
        buttons: [
            { extend: 'print', text: '<i class="fa fa-print"></i>', className: 'btn btn-sm btn-dark' },
            { extend: 'pdfHtml5', text: '<i class="fa fa-file-pdf"></i>', className: 'btn btn-sm btn-dark' },
            { extend: 'excelHtml5', text: '<i class="fa fa-file-excel"></i>', className: 'btn btn-sm btn-dark' }
        ],
        pageLength: 10,
        orderCellsTop: true,
        fixedHeader: true,
        autoWidth: false,
        order: [[4, 'desc']],
        columnDefs: [
            { orderable: false, targets: [0, 5] }
        ]
    });
    
    // Set placeholder for global search box
    $('.dataTables_filter input').attr('placeholder', '      Search');

    // Column-wise search
    $('#itManagersTable thead tr.search-row th').each(function(i) {
        let input = $(this).find('input');
        if(input.length) {
            input.on('keyup change clear', function() {
                if(table.column(i).search() !== this.value) {
                    table.column(i).search(this.value).draw();
                }
            });
        }
    });

    // Dynamic S.No
    table.on('order.dt search.dt', function() {
        table.column(0, { search:'applied', order:'applied' }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

    // Delete IT Manager
    $(document).on('click', '.delete-btn', function() {
        if(!confirm("Are you sure you want to delete this IT Manager?")) return;

        let managerId = $(this).data('id');
        let row = $(this).closest('tr');

        $.ajax({
            url: '../api/delete_it_manager.php',
            type: 'POST',
            data: { it_manager_id: managerId },
            dataType: 'json',
            success: function(res) {
                if(res.status === 'success') {
                    table.row(row).remove().draw();
                    alert(res.message);
                } else {
                    alert('Error: ' + res.message);
                }
            },
            error: function() {
                alert('Something went wrong!');
            }
        });
    });

});
</script>

<?php require_once '../includes/footer.php'; ?>
