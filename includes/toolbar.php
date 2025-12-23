<?php
// includes/toolbar.php
require_once '../config.php';

/* -----------------------------------------
   🔒 SESSION PROTECTION
------------------------------------------ */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

function renderToolbar()
{
    global $pdo;

    // IMPORTANT: GET pathway_id from URL
    $pathwayId = isset($_GET['p_id']) ? (int)$_GET['p_id'] : 0;

    if ($pathwayId === 0) {
        echo "<p style='color:red'>Pathway ID missing</p>";
        return;
    }
    

    // Fetch groups dynamically
    $stmt = $pdo->prepare("SELECT id, group_name FROM groups");
    $stmt->execute();
    $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

<style>
/* ===== SAME EXISTING STYLES (UNCHANGED) ===== */
.toolbar-kit {
    display: flex;
    flex-wrap: wrap;
    background: #f9f9f9;
    border-radius: 8px;
    border: 1px solid #cfd8dc;
    padding: 10px 12px 2px 12px;
    width: 102%;
    box-shadow: 0 0 4px #eee;
}
.toolbar-btn {
    position: relative;
    background: none;
    border: none;
    margin: 5px 8px 5px 0;
    padding: 0;
    cursor: pointer;
}
.toolbar-btn iconify-icon {
    font-size: 28px;
    color: #223344;
    transition: color 0.2s;
}
.toolbar-btn:hover iconify-icon {
    color: #1976d2;
}
.toolbar-btn .tooltip {
    position: absolute;
    left: 50%;
    bottom: -35px;
    transform: translateX(-50%);
    background: #212121;
    color: #fff;
    padding: 3px 10px;
    font-size: 13px;
    border-radius: 4px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s;
    z-index: 8;
}
.toolbar-btn:hover .tooltip {
    opacity: 1;
}
.toolbar-break {
    flex-basis: 100%;
    height: 15px;
}
.label_blue {
    background: #bfeaf8;
    width: 83px;
    height: 60px;
}
.label_big_blue {
    background: #bfeaf8;
    width: 250px;
    height: 60px;
    margin-top: -66px;
    margin-left: 100px;
}
#grouping-section {
    display: none;
    margin-top: 15px;
    background: #f1f7fa;
}
#groupSelect {
    width: 100%;
    padding: 6px 8px;
    border-radius: 5px;
    border: 1px solid #bbb;
    outline: none;
    font-size: 15px;
}
#confirmGroup {
    position: fixed;
    bottom: 20px;
    left: 87%;
    transform: translateX(-50%);
    padding: 10px 20px;
    border: none;
    background: #1976d2;
    color: #fff;
    border-radius: 4px;
    cursor: pointer;
    z-index: 9999;
}
#confirmGroup:hover {
    background: #125ea5;
}
</style>

<!-- ================= TOOLBAR ICONS ================= -->
<div class="toolbar-kit">
<?php
$stmt = $pdo->prepare("SELECT icon_code, tooltip FROM toolbar_icons ORDER BY id ASC");
$stmt->execute();
$icons = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($icons as $index => $icon): ?>
    <button class="toolbar-btn"
        aria-label="<?= htmlspecialchars($icon['tooltip']) ?>"
        <?= ($index === 0 ? 'id="grouping-btn"' : '') ?>
        <?= ($index === 1 ? 'id="question-btn"' : '') ?>>
        <iconify-icon icon="<?= htmlspecialchars($icon['icon_code']) ?>"></iconify-icon>
        <span class="tooltip"><?= htmlspecialchars($icon['tooltip']) ?></span>
    </button>
    <?php if ($index === 8) echo '<div class="toolbar-break"></div>'; ?>
<?php endforeach; ?>
</div>

<br><br>

<!-- EXISTING BLUE LABELS -->
<div>
    <label class="form-control label_blue mt-5"></label>
    <span><label class="form-control label_big_blue mt-5"></label></span>
</div>

<!-- ================= QUESTION SECTION (NEW, NO STYLE CHANGE) ================= -->
<div id="question-section" style="display:none; margin-top:10px;">

    <input type="text"
           id="question_input"
           class="form-control"
           placeholder="Type question">

    <input type="text"
           id="keyword_input"
           class="form-control mt-2"
           placeholder="Keyword">

    <button id="saveQuestionBtn"
            class="btn btn-primary btn-sm mt-2">
        Save
    </button>

    <hr>

    <div id="savedQuestions"></div>
</div>

<!-- ================= GROUP DROPDOWN (EXISTING) ================= -->
<div id="grouping-section">
    <select id="groupSelect">
        <option value="">Select Group</option>
        <?php foreach ($groups as $group): ?>
            <option value="<?= $group['id'] ?>">
                <?= htmlspecialchars($group['group_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button id="confirmGroup">Save</button>
</div>

<script>
// ========== GROUP FUNCTIONALITY ==========
const groupBtn = document.getElementById('grouping-btn');
const groupSection = document.getElementById('grouping-section');
const confirmBtn = document.getElementById('confirmGroup');
const groupSelect = document.getElementById('groupSelect');

if (groupBtn && groupSection) {
    groupBtn.addEventListener('click', () => {
        groupSection.style.display =
            groupSection.style.display === "block" ? "none" : "block";
    });
}

if (confirmBtn) {
    confirmBtn.addEventListener('click', () => {

        const group_id = groupSelect.value;
        if (!group_id) {
            alert("Please select a group first.");
            return;
        }

        const fd = new URLSearchParams();
        fd.append("pathway_id", "<?= (int)$pathwayId ?>");
        fd.append("group_id", group_id);

        fetch("../api/save_group.php", {
            method: "POST",
            body: fd
        })
        .then(res => res.json())
        .then(data => {
            if (data.status) {
                window.location.reload();
            } else {
                alert(data.message || "Group save failed");
            }
        });
    });
}


// ========== QUESTION FUNCTIONALITY ==========
const questionBtn      = document.getElementById('question-btn');
const questionSection  = document.getElementById('question-section');
const saveQuestionBtn  = document.getElementById('saveQuestionBtn');
const questionInput    = document.getElementById('question_input');
const keywordInput     = document.getElementById('keyword_input');
const savedQuestions   = document.getElementById('savedQuestions');

if (questionBtn) {
    questionBtn.addEventListener('click', () => {

        const parentNode = localStorage.getItem('selectedGroupNode');

        if (!parentNode) {
            alert("Please select a group first");
            return;
        }

        questionSection.style.display = "block";
        loadQuestions(parentNode);
    });
}

if (saveQuestionBtn) {
    saveQuestionBtn.addEventListener('click', () => {

        const question = questionInput.value.trim();
        const keyword  = keywordInput.value.trim();
        const parentNode = localStorage.getItem('selectedGroupNode');

        if (!question || !keyword) {
            alert("Enter question and keyword");
            return;
        }

        const fd = new URLSearchParams();
        fd.append("pathway_id", "<?= (int)$pathwayId ?>");
        fd.append("parent_node", parentNode);
        fd.append("question_text", question);
        fd.append("keyword", keyword);

        fetch("../api/save_question.php", {
            method: "POST",
            body: fd
        })
        .then(res => res.json())
        .then(data => {
            if (!data.status) {
                alert(data.message || "Question save failed");
                return;
            }

            questionInput.value = '';
            keywordInput.value = '';

            loadQuestions(parentNode);

            // ✅ refresh left-side UI
            if (window.parent && window.parent !== window) {
                window.parent.location.reload();
            }
        });
    });
}


// ========== LOAD QUESTIONS ==========
function loadQuestions(parentNode) {
    fetch(`../api/get_questions.php?pathway_id=<?= (int)$pathwayId ?>&parent_node=${parentNode}`)
        .then(res => res.json())
        .then(list => {

            if (!savedQuestions) return;

            let html = "";
            list.forEach(q => {
                html += `
                    <div style="display:flex; gap:10px; margin-bottom:6px;">
                        <strong>${q.node_number}</strong>
                        <span>${q.question_text}</span>
                    </div>
                `;
            });

            savedQuestions.innerHTML = html || "<em>No questions</em>";
        });
}
</script>


<?php
}
?>
