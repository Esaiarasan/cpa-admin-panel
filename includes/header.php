<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>
    <?php echo isset($pageTitle) ? $pageTitle . ' - ' . SITE_NAME : SITE_NAME; ?>
</title>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js?v=5.0"></script>
<!-- Iconify -->
<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css?v=5.0">
<!-- Site CSS -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css?v=5.0">



</head>
<body>

<!-- Top Header -->
<div class="main-header d-flex justify-content-between align-items-center p-2">
    <div class="d-flex align-items-center gap-3">
        <!-- Hamburger Button -->
        <button id="sidebarToggle" class="hamburger-btn" aria-label="Toggle Sidebar">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <!-- Show Role -->
        <div class="user-role">Super Admin</div>
        <div class="breadcrumb"></div>
    </div>

    <!-- Right-side Icons -->
    <div class="d-flex align-items-center ms-auto gap-3">
        <!-- Notification Icon -->
        <div id="notificationIcon" style="position: relative;">
            <span class="notification-card">
                <iconify-icon icon="clarity:bell-line" width="22" height="22" style="color:#333;"></iconify-icon>
            </span>
        </div>


        <!-- Notification Dropdown -->
        <div id="notificationDropdown" class="notification-dropdown" style="display:none;">
            <div class="notification-header d-flex justify-content-end align-items-center px-2 py-1">
                <button id="collapseBtn" class="tiny-btn"><i class="fas fa-chevron-up"></i></button>
                <button id="expandBtn" class="tiny-btn"><i class="fas fa-chevron-down"></i></button>
            </div>

            <div class="notification-list-container">
                <div class="notification-list">
                    <?php
                    $notifications = [
                        ['name'=>'SuperAdmin','message'=>'Therapy session tomorrow at 10:00 AM for the new client John Doe at the wellness center.','date'=>'02-05-2025','time'=>'10:00 AM','seen'=>false],
                        ['name'=>'SuperAdmin','message'=>'Invoice #123 is approved successfully by the finance department.','date'=>'01-05-2025','time'=>'02:30 PM','seen'=>true],
                        ['name'=>'SuperAdmin','message'=>'New client registered in the system for future therapy sessions.','date'=>'30-04-2025','time'=>'11:15 AM','seen'=>false],
                    ];
                    foreach($notifications as $note): ?>
                        <div class="notification-card <?= $note['seen'] ? 'seen' : 'unseen' ?>"
                            onclick="showNotificationPopup('<?= addslashes($note['message']) ?>','<?= $note['date'] ?>','<?= $note['time'] ?>')">

                            <div class="notification-header d-flex justify-content-between align-items-center">
                                <span class="notification-title"><?= htmlspecialchars($note['name']) ?></span>
                                <span class="notification-meta"><?= $note['date'] ?> | <?= $note['time'] ?></span>
                            </div>
                            <p class="notification-message"><?= htmlspecialchars($note['message']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Profile Menu -->
        <div id="profileMenu" style="position:relative;">
            <span>
                <img src="<?php echo SITE_URL; ?>assets/images/profile.png" alt="Profile" style="cursor:pointer;"/>
            </span>
            <div id="profileDetails">
                <div><strong>Name:</strong> Nina</div>
                <div><strong>Role:</strong> Super Admin</div>
                <div><strong>Email:</strong> LaurenHarrison3@gmail.com</div>
                <div><strong>Password:</strong> Nina@251</div>
                <div><strong>Phone:</strong> 0123456789</div>
                <button onclick="window.location.href='<?php echo SITE_URL; ?>api/logout.php'">
                    Logout <i class="fas fa-sign-out-alt"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Notification Modal -->
<div id="notificationModal" style="display:none;">
    <div class="modal-content">
        <span class="close-btn" onclick="closeNotificationPopup()">&times;</span>
        <div class="modal-header d-flex justify-content-between align-items-center">
            <div class="modal-title" id="modalTitle">SuperAdmin</div>
            <div class="modal-meta" id="modalMeta"></div>
        </div>
        <div class="modal-message" id="modalMessage"></div>
    </div>
</div>

<!-- ✅ JS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const profileImg = document.querySelector('#profileMenu img');
    const profileDetails = document.getElementById('profileDetails');
    const notificationIcon = document.getElementById('notificationIcon');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const listContainer = document.querySelector('.notification-list-container');
    let dropdownVisible = false;

    // Profile Toggle
    profileImg.addEventListener('click', (e) => {
        e.stopPropagation();
        if (notificationDropdown.style.display === 'flex') notificationDropdown.style.display = 'none';
        profileDetails.style.display = profileDetails.style.display === 'block' ? 'none' : 'block';
    });

    // Notification Toggle
    notificationIcon.addEventListener('click', function(e) {
        e.stopPropagation();
        if (profileDetails.style.display === 'block') profileDetails.style.display = 'none';
        dropdownVisible = !dropdownVisible;
        notificationDropdown.style.display = dropdownVisible ? 'flex' : 'none';
    });

    document.addEventListener('click', (e) => {
        if (!notificationDropdown.contains(e.target) && !notificationIcon.contains(e.target)) {
            notificationDropdown.style.display = 'none';
            dropdownVisible = false;
        }
    });

    // Expand / Collapse
    document.getElementById('collapseBtn').addEventListener('click', () => {
        listContainer.style.maxHeight = '100px';
        listContainer.style.overflowY = 'hidden';
    });
    document.getElementById('expandBtn').addEventListener('click', () => {
        listContainer.style.maxHeight = '300px';
        listContainer.style.overflowY = 'auto';
    });

    // Read more for long messages
    document.querySelectorAll('.notification-message').forEach(msg => {
        const text = msg.textContent.trim();
        if (text.length > 25) {
            const shortText = text.substring(0, 25) + '...';
            const card = msg.closest('.notification-card');
            const date = card.querySelector('.notification-meta').textContent.split('|')[0].trim();
            const time = card.querySelector('.notification-meta').textContent.split('|')[1].trim();
            msg.innerHTML = `
                <span class="short-text">${shortText}</span>
                <a href="#" class="read-more" data-full="${text}" data-date="${date}" data-time="${time}">Read more</a>
            `;
        }
    });

    // Read more click → open popup
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('read-more')) {
            e.preventDefault();
            e.stopPropagation();
            const msg = e.target.getAttribute('data-full');
            const date = e.target.getAttribute('data-date');
            const time = e.target.getAttribute('data-time');
            showNotificationPopup(msg, date, time);
        }
    });
});

// Popup Functions
function showNotificationPopup(message, date, time) {
    document.getElementById('modalTitle').textContent = 'SuperAdmin';
    document.getElementById('modalMeta').textContent = `${date} | ${time}`;
    document.getElementById('modalMessage').textContent = message;
    document.getElementById('notificationModal').style.display = 'flex';
}

function closeNotificationPopup() {
    document.getElementById('notificationModal').style.display = 'none';
}
</script>

<!-- ✅ CSS -->

</body>
</html>
