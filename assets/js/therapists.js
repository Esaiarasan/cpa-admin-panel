$(document).ready(function () {

    // Initialize DataTable
    let table = $('#therapistTable').DataTable({
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
    $('#therapistTable thead tr:eq(1) th input').on('keyup change', function () {
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
        if (!confirm('Are you sure you want to delete this therapist?')) return;

        let therapistId = $(this).data('id');
        let row = $(this).closest('tr');

        $.ajax({
            url: '../api/delete_therapist.php?id=' + therapistId,
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
    // CREATE THERAPIST PAGE VALIDATION
    // =============================
    const form = document.getElementById('therapistForm');
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
            validateField('moodle_id', v => v !== '');
            validateField('password', v => v.length >= 4);

            // Submit if valid
            if (valid) form.submit();
        });

        // Success alert (for redirect)
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') === '1') {
            alertify.set('notifier', 'position', 'top-right');
            alertify.success('Therapist saved successfully!');
            setTimeout(() => {
                window.location.href = 'therapist.php';
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

    $('#therapistForm').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: '../api/update_therapist.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                alertify.dismissAll();
                alertify.message('Updating therapist...');
            },
            success: function(response) {
                alertify.dismissAll();
                if (response.status === 'success') {
                    alertify.success(response.message || 'Therapist updated successfully!');
                    setTimeout(() => {
                        window.location.href = 'therapist.php';
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