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
$pageTitle = "Send Notifications";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';


// ✅ Fetch roles from role_management table
$stmt = $pdo->query("SELECT * FROM role_management WHERE deleted_at = 0 ORDER BY r_id ASC ");
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);


// ✅ Fetch notifications
$notifications = $pdo->query("SELECT * FROM notifications ORDER BY sent_date DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Dependencies -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css?v=4.0" rel="stylesheet" />
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css?v=4.0" rel="stylesheet" />

<style>
        body {
            background: #f4f7fb;
        }
        .notify-container {
            max-width: 100%;
            margin: 45px auto;
            border-radius: 10px;
        }
        .page-header {
            color: #12b1eb;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 20px;
            margin-top: -5rem;
        }
        .header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .select-user {
            width: 260px;
            font-size: 1rem;
        }
        #notify_msg {
            width: 100%;
            resize: none;
            border-radius: 7px;
            border: 1px solid #cdedfd;
            min-height: 90px;
            margin-top: 35px;
            font-size: 1.08em;
            padding: 13px 14px;
            box-sizing: border-box;
            background-color: #f0f0f0;
        }
        .send-btn {
            background: #16ace5;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 38px;
            font-size: 1.08em;
            font-weight: 500;
            cursor: pointer;
            margin-top: 8px;
            /* margin-left: 67.5rem; */
            float:right;
            transition: background 0.18s;
        }
        .send-btn:hover {
            background: #0b86b3;
        }
        .table-responsive {
            margin-top: 10px;
        }
        .table thead th {
            background: #11b0e7;
            color: #fff;
            text-align: center;
            border-bottom: none;
        }
        .table-striped>tbody>tr:nth-of-type(even) {
            background-color: #f6fafd;
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
            font-size: 1em;
        }
        .table thead input {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #cbe9fd;
            border-radius: 4px;
            font-size: 0.9em;
            padding: 4px 6px;
        }
        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
        }
        .filter-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 18px 0 8px 0;
        }
        #notifTable_filter label {
            font-weight: 500;
        }
        /* #notifTable_filter input {
            border-radius: 6px;
            border: 1px solid #cbe9fd;
            padding: 6px 10px;
            font-size: 1em;
            margin-left: 5px;
            width: 220px;
            height:32px
        } */
        .date-filter input {
            border-radius: 6px;
            border: 1px solid #cbe9fd;
            height: 36px;
            font-size: 1em;
            padding: 6px 9px;
            width: 160px;
            color:grey
        }
        .date-filter {
            display: flex;
            gap: 10px;
        }
        @media (max-width: 900px) {
            .notify-container { padding: 18px; }
            .header-row, .filter-row { flex-direction: column; align-items: stretch; gap: 8px; }
            .select-user { width: 100%; }
            .send-btn { width: 100%; margin-left: 0; }
            #notifTable_filter input { width: 100%; }
            .date-filter { flex-wrap: wrap; justify-content: flex-start; }
        }
        .select2-container--default .select2-selection--multiple {
            background-color: white;
            border: 1px solid #aaa;
            border-radius: 4px;
            cursor: text;
            position: relative;
            top: 42px;
            width: 50%;
            margin-top: -26px;
            float:right
        }
        .From_date { margin-top: -19px; }
        .To_date { margin-top: -19px; }
        .select2-container--default .select2-dropdown.select2-dropdown--below {
            width: 22.5rem !important;
            margin-left: 34rem !important;
            margin-top: 3rem;
        }

        .read-more {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
            margin-left: 5px;
            cursor: pointer;
        }
        .read-more:hover {
            text-decoration: underline;
        }

        .message-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            justify-content: center;
            align-items: center;
        }

        .message-modal .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            width: 85%;
            position: relative;
            font-size: 1.05em;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            overflow-y: auto;
            max-height: 80vh;
        }

        .close-modal {
            position: absolute;
            top: 8px;
            right: 12px;
            font-size: 22px;
            color: #000;
            cursor: pointer;
            font-weight: bold;
        }


        /* ==========================
        Table Container
        ========================== */
        .table-responsive {
            margin-top: 10px;
            overflow-x: auto;
        }

        /* ==========================
        Table Style
        ========================== */
        #notifTable {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        /* Header */
        #notifTable thead th {
            background-color: #11b0e7;
            color: #fff;
            text-align: center;
            font-weight: 600;
            font-size: 0.95rem;
            border-bottom: none;
            padding: 12px 8px;
        }

        /* Body rows */
        #notifTable tbody td {
            vertical-align: middle;
            /* text-align: center; */
            font-size: 0.95rem;
            padding: 10px 8px;
            border-bottom: 1px solid #e3f1fb;
        }

        /* Stripe rows */
        #notifTable tbody tr:nth-child(even) {
            background-color: #f6fafd;
        }

        /* Hover effect */
        #notifTable tbody tr:hover {
            background-color: #e1f3fc;
        }

        /* Action buttons */
        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            margin: 0 3px;
            padding: 5px;
        }

        .action-btn i {
            font-size: 1rem;
            transition: transform 0.2s;
        }

        .action-btn:hover i {
            transform: scale(1.2);
        }

        /* Search input inside table */
        #notifTable_filter input {
            border-radius: 6px;
            border: 1px solid #cbe9fd;
            padding: 6px 10px;
            font-size: 0.95rem;
            width: 220px;
        }

        /* Column search inputs */
        #notifTable thead input {
            border-radius: 4px;
            border: 1px solid #cbe9fd;
            padding: 4px 6px;
            font-size: 0.9em;
            width: 60%;
            box-sizing: border-box;
        }

        /* Read More link */
        .read-more {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
        }

        .read-more:hover {
            text-decoration: underline;
        }

        /* Responsive adjustments */
        @media (max-width: 900px) {
            #notifTable thead th, #notifTable tbody td {
                font-size: 0.85rem;
                padding: 8px 6px;
            }

            #notifTable_filter input {
                width: 100%;
                margin-left: 0px;
                margin-top: 8px;
            }
        }

        /* Smaller S.No column */
        #notifTable thead th:first-child,
        #notifTable tbody td:first-child {
            font-size: 0.8rem; /* smaller font */
            width: 50px;       /* optional: fixed width for compact look */
            /* text-align: center; */
        }

        /* Action column */
#notifTable thead th:last-child,
#notifTable tbody td:last-child {
    width: 80px; /* fixed width for consistency */
    text-align: center;
}

/* Center other columns */
#notifTable thead th:not(:first-child):not(:last-child),
#notifTable tbody td:not(:first-child):not(:last-child) {
    /* text-align: center; */
    vertical-align: middle;
}

/* Remove search inputs for S.No and Action */
#notifTable thead th:first-child input,
#notifTable thead th:last-child input {
    display: none;
}

/* Optional: adjust the "Read More" link inside table cells */
#notifTable tbody .read-more {
    margin-left: 0;
    display: inline-block;
    font-size: 0.9rem;
}

#notifTable tbody tr:nth-child(odd){
    background-color: #f2f2f2;
}

#notifTable tbody tr:nth-child(even){
    background-color: #f8f8f8;
}


/* ✅ Allow "Sent To" column to wrap and auto-increase height */
#notifTable td:nth-child(2), 
#notifTable th:nth-child(2) {
    white-space: normal !important;   /* Allow wrapping */
    word-wrap: break-word;            /* Break long words if needed */
    max-width: 250px;                 /* Optional: control width to prevent horizontal scroll */
    text-align: left;                 /* Align text neatly */
}

/* Optional: make multi-role lists look tidy */
#notifTable td:nth-child(2) {
    line-height: 1.4;
    padding: 8px 10px;
}

</style>

<div class="main-content">
    <div class="notify-container">
        <div class="header-row">
            <span class="page-header" style="white-space: nowrap;">Send Notifications</span>

            <!-- ✅ Roles fetched from role_management -->
            <select class="form-select select-user" id="roles_select" name="roles_select[]" multiple>
                <option value="all">All</option>
                <?php foreach ($roles as $role): ?>
                    <option value="<?= htmlspecialchars($role['r_id']) ?>">
                        <?= htmlspecialchars($role['role']) ?>
                    </option>
                <?php endforeach; ?>
            </select>


        </div>

        <form id="notifyForm" action="save_notification.php" method="POST">
            <textarea id="notify_msg" name="notify_msg" placeholder="Type Here..." rows="4"></textarea>
            <button type="submit" class="send-btn">Send</button>
        </form>

        <br><br><br>

        <!-- ✅ Filter Row (Search Left + Date Pickers Right, No Labels) -->
        <div class="filter-row d-flex justify-content-between align-items-center mb-2">
            <div id="notifTable_filter_container"></div>
            <div class="date-filter">
                <input type="date" id="from_date" class="form-control From_date" />
                <input type="date" id="to_date" class="form-control To_date" />
            </div>
        </div>

        <!-- Popup Modal -->
        <div id="messageModal" class="message-modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="modal-body"></div>
        </div>
        </div>


        <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle nowrap" id="notifTable" style="width:100%">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Sent To<br><input type="text" placeholder="Search Sent To" class="column-search"/></th>
                <th>Send On<br><input type="text" placeholder="Search Date" class="column-search"/></th>
                <th>Message<br><input type="text" placeholder="Search Message" class="column-search"/></th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($notifications as $sn => $row): ?>
        <?php
        // ✅ Check if notification was sent to ALL
        $checkAll = $pdo->prepare("
            SELECT COUNT(*) FROM notification_recipients 
            WHERE notification_id = ? AND recipient_type = 'all'
        ");
        $checkAll->execute([$row['notification_id']]);
        $isAll = $checkAll->fetchColumn() > 0;
        
        if ($isAll) {
            // If sent to all, show "All"
            $sentToDisplay = 'All';
        } else {
            // Otherwise, fetch role names for specific roles
            $recipientsStmt = $pdo->prepare("
                SELECT rm.role AS role_name
                FROM notification_recipients nr
                INNER JOIN role_management rm 
                    ON nr.recipient_type = 'role' AND nr.recipient_id = rm.r_id
                WHERE nr.notification_id = ?
            ");
            $recipientsStmt->execute([$row['notification_id']]);
            $recipientData = $recipientsStmt->fetchAll(PDO::FETCH_ASSOC);
        
            $sentToList = array_map(fn($r) => $r['role_name'], $recipientData);
            $sentToDisplay = !empty($sentToList) ? implode(', ', $sentToList) : 'N/A';
        }
        
        $fullMessage = html_entity_decode($row['message']);

        $shortMessage = strlen($fullMessage) > 25 ? substr($fullMessage, 0, 25) . '...' : $fullMessage;
        ?>
        <tr data-id="<?= $row['notification_id'] ?>">
            <td><?= $sn + 1 ?></td>
            <td><?= htmlspecialchars($sentToDisplay) ?></td>
            <td><?= date('d-F-Y', strtotime($row['sent_date'])) ?></td>
            <td>
                <span class="short-msg"><?= $shortMessage ?></span>
                <?php if (strlen($fullMessage) > 25): ?>
                    <a href="#" class="read-more" data-message="<?= htmlspecialchars($fullMessage) ?>">Read More</a>
                <?php endif; ?>
            </td>
            <td>
                <button class="action-btn delete-btn" title="Delete">
                    <i class="fa-solid fa-trash" style="color:red;"></i>
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
       </tbody>

    </table>
</div>
    </div>
</div>

<link rel="stylesheet" href="../assets/css/jquery.dataTables.min.css?v=4.0">
<link rel="stylesheet" href="../assets/css/buttons.dataTables.min.css?v=4.0">
<link rel="stylesheet" href="../assets/css/all.min.css?v=4.0">
<link rel="stylesheet" href="../assets/css/style.css?v=4.0">

<!-- JS Dependencies -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js?v=4.0"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js?v=4.0"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js?v=4.0"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js?v=4.0"></script>



<script>
$(document).ready(function() {

    // ==============================
    // Initialize Select2 for roles
    // ==============================
    $('#roles_select').select2({
        placeholder: "Select User",
        width: "100%"
    });

    // ✅ If "All" is selected, show only "All" in UI, but backend will treat it as all roles
    $('#roles_select').on('select2:select', function(e) {
        const selectedValue = e.params.data.id;
        if (selectedValue === 'all') {
            // Deselect all others and keep only 'All' visually selected
            $('#roles_select').val(['all']).trigger('change.select2');
        }
    });
    
    // ✅ If user unselects "All", allow selecting other roles normally
    $('#roles_select').on('select2:unselect', function(e) {
        if (e.params.data.id === 'all') return; // Ignore if unselecting 'all'
        const selected = $('#roles_select').val() || [];
        if (selected.includes('all')) {
            $('#roles_select option[value="all"]').prop('selected', false);
            $('#roles_select').val(selected.filter(v => v !== 'all')).trigger('change.select2');
        }
    });



    // ✅ Remove 'All' if user deselects anything
        $('#roles_select').on('select2:unselect', function() {
            const selected = $('#roles_select').val() || [];
            if (!selected.includes('all')) {
                $('#roles_select option[value="all"]').prop('selected', false);
            }
        });


    // ==============================
    // Initialize DataTable
    // ==============================
    let table = $('#notifTable').DataTable({
        destroy: true,
        responsive: true,
        pageLength: 10,
        lengthChange: false,
        dom: '<"top"f>rt<"bottom"ip>',
        order: [[0, 'asc']]
    });

    // Move and style the global search bar
    $('#notifTable_filter').appendTo('#notifTable_filter_container');
    $('#notifTable_filter input')
        .attr('placeholder', 'Search')
        .addClass('form-control')
        .css({'width': '220px','display': 'inline-block','margin-left': '0px'})
        .off()
        .on('keyup change', function() {
            table.search(this.value).draw();
        });

    // Per-column search
    $('#notifTable thead input.column-search').on('keyup change', function() {
        let idx = $(this).closest('th').index();
        table.column(idx).search(this.value).draw();
    });

    // ==============================
    // Date range filter
    // ==============================
    $.fn.dataTable.ext.search.push(function(settings, data) {
        let min = $('#from_date').val();
        let max = $('#to_date').val();
        let date = data[2]; // "Send On" column

        if (!min && !max) return true;

        let d = moment(date, 'DD-MMMM-YYYY');
        if (!d.isValid()) return false;

        if (min && d.isBefore(moment(min))) return false;
        if (max && d.isAfter(moment(max).endOf('day'))) return false;

        return true;
    });
    $('#from_date, #to_date').on('change', function() {
        table.draw();
    });

    // ==============================
    // Submit notification form via AJAX
    // ==============================
    $('#notifyForm').on('submit', function(e) {
        e.preventDefault();
        const sent_to = $('#roles_select').val();
        const message = $('#notify_msg').val().trim();

        if (!sent_to || sent_to.length === 0) { alert("Please select at least one recipient."); return; }
        if (message === "") { alert("Please enter a message."); return; }

        $.ajax({
            url: "../api/save_notification.php",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify({ message, sent_to }),
            success: function(res) {
                if (res.status === "success") {
                    alert(res.message);
                    $('#notify_msg').val("");
                    $('#roles_select').val(null).trigger('change');
                    location.reload();
                } else {
                    alert("Error: " + res.message);
                }
            },
            error: function(xhr) { alert("Request failed: " + xhr.responseText); }
        });
    });

    // ==============================
    // Delete notification
    // ==============================
    $('#notifTable').on('click', '.delete-btn', function() {
        if (!confirm("Are you sure you want to delete this notification?")) return;

        let row = $(this).closest('tr');
        let notification_id = row.data('id');

        $.ajax({
            url: "../api/delete_notification.php",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify({ notification_id }),
            success: function(res) {
                if (res.status === 'success') {
                    alert(res.message);
                    table.row(row).remove().draw();
                } else {
                    alert("Error: " + res.message);
                }
            },
            error: function(xhr) { alert("Request failed: " + xhr.responseText); }
        });
    });

    // ==============================
    // Read More modal (scoped only to this table)
    // ==============================
$('#notifTable').on('click', '.read-more', function(e) {
    e.preventDefault();
    e.stopPropagation(); // ✅ Prevent triggering global header modal

    const message = $(this).data('message');
    $('#messageModal .modal-body').text(message);
    $('#messageModal').fadeIn(50).css('display', 'flex');
});

    $('#messageModal').on('click', '.close-modal', function() {
        $('#messageModal').fadeOut(50);
    });

    // Close modal when clicking outside
    $('#messageModal').on('click', function(e) {
        if ($(e.target).is('#messageModal')) {
            $(this).fadeOut(50);
        }
    });

});
</script>





<?php require_once '../includes/footer.php'; ?>
