<?php
require "config.php";
requireRole("student");

$student_id = $_SESSION['user']['id'];

/* ========== FETCH HISTORY ========== */
$data = $pdo->prepare("
    SELECT semesters.label, courses.name, courses.credits, grades.grade
    FROM grades
    JOIN courses ON courses.id = grades.course_id
    JOIN semesters ON semesters.id = courses.semester_id
    WHERE grades.student_id = ?
    ORDER BY semesters.id
");
$data->execute([$student_id]);
$rows = $data->fetchAll();
?>

<h2>📜 Academic History</h2>

<input type="text" id="searchHistory" placeholder="🔍 Search...">

<div class="card">
<table id="historyTable">

<tr>
    <th>Semester</th>
    <th>Course</th>
    <th>Credits</th>
    <th>Grade</th>
</tr>

<?php foreach($rows as $r): ?>
<tr>
    <td><?= $r['label'] ?></td>
    <td><?= $r['name'] ?></td>
    <td><?= $r['credits'] ?></td>
    <td><?= $r['grade'] ?></td>
</tr>
<?php endforeach; ?>

</table>
</div>

<!-- SEARCH -->
<script>
document.getElementById("searchHistory").addEventListener("keyup", function() {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll("#historyTable tr");

    rows.forEach((row, index) => {
        if(index === 0) return;
        row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
    });
});
</script>