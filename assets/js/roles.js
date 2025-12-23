let table;

document.addEventListener('DOMContentLoaded', function () {
    // Ensure jQuery and DataTables are loaded
    if (typeof $ === 'undefined' || typeof $.fn.DataTable === 'undefined') {
        console.error("jQuery or DataTables not loaded.");
        return;
    }

    if ($('#rolesTable').length) {
        initDataTable();
        loadRoles();
    }
});

function initDataTable() {
    // Destroy any existing instance to prevent reinitialization errors
    if ($.fn.DataTable.isDataTable('#rolesTable')) {
        $('#rolesTable').DataTable().clear().destroy();
    }

    // Initialize DataTable
    table = $('#rolesTable').DataTable({
        dom: '<"d-flex justify-content-between mb-2"<"search-bar"f><"dt-buttons"B>>rt<"bottom"ip>',
        buttons: [
            { extend: 'print', text: '<i class="fa fa-print"></i>', className: 'btn btn-sm btn-dark' },
            { extend: 'pdfHtml5', text: '<i class="fa fa-file-pdf"></i>', className: 'btn btn-sm btn-dark' },
            { extend: 'excelHtml5', text: '<i class="fa fa-file-excel"></i>', className: 'btn btn-sm btn-dark' }
        ],
        pageLength: 10,
        orderCellsTop: true,
        fixedHeader: true,
        autoWidth: true,
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "       Search"
        }
    });

    // Remove label text (keep only input)
    const $filter = $('.dataTables_filter');
    $filter.find('label').contents().filter(function() {
        return this.nodeType === 3;
    }).remove();

    // Add wrapper for icon inside input
    const $input = $filter.find('input');
    $input.wrap('<div class="dt-search-wrapper"></div>');
    $('.dt-search-wrapper').prepend('<i class="fa fa-search"></i>');

    // Column-specific search (Role Name)
    $('#rolesTable thead input.search_role').on('keyup change clear', function () {
        table.column(1).search(this.value).draw();
    });

    // Global search
    $('.dataTables_filter input')
        .off()
        .on('keyup change', function () {
            table.search(this.value).draw();
        });
}

// Load Roles
function loadRoles() {
    apiFetch('../api/get_roles.php')
        .then(data => {
            if (!Array.isArray(data)) {
                console.error("Invalid API data:", data);
                return;
            }

            const rows = data.map((role, index) => [
                index + 1,
                role.role,
                `
                <button class="btn-icon" title="View" onclick="viewRole(${role.r_id})">
                    <i class="fa-regular fa-eye" style="color:black;"></i>
                </button>
                <button class="btn-action btn-edit" title="Edit" onclick="editRole(${role.r_id})">
                    <i class="fa-solid fa-pen" style="color:#0063a5;"></i>
                </button>
                <button class="btn-action btn-delete" title="Delete" onclick="deleteRole(${role.r_id})">
                    <i class="fa-solid fa-trash" style="color:red;"></i>
                </button>
                `
            ]);

            table.clear().rows.add(rows).draw();
        })
        .catch(error => {
            console.error("Error loading roles:", error);
        });
}

// Redirect functions
function viewRole(id) { window.location.href = `view_role.php?id=${id}`; }
function editRole(id) { window.location.href = `edit_role.php?id=${id}`; }
function deleteRole(id) {
    if(confirm("Are you sure you want to delete this role?")) {
        apiFetch(`../api/delete_roles.php?id=${id}`, { method: 'POST' })
            .then(res => {
                alert("Role deleted successfully!");
                table.row($(`button[onclick='deleteRole(${id})']`).parents('tr')).remove().draw();
            })
            .catch(err => alert("Failed to delete role."));
    }
}

// Utility function to fetch API
function apiFetch(url, options = {}) {
    return fetch(url, options)
        .then(response => {
            if (!response.ok) throw new Error("Network response was not ok");
            return response.json();
        });
}

// ========================
// Access Controls Section
// ========================
$(function() {
    $('#sel_access').select2({
        placeholder: "Select Access...",
        width: "100%",
        closeOnSelect: false
    });

    let addedAccessIds = {};

    $('#sel_access').on('change', function() {
        let selectedAccess = $(this).val() || [];
        let container = $('#accessControlsList');

        // Remove deselected access
        Object.keys(addedAccessIds).forEach(id => {
            if (!selectedAccess.includes(id)) {
                container.find('.access-item[data-id="' + id + '"]').remove();
                delete addedAccessIds[id];
            }
        });

        // Add newly selected access
// Add newly selected access
selectedAccess.forEach(accessId => {
    if (!addedAccessIds[accessId]) {
        let accessText = $('#sel_access option[value="' + accessId + '"]').text().trim();

        // Pages with view-only access
        let viewOnlyPages = ['Dashboard', 'User Activity Log', 'Analytics'];
        let buttonsHtml = '';

        if (viewOnlyPages.includes(accessText)) {
            // Only view button, pre-active
            buttonsHtml = `<button type="button" class="ctrl-btn active" data-action="view">View</button>`;
        } else {
            // All buttons for other pages
            buttonsHtml = `
                <button type="button" class="ctrl-btn" data-action="view">View</button>
                <button type="button" class="ctrl-btn" data-action="edit">Edit</button>
                <button type="button" class="ctrl-btn" data-action="delete">Delete</button>
            `;
        }

        let html = `
            <div class="access-item" data-id="${accessId}">
                <span>${accessText}</span>
                <div class="access-buttons">
                    ${buttonsHtml}
                </div>

                <input type="hidden" class="perm-input" name="permissions[${accessId}]" value="${viewOnlyPages.includes(accessText) ? 'view' : ''}" data-id="${accessId}">

            </div>
        `;

        container.append(html);
        addedAccessIds[accessId] = true;
    }
});


        $('#accessControlsContainer').toggle(selectedAccess.length > 0);
    });

    // Toggle buttons
$(document).on('click', '.ctrl-btn', function() {
    $(this).toggleClass('active');
    let item = $(this).closest('.access-item');
    let accessId = item.data('id');
    let selectedActions = item.find('.ctrl-btn.active').map(function() {
        return $(this).data('action');
    }).get().join(',');

    item.find(`.perm-input[data-id='${accessId}']`).val(selectedActions);
});


    // Submit form via AJAX
    $('#createRoleForm').on('submit', function(e) {
        e.preventDefault();
        $('.text-danger').text('');

        let valid = true;
        if ($('#txt_role').val().trim() === '') { $('#roleError').text('Role is required.'); valid = false; }
        if ($('#txt_department').val().trim() === '') { $('#deptError').text('Department is required.'); valid = false; }
        if ($('#sel_access').val() === null || $('#sel_access').val().length === 0) { $('#accessError').text('Please select at least one access.'); valid = false; }

        if (valid) {
            let formData = new FormData(this);
            $.ajax({
                url: '../api/save_roles.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    alert('Role created successfully!');
                    $('#createRoleForm')[0].reset();
                    $('#sel_access').val(null).trigger('change');
                    $('#accessControlsList').empty();
                    $('#accessControlsContainer').hide();
                    addedAccessIds = {};
                },
                error: function() {
                    alert('Something went wrong.');
                }
            });
        }
    });
});
