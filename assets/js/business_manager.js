$(document).ready(function () {

    // Initialize DataTable
    let table = $('#businessmanagersTable').DataTable({
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
        stripeClasses: [],
        order: [[4, 'desc']],
        columnDefs: [
            { orderable: false, targets: [0, 5] } // disable S.No and Action sorting
        ]
    });

    // Set placeholder for global search box
    $('.dataTables_filter input').attr('placeholder', '     Search');

    // Apply column search
    $('#businessmanagersTable thead tr:eq(1) th input').on('keyup change', function () {
        table
            .column($(this).parent().index())
            .search(this.value)
            .draw();
    });

    // ✅ Dynamic S.No numbering
    table.on('order.dt search.dt', function () {
        table.column(0, { search: 'applied', order: 'applied' })
            .nodes()
            .each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
    }).draw();

    // =============================
    // DELETE FUNCTIONALITY
    // =============================
    $(document).on('click', '.delete-btn', function () {
        if (!confirm('Are you sure you want to delete this businessmanager?')) return;

        let businessmanagerId = $(this).data('id');
        let row = $(this).closest('tr');

        $.ajax({
            url: '../api/delete_it_manager.php?id=' + it_managerId,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                alert(response.message);
                if (response.status === 'success') {
                    table.row(row).remove().draw();
                }
            },
            error: function () {
                alert('Something went wrong!');
            }
        });
    });

    // =============================
    // CREATE business_manager PAGE VALIDATION
    // =============================
    const form = document.getElementById('business_managerForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            let valid = true;

            // Reset states
            form.querySelectorAll('.form-control, .form-select').forEach(input => {
                input.classList.remove('is-invalid', 'is-valid');
            });

            // Validation rules
            const validateField = (id, condition) => {
                const el = document.getElementById(id);
                if (!condition(el.value.trim())) {
                    el.classList.add('is-invalid');
                    valid = false;
                } else {
                    el.classList.add('is-valid');
                }
            };

            validateField('first_name', v => v !== '');
            validateField('last_name', v => v !== '');
            validateField('email', v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v));
            validateField('roles_access', v => v !== '');
            validateField('user_id', v => v !== '');
            validateField('password', v => v.length >= 4);

            // Submit if valid
            if (valid) form.submit();
        });

        // Success alert (for redirect)
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') === '1') {
            alertify.set('notifier', 'position', 'top-right');
            alertify.success('Business Manager saved successfully!');
            setTimeout(() => {
                window.location.href = 'business_manager.php';
            }, 1500);
        }
    }
});


document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('profile_image');
    const fileLabel = document.getElementById('fileLabel');

    fileInput.addEventListener('change', function () {
        if (fileInput.files.length > 0) {
            fileLabel.textContent = fileInput.files[0].name;
        } else {
            fileLabel.textContent = 'No file chosen';
        }
    });
});


$(document).ready(function() {

    $('#business_managerForm').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: '../api/update_business_manager.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                alertify.dismissAll();
                alertify.message('Updating business_manager...');
            },
            success: function(response) {
                alertify.dismissAll();
                if (response.status === 'success') {
                    alertify.success(response.message || 'Business Manager updated successfully!');
                    setTimeout(() => {
                        window.location.href = 'business_managers.php';
                    }, 1500);
                } else {
                    alertify.error(response.message || 'Something went wrong!');
                }
            },
            error: function(xhr, status, error) {
                alertify.dismissAll();
                alertify.error('An error occurred while updating.');
                console.error(xhr.responseText);
            }
        });
    });

});