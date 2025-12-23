$(document).ready(function () {

    // ✅ 1️⃣ Clone header row for column-wise search
    $('#AdministratorsTable thead tr')
        .clone(true)
        .appendTo('#AdministratorsTable thead')
        .addClass('search-row');

    $('#AdministratorsTable thead tr.search-row th').each(function (i) {
        // Enable search for specific columns (User ID, Name, Role, DOJ)
        if (i > 0 && i < 5) {
            $(this).html('<input type="text" placeholder="Search" class="form-control form-control-sm" />');
        } else {
            $(this).html('');
        }
    });

    // ✅ 2️⃣ Initialize DataTable
    let table = $('#AdministratorsTable').DataTable({
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
        lengthMenu: [5, 10, 25, 50, 100],
        autoWidth: false,
        order: [[4, 'desc']],
        columnDefs: [
            { orderable: false, targets: [0, 5] } // Disable sorting on S.No and Action
        ],
        stripeClasses: ['table-white', 'table-secondary'],
        initComplete: function () {
            // ✅ Make column search functional after table load
            this.api()
                .columns()
                .every(function (i) {
                    let column = this;
                    $('input', $('.search-row th').eq(i)).on('keyup change clear', function () {
                        if (column.search() !== this.value) {
                            column.search(this.value).draw();
                        }
                    });
                });
        }
    });

    // ✅ 3️⃣ Placeholder for global search
    $('.dataTables_filter input').attr('placeholder', 'Search administrators...');

    // ✅ 4️⃣ Dynamic Serial Numbers
    table.on('order.dt search.dt', function () {
        table.column(0, { search: 'applied', order: 'applied' })
            .nodes()
            .each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
    }).draw();

    // ✅ 5️⃣ Delete Administrator
    $(document).on('click', '.delete-btn', function () {
        const administrator_id = $(this).data('id');
        if (!administrator_id) return;

        if (confirm('Are you sure you want to delete this Administrator?')) {
            $.ajax({
                url: '../api/delete_administrator.php',
                type: 'POST',
                data: { administrator_id: administrator_id },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        table.row($(this).parents('tr')).remove().draw(); // remove row instantly
                    } else {
                        alert(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    alert('An error occurred while deleting the administrator.');
                }
            });
        }
    });

    // ✅ 6️⃣ Handle Update Form (if edit page loaded)
    $('#administratorForm').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: '../api/update_administrator.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function () {
                alertify.dismissAll();
                alertify.message('Updating administrator...');
            },
            success: function (response) {
                alertify.dismissAll();
                if (response.status === 'success') {
                    alertify.success(response.message || 'Administrator updated successfully!');
                    setTimeout(() => {
                        window.location.href = 'administrators.php';
                    }, 1500);
                } else {
                    alertify.error(response.message || 'Something went wrong!');
                }
            },
            error: function (xhr, status, error) {
                alertify.dismissAll();
                alertify.error('An error occurred while updating.');
                console.error(xhr.responseText);
            }
        });
    });

});
