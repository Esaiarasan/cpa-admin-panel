<?php
// content_upload.php (Main Tab UI)

 $pageTitle = "Content Upload";

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Content Upload</title>
    <link rel="stylesheet" href="../assets/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../assets/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../assets/css/all.min1.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .upload-content-btn {
            background-color: #0063a5;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
            display: inline-block;
            text-decoration: none;
        }
        
        .upload-content-btn:hover {
            background-color: #004a80;
            color: white;
        }
        
        .filter-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .left-filters, .right-filters {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .search-box {
            width: 200px;
        }
        
        .date-input {
            padding: 6px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .export-btn {
            background: none;
            border: 1px solid #ddd;
            padding: 6px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .export-btn:hover {
            background-color: #f5f5f5;
        }
        
        .btn-icon {
            background: none;
            border: none;
            padding: 5px;
            cursor: pointer;
        }
        
        .tab-content {
            padding-top: 20px;
        }
        
        .table-responsive {
            margin-top: 15px;
        }
        
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 15px;
        }
        
        /* Custom search icon */
        .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            z-index: 10;
        }
        
        .search-container {
            position: relative;
        }
        
        .dataTables_filter input {
            padding-left: 30px;
        }
        
        /* Action button styles */
        .action-eye { color: black !important; }
        .action-edit { color: #0063a5 !important; }
        .action-delete { color: red !important; }
        
        /* Date range styling */
        .date-range-container {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        /* Ensure upload button stays with tab content */
        .tab-pane {
            position: relative;
        }
    </style>
</head>
<body>
<div class="main-content">
    <div class="page-header">
        <button class="back-btn" onclick="window.history.back();">&larr;</button>
        <span class="page-title">Content Upload</span>
    </div>
    <br><br>
    
    <!-- Tabs -->
    <ul class="nav content-tabs mb-3" id="contentTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="help-tab" data-bs-toggle="tab" data-bs-target="#help" type="button" role="tab">Help</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="library-tab" data-bs-toggle="tab" data-bs-target="#library" type="button" role="tab">Library</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="banner-tab" data-bs-toggle="tab" data-bs-target="#banner" type="button" role="tab">Banner</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="images-tab" data-bs-toggle="tab" data-bs-target="#images" type="button" role="tab">Images</button>
        </li>
    </ul>
    
    <!-- Tab Content -->
    <div class="tab-content" id="contentTabContent">
        <!-- Help Tab -->
        <div class="tab-pane fade show active" id="help" role="tabpanel" aria-labelledby="help-tab">
            <button class="upload-content-btn mb-2" onclick="window.location.href='help_content.php'">Upload Content</button>
            
            <div class="table-responsive mt-3">
                <table id="helpTable" class="table table-bordered table-striped table-hover align-middle nowrap" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">S.No</th>
                            <th style="width: 15%;">Title</th>
                            <th style="width: 20%;">Video</th>
                            <th style="width: 25%;">Description</th>
                            <th style="width: 15%;">Steps to follow</th>
                            <th style="width: 20%;">Action</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th><input type="text" class="column-search" placeholder="Search Title"></th>
                            <th><input type="text" class="column-search" placeholder="Search Video"></th>
                            <th><input type="text" class="column-search" placeholder="Search Description"></th>
                            <th><input type="text" class="column-search" placeholder="Search Steps"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Getting Started Guide</td>
                            <td>intro_video.mp4</td>
                            <td>Introduction to the platform</td>
                            <td>Step 1: Login, Step 2: Navigate</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn-icon action-eye" title="View"><i class="fa-regular fa-eye"></i></button>
                                    <button class="btn-icon action-edit" title="Edit"><i class="fa-solid fa-pen"></i></button>
                                    <button class="btn-icon action-delete" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Advanced Features</td>
                            <td>advanced_tutorial.mp4</td>
                            <td>Learn advanced platform features</td>
                            <td>Step 1: Settings, Step 2: Configure</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn-icon action-eye" title="View"><i class="fa-regular fa-eye"></i></button>
                                    <button class="btn-icon action-edit" title="Edit"><i class="fa-solid fa-pen"></i></button>
                                    <button class="btn-icon action-delete" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Library Tab -->
        <div class="tab-pane fade" id="library" role="tabpanel" aria-labelledby="library-tab">
            <button class="upload-content-btn mb-2" onclick="window.location.href='library_content.php'">Upload Content</button>
            
            <div class="table-responsive">
                <table id="libraryTable" class="table table-bordered table-striped table-hover align-middle nowrap" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>S.No</th>
                            <th>Title</th>
                            <th>File</th>
                            <th>Description</th>
                            <th>Steps to Follow</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th><input type="text" class="column-search" placeholder="Search Title"></th>
                            <th><input type="text" class="column-search" placeholder="Search File"></th>
                            <th><input type="text" class="column-search" placeholder="Search Description"></th>
                            <th><input type="text" class="column-search" placeholder="Search Steps"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>User Manual</td>
                            <td>user_manual.pdf</td>
                            <td>Complete user documentation</td>
                            <td>Download and read</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn-icon action-eye" title="View"><i class="fa-regular fa-eye"></i></button>
                                    <button class="btn-icon action-edit" title="Edit"><i class="fa-solid fa-pen"></i></button>
                                    <button class="btn-icon action-delete" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>API Documentation</td>
                            <td>api_docs.pdf</td>
                            <td>API integration guide</td>
                            <td>Follow integration steps</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn-icon action-eye" title="View"><i class="fa-regular fa-eye"></i></button>
                                    <button class="btn-icon action-edit" title="Edit"><i class="fa-solid fa-pen"></i></button>
                                    <button class="btn-icon action-delete" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Banner Tab -->
        <div class="tab-pane fade" id="banner" role="tabpanel" aria-labelledby="banner-tab">
            <button class="upload-content-btn mb-2" onclick="window.location.href='banner_upload.php'">Upload Content</button>
            
            <div class="table-responsive">
                <table id="bannerTable" class="table table-bordered table-striped table-hover align-middle nowrap" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>S.No</th>
                            <th>Title</th>
                            <th>File</th>
                            <th>Description</th>
                            <th>Steps to Follow</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th><input type="text" class="column-search" placeholder="Search Title"></th>
                            <th><input type="text" class="column-search" placeholder="Search File"></th>
                            <th><input type="text" class="column-search" placeholder="Search Description"></th>
                            <th><input type="text" class="column-search" placeholder="Search Steps"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Homepage Banner</td>
                            <td>banner_home.jpg</td>
                            <td>Homepage Main Banner</td>
                            <td>Steps to upload</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn-icon action-eye" title="View"><i class="fa-regular fa-eye"></i></button>
                                    <button class="btn-icon action-edit" title="Edit"><i class="fa-solid fa-pen"></i></button>
                                    <button class="btn-icon action-delete" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Promo Banner</td>
                            <td>promo_banner.png</td>
                            <td>Special Promotion Banner</td>
                            <td>Steps to upload</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn-icon action-eye" title="View"><i class="fa-regular fa-eye"></i></button>
                                    <button class="btn-icon action-edit" title="Edit"><i class="fa-solid fa-pen"></i></button>
                                    <button class="btn-icon action-delete" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Images Tab -->
        <div class="tab-pane fade" id="images" role="tabpanel" aria-labelledby="images-tab">
            <button class="upload-content-btn mb-2" onclick="window.location.href='images_content.php'">Upload Content</button>
            
            <div class="table-responsive">
                <table id="imagesTable" class="table table-bordered table-striped table-hover align-middle nowrap" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>S.No</th>
                            <th>Title</th>
                            <th>File</th>
                            <th>Description</th>
                            <th>Steps to Follow</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th><input type="text" class="column-search" placeholder="Search Title"></th>
                            <th><input type="text" class="column-search" placeholder="Search File"></th>
                            <th><input type="text" class="column-search" placeholder="Search Description"></th>
                            <th><input type="text" class="column-search" placeholder="Search Steps"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Product Image 1</td>
                            <td>product_img1.jpg</td>
                            <td>Main product showcase image</td>
                            <td>Upload high resolution image</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn-icon action-eye" title="View"><i class="fa-regular fa-eye"></i></button>
                                    <button class="btn-icon action-edit" title="Edit"><i class="fa-solid fa-pen"></i></button>
                                    <button class="btn-icon action-delete" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Product Image 2</td>
                            <td>product_img2.png</td>
                            <td>Secondary product image</td>
                            <td>Upload in PNG format</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn-icon action-eye" title="View"><i class="fa-regular fa-eye"></i></button>
                                    <button class="btn-icon action-edit" title="Edit"><i class="fa-solid fa-pen"></i></button>
                                    <button class="btn-icon action-delete" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- DataTables CSS -->
<link rel="stylesheet" href="../assets/css/jquery.dataTables.min.css?v=4.0">
<link rel="stylesheet" href="../assets/css/buttons.dataTables.min.css?v=4.0">
<link rel="stylesheet" href="../assets/css/all.min.css?v=4.0">
<link rel="stylesheet" href="../assets/css/style.css?v=4.0">

<!-- jQuery & DataTables JS -->
<script src="../assets/js/jquery.min.js?v=4.0"></script>
<script src="../assets/js/jquery.dataTables.min.js?v=4.0"></script>
<script src="../assets/js/dataTables.buttons.min.js?v=4.0"></script>
<script src="../assets/js/buttons.html5.min.js?v=4.0"></script>
<script src="../assets/js/buttons.print.min.js?v=4.0"></script>
<script src="../assets/js/jszip.min.js?v=4.0"></script>
<script src="../assets/js/pdfmake.min.js?v=4.0"></script>
<script src="../assets/js/vfs_fonts.js?v=4.0"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
 $(document).ready(function() {
    // Common DataTable options
    const commonOptions = {
        dom: '<"d-flex flex-wrap justify-content-between mb-2"<"search-container position-relative"f><"date-range-container d-flex align-items-center gap-2"><"dt-buttons"B>>rt<"bottom d-flex flex-wrap justify-content-between"ip>',
        buttons: [
            { extend: 'print', text: '<i class="fa fa-print"></i>', className: 'btn btn-sm btn-dark' },
            { extend: 'pdfHtml5', text: '<i class="fa fa-file-pdf"></i>', className: 'btn btn-sm btn-dark' },
            { extend: 'excelHtml5', text: '<i class="fa fa-file-excel"></i>', className: 'btn btn-sm btn-dark' }
        ],
        pageLength: 10,
        orderCellsTop: true,
        fixedHeader: false,
        autoWidth: false,
        stripeClasses: [],
        responsive: true,
        scrollX: true,
        columnDefs: [
            { orderable: false, targets: [0, 5] } // disable S.No and Action sorting
        ],
        initComplete: function() {
            const api = this.api();
            const tableId = api.table().node().id;
            
            // Add search icon to global search
            const searchContainer = $(api.table().container()).find('.search-container');
            searchContainer.prepend('<i class="fa fa-search search-icon"></i>');
            
            // Set placeholder for global search box
            $(api.table().container()).find('.dataTables_filter input').attr('placeholder', 'Search');
            
            // Hide icon and placeholder when typing
            $(api.table().container()).find('.dataTables_filter input').on('focus', function() {
                $(this).siblings('.search-icon').hide();
                if ($(this).val() === '') {
                    $(this).attr('placeholder', '');
                }
            }).on('blur', function() {
                if ($(this).val() === '') {
                    $(this).siblings('.search-icon').show();
                    $(this).attr('placeholder', 'Search');
                }
            });
            
            // Add date range filters without labels
            const dateRangeContainer = $(api.table().container()).find('.date-range-container');
            dateRangeContainer.append(`
                <input type="date" class="form-control form-control-sm date-from" style="width:150px">
                <input type="date" class="form-control form-control-sm date-to" style="width:150px">
            `);
            
            // Apply column search
            $('.column-search', api.table().header()).on('keyup change', function() {
                const index = $(this).parent().index();
                api.column(index).search(this.value).draw();
            });
            
            // Date filtering
            $(`.date-from, .date-to`).on('change', function() {
                const fromDate = $(`.date-from`).val();
                const toDate = $(`.date-to`).val();
                
                if (fromDate || toDate) {
                    // In a real application, you would have a date column to filter on
                    // For now, this is a placeholder implementation
                    console.log(`Filtering ${tableId} from ${fromDate} to ${toDate}`);
                    // api.column(dateColumnIndex).search(dateRange).draw();
                }
            });
        }
    };
    
    // Initialize DataTables for each table
    const helpTable = $('#helpTable').DataTable(commonOptions);
    const libraryTable = $('#libraryTable').DataTable(commonOptions);
    const bannerTable = $('#bannerTable').DataTable(commonOptions);
    const imagesTable = $('#imagesTable').DataTable(commonOptions);
    
    // Dynamic S.No numbering for all tables
    [helpTable, libraryTable, bannerTable, imagesTable].forEach(table => {
        table.on('order.dt search.dt', function() {
            table.column(0, { search: 'applied', order: 'applied' })
                .nodes()
                .each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
        }).draw();
    });
    
    // Tab switching event to redraw DataTables
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        const target = $(e.target).attr("data-bs-target");
        
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
</script>

</body>
</html>

<?php require_once '../includes/footer.php'; ?>