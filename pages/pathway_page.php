<?php
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$pageTitle = "Pathway Page";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

/* -----------------------------
   URL VALUES
----------------------------- */
$pathwayNameId = $_GET['p_id'] ?? '';
$age = $_GET['age'] ?? '';

/* -----------------------------
   GET REAL pathway_id (PK)
----------------------------- */
$stmt = $pdo->prepare("
    SELECT pathway_id
    FROM pathways
    WHERE pathway_name_id = ?
    LIMIT 1
");
$stmt->execute([$pathwayNameId]);
$pathwayId = $stmt->fetchColumn();

if (!$pathwayId) {
    die('Invalid Pathway');
}
?>

<style>
#toolbar-panel {
    position: fixed;
    top: 4rem;
    right: 0;
    width: 380px;
    height: 100%;
    background: #ffffff;
    border-left: 1px solid #ddd;
    padding: 15px;
    overflow-y: auto;
}

.main-content {
    margin-left: 250px;
    margin-right: 380px;
    background-color: #bfeaf8;
    padding: 20px;
    min-height: 100vh;
}

/* #pathway-display {
    background: #08a6e8;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    font-weight: bold;
    max-width: 60%;
    margin-left: 50px;
    margin-bottom: 20px;
} */

.group-row {
    display: flex;
    align-items: flex-start;
    margin-left: 50px;
    margin-bottom: 15px;
}

.group-btn {
    background: #ffffff;
    border: 1px solid #cfd8dc;
    padding: 8px 12px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    white-space: normal;
    max-width: 320px;
    color  :black!important
}

.group-btn.active {
    border: 2px solid #0aa0df;
}

.question-area {
    margin-left: 20px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.question-card {
    display: flex;
    align-items: center;
    gap: 8px;
}

.question-btn {
    background: #ffffff;
    border: 1px solid #cfd8dc;
    padding: 6px 10px;
    border-radius: 5px;
    font-size: 13px;
    cursor: pointer;
    color:black!important
}

.question-kw {
    background: #ffffff;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: bold;
}
</style>

<div class="main-content">



<div class="page-header">
        <button class="back-btn" onclick="window.history.back();">&larr;</button>
        <span id="pathway-display" class="page-title">Pathway ID: <?= htmlspecialchars($pathwayNameId) ?> |
        Age: <?= htmlspecialchars($age) ?> months</span>
</div>

<div id="group-container">

<?php
$stmt = $pdo->prepare("
    SELECT node_number, group_name
    FROM pathways
    WHERE pathway_name_id = ?
    ORDER BY node_number
");
$stmt->execute([$pathwayNameId]);

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $g):
?>
<div class="group-row">

    <button class="group-btn"
            data-node="<?= htmlspecialchars($g['node_number']) ?>">
        <?= htmlspecialchars($g['node_number']) ?>
        <?= htmlspecialchars($g['group_name']) ?>
    </button>

    <div class="question-area"
         data-group="<?= htmlspecialchars($g['node_number']) ?>">
    </div>

</div>
<?php endforeach; ?>

</div>
</div>

<div id="toolbar-panel">
    <?php
        $_GET['p_id'] = $pathwayNameId; // IMPORTANT
        include '../includes/toolbar_loader.php';
    ?>
</div>


<script>
// GROUP SELECT ONLY
document.querySelectorAll('.group-btn').forEach(btn => {
    btn.addEventListener('click', () => {

        document.querySelectorAll('.group-btn')
            .forEach(b => b.classList.remove('active'));

        btn.classList.add('active');

        const node = btn.dataset.node;
        localStorage.setItem('selectedGroupNode', node);

        loadQuestions(node);
    });
});

function loadQuestions(node) {

    fetch(`../api/get_questions.php?pathway_id=<?= $pathwayId ?>&parent_node=${node}`)
    .then(res => res.json())
    .then(list => {

        const container = document.querySelector(
            `.question-area[data-group="${node}"]`
        );

        if (!container) return;
        container.innerHTML = '';

        list.forEach(q => {
            container.innerHTML += `
                <div class="question-card">
                    <button class="question-btn">
                        ${q.node_number} ${q.question_text}
                    </button>
                    <div class="question-kw">
                        KW<br>${q.keywords}
                    </div>
                </div>
            `;
        });
    });
}
</script>

<?php require_once '../includes/footer.php'; ?>
