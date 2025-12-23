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


$pageTitle = "Edit Role";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

// Get role ID from query string
$role_id = $_GET['id'] ?? '';

if (empty($role_id)) {
    echo "<h3 style='color:red;text-align:center;margin-top:20px;'>Invalid Role ID!</h3>";
    exit;
}

// Fetch navigation items for dropdown
$stmt = $pdo->query("SELECT nav_id, nav_name FROM navigation_items ORDER BY parent_id ASC, nav_id ASC");
$nav_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link href="../assets/css/select2.min.css" rel="stylesheet" />

<div class="main-content">
    <div class="page-header">
        <button class="back-btn" onclick="window.history.back();">&larr;</button>
        <span class="page-title">Edit Role</span>
    </div>

    <form id="editRoleForm">
        <input type="hidden" name="role_id" id="role_id" value="<?= htmlspecialchars($role_id) ?>">

        <div class="mb-3">
            <label for="txt_role">Role</label>
            <input type="text" class="form-control" id="txt_role" name="txt_role" required>
        </div>

        <div class="mb-3">
            <label for="txt_department">Department</label>
            <input type="text" class="form-control" id="txt_department" name="txt_department" required>
        </div>

        <div class="mb-3">
            <label for="sel_access">Access</label>
            <select class="form-select" id="sel_access" name="sel_access[]" multiple style="width:100%" required>
                <?php foreach ($nav_items as $nav): ?>
                    <option value="<?= htmlspecialchars($nav['nav_id']) ?>">
                        <?= htmlspecialchars($nav['nav_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="accessControlsContainer" class="access-controls" style="display:none;">
            <h5>Controls:</h5>
            <div id="accessControlsList"></div>
        </div>

        <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(function() {
    $('#sel_access').select2({ width: '100%', closeOnSelect: false });

    const roleId = $('#role_id').val();

    // Fetch existing role details
    $.getJSON('../api/get_role_by_id.php', { id: roleId }, function(res) {
        if (res.status && res.data) {
            const data = res.data;
            $('#txt_role').val(data.role);
            $('#txt_department').val(data.department);

            // Handle access
            const accessArray = (data.access_navigations || '').split(',').filter(Boolean);
            $('#sel_access').val(accessArray).trigger('change');

            // Render permissions
            renderAccessControls(accessArray, data.controls);
        } else {
            alert("Failed to load role details.");
        }
    });

    // Render access controls dynamically
    function renderAccessControls(selectedAccess, controlsData = {}) 
    {
    const container = $('#accessControlsList');
    container.empty();

    if (selectedAccess.length === 0) {
        $('#accessControlsContainer').hide();
        return;
    }

    $('#accessControlsContainer').show();

    selectedAccess.forEach(accessId => {
        const accessText = $('#sel_access option[value="' + accessId + '"]').text();

        // Normalize stored controls
        let actions = [];
        if (controlsData && controlsData[accessId]) {
            if (Array.isArray(controlsData[accessId])) {
                actions = controlsData[accessId];
            } else if (typeof controlsData[accessId] === 'string') {
                try {
                    actions = JSON.parse(controlsData[accessId]);
                } catch {
                    actions = controlsData[accessId].split(',').map(a => a.trim());
                }
            }
        }

        // Force view-only for specific pages
        const viewOnlyPages = ['Dashboard', 'User Activity Log', 'Analytics'];
        const isViewOnly = viewOnlyPages.includes(accessText);
        if (isViewOnly) actions = ['view']; // Force view

        // Generate HTML
        const html = `
            <div class="access-item" data-id="${accessId}">
                <span class="access-title">${accessText}</span>
                <div class="access-buttons">
                    <button type="button" class="ctrl-btn ${actions.includes('view') ? 'active' : ''}" data-action="view">View</button>
                    ${!isViewOnly ? `<button type="button" class="ctrl-btn ${actions.includes('edit') ? 'active' : ''}" data-action="edit">Edit</button>` : ''}
                    ${!isViewOnly ? `<button type="button" class="ctrl-btn ${actions.includes('delete') ? 'active' : ''}" data-action="delete">Delete</button>` : ''}
                </div>
                <input type="hidden" name="permissions[${accessId}]" value="${actions.join(',')}">
            </div>
        `;
        container.append(html);
    });
}


    // Re-render controls when access changes
    $('#sel_access').on('change', function() {
        renderAccessControls($(this).val());
    });

    // Toggle action buttons
    $(document).on('click', '.ctrl-btn', function() {
        $(this).toggleClass('active');
        const item = $(this).closest('.access-item');
        const accessId = item.data('id');
        const selectedActions = item.find('.ctrl-btn.active').map(function() {
            return $(this).data('action');
        }).get();
        item.find(`input[name="permissions[${accessId}]"]`).val(selectedActions.join(','));
    });

    // Submit updated role
    $('#editRoleForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: '../api/update_roles.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                try {
                    const result = JSON.parse(res);
                    alert(result.message);
                    if (result.status) window.location.href = 'roles_list.php';
                } catch {
                    console.error("Invalid response:", res);
                    alert("Unexpected response from server.");
                }
            },
            error: function(err) {
                console.error(err);
                alert("Error while updating role.");
            }
        });
    });
});
</script>

<style>
.access-controls {
    margin-top: 20px;
}
.access-item {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 10px;
}
.access-buttons {
    margin-top: 8px;
}
.ctrl-btn {
    margin-right: 8px;
    padding: 6px 12px;
    border: 1px solid #aaa;
    background: #f4f4f4;
    border-radius: 5px;
    cursor: pointer;
}
.ctrl-btn.active {
    background: #28a745;
    color: #fff;
    border-color: #28a745;
}
.access-title {
    font-weight: 600;
}
</style>

<?php require_once '../includes/footer.php'; ?>
