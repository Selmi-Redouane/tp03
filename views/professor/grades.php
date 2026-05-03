<?php
require "config.php";
requireRole("professor");

/* ========== ADD GRADE ========== */
if (isset($_POST['add'])) {

    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $grade = $_POST['grade'];

    $stmt = $pdo->prepare("INSERT INTO grades(student_id,course_id,grade) VALUES(?,?,?)");
    $stmt->execute([$student_id,$course_id,$grade]);
}

/* ========== FETCH DATA ========== */
$students = $pdo->query("SELECT * FROM users WHERE role='student'")->fetchAll();
$courses = $pdo->query("SELECT * FROM courses")->fetchAll();

$data = $pdo->query("
    SELECT grades.id, users.name, courses.name AS course, grades.grade
    FROM grades
    JOIN users ON users.id = grades.student_id
    JOIN courses ON courses.id = grades.course_id
")->fetchAll();
?>

<h2>👨‍🏫 Grades Management</h2>

<!-- ADD FORM -->
<div class="card">
<form method="POST">

    <!-- STUDENT -->
    <select name="student_id">
        <?php foreach($students as $s): ?>
            <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
        <?php endforeach; ?>
    </select>

    <!-- COURSE -->
    <select name="course_id">
        <?php foreach($courses as $c): ?>
            <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
        <?php endforeach; ?>
    </select>

    <!-- GRADE -->
    <input type="number" step="0.1" name="grade" placeholder="Grade" required>

    <button name="add">Add Grade</button>

</form>
</div>

<!-- SEARCH -->
<input type="text" id="searchGrade" placeholder="🔍 Search...">

<!-- TABLE -->
<div class="card">
<table id="gradeTable">

<tr>
    <th>ID</th>
    <th>Student</th>
    <th>Course</th>
    <th>Grade</th>
</tr>

<?php foreach($data as $g): ?>
<tr>
    <td><?= $g['id'] ?></td>
    <td><?= $g['name'] ?></td>
    <td><?= $g['course'] ?></td>
    <td><?= $g['grade'] ?></td>
</tr>
<?php endforeach; ?>

</table>
</div>

<!-- SEARCH SCRIPT -->
<script>
document.getElementById("searchGrade").addEventListener("keyup", function() {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll("#gradeTable tr");

    rows.forEach((row, index) => {
        if(index === 0) return;
        row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
    });
});
</script>
<script src="public/js/professor.js"></script>