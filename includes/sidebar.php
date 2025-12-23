<?php
// sidebar.php
?>

<div class="sidebar" id="sidebar">
    <!-- Logo + Toggle -->
    <div class="logo d-flex justify-content-between align-items-center">
        <h2><?php echo SITE_NAME; ?></h2>
        <button id="sidebarToggle" class="sidebar-toggle" aria-label="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <ul class="nav-menu">
        <?php
        // Database connection
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($mysqli->connect_errno) {
            echo "<li><span style='color:red;'>DB Connection Error</span></li>";
        } else {
            $result = $mysqli->query("SELECT * FROM navigation_items ORDER BY nav_id ASC");
            if ($result && $result->num_rows > 0) {
                $currentFile = basename($_SERVER['PHP_SELF']);

                while ($row = $result->fetch_assoc()) {
                    $navName = $row['nav_name'];
                    $navLink = $row['nav_link'];
                    $navIcon = $row['nav_icon'] ?: 'fa-circle';
                    $subLinks = $row['sub_nav_link']; // e.g. "pages/add_roles.php,pages/edit_roles.php"

                    // --- Active Logic ---
                    $isActive = '';
                    $navFile = basename($navLink);
                    $subMatch = false;

                    if (!empty($subLinks)) {
                        $subArray = array_map('trim', explode(',', $subLinks));
                        foreach ($subArray as $sub) {
                            if ($currentFile === basename($sub)) {
                                $subMatch = true;
                                break;
                            }
                        }
                    }

                    if ($currentFile === $navFile || $subMatch) {
                        $isActive = 'active';
                    }

                    // --- Output each nav item ---
                    echo '<li class="' . $isActive . '">';
                    echo '<a href="' . SITE_URL . $navLink . '">';

                    // Detect icon type (Iconify, Boxicons, FontAwesome)
                    if (strpos($navIcon, ':') !== false) {
                        echo '<iconify-icon class="nav-icon" icon="' . htmlspecialchars($navIcon) . '" width="22" height="22"></iconify-icon>';
                    } elseif (strpos($navIcon, 'bxs-') === 0 || strpos($navIcon, 'bx-') === 0) {
                        echo '<i class="bx nav-icon ' . htmlspecialchars($navIcon) . '"></i>';
                    } else {
                        echo '<i class="fas nav-icon ' . htmlspecialchars($navIcon) . '"></i>';
                    }

                    echo '<span class="nav-text">' . htmlspecialchars($navName) . '</span>';
                    echo '</a>';
                    echo '</li>';
                }
            } else {
                echo '<li><span style="color:lightgray;">No navigation items found</span></li>';
            }
            $mysqli->close();
        }
        ?>
    </ul>
</div>

<!-- Dependencies -->
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css?v=4.0" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css?v=4.0" rel="stylesheet">
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js?v=4.0"></script>

<!-- Sidebar Toggle Logic -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const mainContent = document.querySelector('.main-content');

    toggleBtn.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');
        if (mainContent) mainContent.classList.toggle('sidebar-collapsed');
    });
});
</script>
