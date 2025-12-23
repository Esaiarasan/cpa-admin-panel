// Delete Role
// function deleteRole(id) {
//     if (!confirm("Are you sure you want to delete this role?")) return;

//     apiFetch('../api/delete_role.php', {
//         method: "POST",
//         headers: { "Content-Type": "application/json" },
//         body: JSON.stringify({ role_id: id })
//     }).then(res => {
//         if (res && res.success) {
//             showMessage("Role deleted successfully!", "success");
//             loadRoles();
//         } else {
//             showMessage("Error deleting role: " + (res?.message || "Unknown error"), "error");
//         }
//     });
// }

// Add Role (used in add_roles.php)
function addRole(roleData) {
    apiFetch('../api/add_role.php', {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(roleData)
    }).then(res => {
        if (res && res.success) {
            showMessage("Role added successfully!", "success");
            window.location.href = "roles.php";
        } else {
            showMessage("Error adding role: " + (res?.message || "Unknown error"), "error");
        }
    });
}


function viewRole(id) {
    // Redirect to view page
    window.location.href = `view_role.php?id=${id}`;
}

function editRole(id) {
    // Redirect to edit page
    window.location.href = `edit_role.php?id=${id}`;
}

function deleteRole(id) {
    if (!confirm("Are you sure you want to delete this role?")) return;

    // Send JSON body instead of query string
    apiFetch('../api/delete_roles.php', {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ role_id: id })
    })
    .then(res => {
        if (res && (res.success || res.status)) {
            alert("Role deleted successfully!");
            // Remove the deleted row from DataTable safely
            const row = $(`button[onclick='deleteRole(${id})']`).closest('tr');
            if (row.length) {
                table.row(row).remove().draw(false);
            }
        } else {
            alert("Error deleting role: " + (res?.message || "Unknown error"));
        }
    })
    .catch(err => {
        console.error(err);
        alert("Failed to delete role due to a network or API error.");
    });
}

