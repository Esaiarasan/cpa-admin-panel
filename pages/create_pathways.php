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


$pageTitle = "Create Pathway";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

?>

<div class="main-content">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h1>Create Pathway</h1>
    </div>

    <div class=" mt-3">
        <div class="">
            <form id="pathwayForm">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="appointment" class="form-label">Appointment</label>
                        <select class="form-select" id="appointment">
                            <option value="initial">Initial</option>
                            <option value="review">Review</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="age" class="form-label">Age (months)</label>
                        <input type="text" class="form-control" id="age" placeholder="Enter age (23-36)">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="goalDomain" class="form-label">Goal Domain</label>
                        <input type="text" class="form-control" id="goalDomain" placeholder="Enter goal domain">
                    </div>
                    <div class="col-md-6">
                        <label for="pathwayId" class="form-label">Pathway Name</label>
                        <input type="text" class="form-control" id="pathwayId" value="">
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="button" id="startBtn" class="btn btn-primary px-5">Start</button>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
.text-center {
    text-align: center !important;
}

</style>


<script>
document.getElementById('startBtn').addEventListener('click', function() {

    const appointment_type = document.getElementById('appointment').value.trim();
    const age_group = document.getElementById('age').value.trim();
    const goal_domain = document.getElementById('goalDomain').value.trim();
    const pathway_name = document.getElementById('pathwayId').value.trim();

    if (!appointment_type || !age_group || !goal_domain || !pathway_name) {
        alert("All fields are required.");
        return;
    }

    // Send to backend
    fetch("../api/create_pathway.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `appointment_type=${appointment_type}&age_group=${age_group}&goal_domain=${goal_domain}&pathway_name=${pathway_name}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status) {
            // Redirect with the new clpa_id
            window.location.href = `pathway_page.php?p_id=${data.clpa_id}&age=${encodeURIComponent(age_group)}`;
        } else {
            alert(data.message);
        }
    });
});

</script>

<?php require_once '../includes/footer.php'; ?>
