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

$pageTitle = "Administrator List";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

// Fetch administrators with Role/Access from navigation_items
$stmt = $pdo->prepare("
    SELECT 
        t.administrator_id,
        t.user_id,
        t.first_name,
        t.last_name,
        t.email,
        t.role_access_id,
        t.created_at,
        n.role AS role_access
    FROM administrator t
    LEFT JOIN role_management n ON t.role_access_id = n.r_id
    ORDER BY t.created_at DESC
");
$stmt->execute();
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="main-content container-fluid">
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center">
        <h1 class="mb-2">Administrator List</h1>
    </div>

    <a href="add_administrator.php" class="btn btn-primary mb-2 create_button">Create</a>
    <br><br>

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-striped table-hover align-middle nowrap" 
               id="AdministratorsTable" style="width:100%">
            <thead class="table-light">
                <tr>
                    <th>S.No</th>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Role/Access</th>
                    <th>DOJ</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $row): ?>
                <tr>
                    <td></td> <!-- S.No dynamic -->
                    <td><?= htmlspecialchars($row['user_id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['role_access'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= date('d-F-Y', strtotime($row['created_at'])) ?></td>
                    <td>
                        <div class="d-flex flex-wrap gap-2">
                    <a href="view_administrator.php?id=<?= $row['administrator_id'] ?>"><button class="btn-icon" title="View"><i class="fa-regular fa-eye" style="color:black;">
                    </i></button></a>
                    <a href="edit_administrator.php?id=<?= $row['administrator_id'] ?>"><button class="btn-icon" title="Edit"><i class="fa-solid fa-pen" style="color:#0063a5;"></i></button></a>
                    <button class="btn-icon delete-btn" data-id="<?= $row['administrator_id'] ?>" title="Delete">
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

<!-- ✅ DataTables CSS -->
<link rel="stylesheet" href="../assets/css/jquery.dataTables.min.css?v=8.0">
<link rel="stylesheet" href="../assets/css/buttons.dataTables.min.css?v=8.0">
<link rel="stylesheet" href="../assets/css/all.min.css?v=8.0">
<link rel="stylesheet" href="../assets/css/style.css?v=8.0">

<!-- ✅ jQuery & DataTables JS -->
<script src="../assets/js/jquery.min.js?v=8.0"></script>
<script src="../assets/js/jquery.dataTables.min.js?v=8.0"></script>
<script src="../assets/js/dataTables.buttons.min.js?v=8.0"></script>
<script src="../assets/js/buttons.html5.min.js?v=8.0"></script>
<script src="../assets/js/buttons.print.min.js?v=8.0"></script>
<script src="../assets/js/jszip.min.js?v=8.0"></script>
<script src="../assets/js/pdfmake.min.js?v=8.0"></script>
<script src="../assets/js/vfs_fonts.js?v=8.0"></script>
<script src="../assets/js/administrator.js?v=8.0"></script>



<?php require_once '../includes/footer.php'; ?>
