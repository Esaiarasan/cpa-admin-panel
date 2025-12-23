
 $(document).ready(function() {
    // Initialize DataTables for each table
    var helpTable = $('#helpTable').DataTable({
        dom: 'rtip',
        pageLength: 10,
        responsive: true,
        initComplete: function() {
            // Add custom search functionality
            $('#globalSearchHelp').on('keyup', function() {
                helpTable.search(this.value).draw();
            });
        }
    });
    
    var libraryTable = $('#libraryTable').DataTable({
        dom: 'rtip',
        pageLength: 10,
        responsive: true,
        initComplete: function() {
            // Add custom search functionality
            $('#globalSearchLibrary').on('keyup', function() {
                libraryTable.search(this.value).draw();
            });
        }
    });
    
    var bannerTable = $('#bannerTable').DataTable({
        dom: 'rtip',
        pageLength: 10,
        responsive: true,
        initComplete: function() {
            // Add custom search functionality
            $('#globalSearchBanner').on('keyup', function() {
                bannerTable.search(this.value).draw();
            });
        }
    });
    
    var imagesTable = $('#imagesTable').DataTable({
        dom: 'rtip',
        pageLength: 10,
        responsive: true,
        initComplete: function() {
            // Add custom search functionality
            $('#globalSearchImages').on('keyup', function() {
                imagesTable.search(this.value).draw();
            });
        }
    });
    
    // Export functionality for Help tab
    $('#printHelp').on('click', function() {
        helpTable.button().add(0, {
            extend: 'print',
            text: '<i class="fa fa-print"></i>',
            titleAttr: 'Print'
        });
        helpTable.button(0).trigger();
    });
    
    $('#pdfHelp').on('click', function() {
        helpTable.button().add(0, {
            extend: 'pdfHtml5',
            text: '<i class="fa fa-file-pdf"></i>',
            titleAttr: 'Export to PDF'
        });
        helpTable.button(0).trigger();
    });
    
    $('#excelHelp').on('click', function() {
        helpTable.button().add(0, {
            extend: 'excelHtml5',
            text: '<i class="fa fa-file-excel"></i>',
            titleAttr: 'Export to Excel'
        });
        helpTable.button(0).trigger();
    });
    
    // Export functionality for Library tab
    $('#printLibrary').on('click', function() {
        libraryTable.button().add(0, {
            extend: 'print',
            text: '<i class="fa fa-print"></i>',
            titleAttr: 'Print'
        });
        libraryTable.button(0).trigger();
    });
    
    $('#pdfLibrary').on('click', function() {
        libraryTable.button().add(0, {
            extend: 'pdfHtml5',
            text: '<i class="fa fa-file-pdf"></i>',
            titleAttr: 'Export to PDF'
        });
        libraryTable.button(0).trigger();
    });
    
    $('#excelLibrary').on('click', function() {
        libraryTable.button().add(0, {
            extend: 'excelHtml5',
            text: '<i class="fa fa-file-excel"></i>',
            titleAttr: 'Export to Excel'
        });
        libraryTable.button(0).trigger();
    });
    
    // Export functionality for Banner tab
    $('#printBanner').on('click', function() {
        bannerTable.button().add(0, {
            extend: 'print',
            text: '<i class="fa fa-print"></i>',
            titleAttr: 'Print'
        });
        bannerTable.button(0).trigger();
    });
    
    $('#pdfBanner').on('click', function() {
        bannerTable.button().add(0, {
            extend: 'pdfHtml5',
            text: '<i class="fa fa-file-pdf"></i>',
            titleAttr: 'Export to PDF'
        });
        bannerTable.button(0).trigger();
    });
    
    $('#excelBanner').on('click', function() {
        bannerTable.button().add(0, {
            extend: 'excelHtml5',
            text: '<i class="fa fa-file-excel"></i>',
            titleAttr: 'Export to Excel'
        });
        bannerTable.button(0).trigger();
    });
    
    // Export functionality for Images tab
    $('#printImages').on('click', function() {
        imagesTable.button().add(0, {
            extend: 'print',
            text: '<i class="fa fa-print"></i>',
            titleAttr: 'Print'
        });
        imagesTable.button(0).trigger();
    });
    
    $('#pdfImages').on('click', function() {
        imagesTable.button().add(0, {
            extend: 'pdfHtml5',
            text: '<i class="fa fa-file-pdf"></i>',
            titleAttr: 'Export to PDF'
        });
        imagesTable.button(0).trigger();
    });
    
    $('#excelImages').on('click', function() {
        imagesTable.button().add(0, {
            extend: 'excelHtml5',
            text: '<i class="fa fa-file-excel"></i>',
            titleAttr: 'Export to Excel'
        });
        imagesTable.button(0).trigger();
    });
    
    // Date filter functionality (simplified example)
    $('.date-input').on('change', function() {
        // This is a placeholder for date filtering functionality
        // In a real application, you would implement server-side filtering
        // or client-side filtering based on date columns
        console.log('Date filter changed');
    });
    
    // Tab switching event to redraw DataTables
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        var target = $(e.target).attr("data-bs-target");
        
        if (target === "#help") {
            helpTable.columns.adjust().responsive.recalc();
        } else if (target === "#library") {
            libraryTable.columns.adjust().responsive.recalc();
        } else if (target === "#banner") {
            bannerTable.columns.adjust().responsive.recalc();
        } else if (target === "#images") {
            imagesTable.columns.adjust().responsive.recalc();
        }
    });
});
