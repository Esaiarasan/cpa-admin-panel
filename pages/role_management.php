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
$pageTitle = "Role Management";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';
?>

<div class="main-content">
    <div class="page-header">
        <h1>Role Management</h1>
    </div>
    <a href="add_roles.php" class="btn btn-primary create_button">Create</a>
    <br><br>

    <div class="table-responsive">
        <table id="rolesTable" class="display nowrap table table-striped table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Role Name</th>
                    <th>Actions</th>
                </tr>
                <!-- <tr class="search-row">
                    <th></th>
                    <th><input type="text" placeholder="Search Role" class="form-control form-control-sm search_role"/></th>
                    <th></th>
                </tr> -->
            </thead>
            <tbody>
                <!-- Data loaded dynamically via JS -->
            </tbody>
        </table>
    </div>
</div>


<!-- CSS -->
<link rel="stylesheet" href="../assets/css/jquery.dataTables.min.css?v=7.0">
<link rel="stylesheet" href="../assets/css/buttons.dataTables.min.css?v=7.0">
<link rel="stylesheet" href="../assets/css/all.min.css?v=7.0">
<link rel="stylesheet" href="../assets/css/role_style.css?v=7.0">

<!-- JS -->
<script src="../assets/js/jquery.min.js?v=7.0"></script>
<script src="../assets/js/jquery.dataTables.min.js?v=7.0"></script>
<script src="../assets/js/dataTables.buttons.min.js?v=7.0"></script>
<script src="../assets/js/buttons.html5.min.js?v=7.0"></script>
<script src="../assets/js/buttons.print.min.js?v=7.0"></script>
<script src="../assets/js/jszip.min.js?v=7.0"></script>
<script src="../assets/js/pdfmake.min.js?v=7.0"></script>
<script src="../assets/js/vfs_fonts.js?v=7.0"></script>
<script src="../assets/js/roles.js?v=7.0"></script>



<?php require_once '../includes/footer.php'; ?>
