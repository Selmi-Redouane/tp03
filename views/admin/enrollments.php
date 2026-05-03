<?php
requireRole("admin");

$controller = new AdminController($pdo);

/* ADD */
if (isset($_POST['add'])) {
    $controller->addEnrollment([
        'student_id' => $_POST['student_id'],
        'semester_id' => $_POST['semester_id']
    ]);
}

/* FETCH */
$data = $controller->enrollments();
$students = $controller->students();
$semesters = $controller->semesters();
?>
<h2>📌 Enrollments Management</h2>

<!-- ADD FORM -->
<div class="card">
<form method="POST">

    <select name="student_id">
        <?php foreach($students as $s): ?>
            <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
        <?php endforeach; ?>
    </select>

    <select name="semester_id">
        <?php foreach($semesters as $s): ?>
            <option value="<?= $s['id'] ?>"><?= $s['label'] ?></option>
        <?php endforeach; ?>
    </select>

    <button name="add">Add Enrollment</button>

</form>
</div>

<!-- SEARCH -->
<input type="text" id="searchEnroll" placeholder="🔍 Search...">

<!-- TABLE -->
<div class="card">
<table id="enrollTable">

<tr>
    <th>ID</th>
    <th>Student</th>
    <th>Semester</th>
    <th>Action</th>
</tr>

<?php foreach($data as $d): ?>
<tr>
    <td><?= $d['id'] ?></td>
    <td><?= $d['name'] ?></td>
    <td><?= $d['label'] ?></td>
    <td>
        <a href="index.php?page=enrollments&delete=<?= $d['id'] ?>" onclick="return confirm('Delete?')">❌</a>
    </td>
</tr>
<?php endforeach; ?>

</table>
</div>

<!-- SEARCH SCRIPT -->
<script>
document.getElementById("searchEnroll").addEventListener("keyup", function() {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll("#enrollTable tr");

    rows.forEach((row, index) => {
        if(index === 0) return;
        row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
    });
});
</script>