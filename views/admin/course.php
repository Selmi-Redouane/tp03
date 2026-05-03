<?php
requireRole("admin");

$controller = new AdminController($pdo);

/* ADD */
if (isset($_POST['add'])) {
    $controller->addCourse([
        'name' => $_POST['name'],
        'credits' => $_POST['credits'],
        'semester_id' => $_POST['semester_id']
    ]);
}

/* FETCH */
$courses = $controller->courses();
$semesters = $controller->semesters();
?>

<h2>📚 Courses Management</h2>

<!-- ADD FORM -->
<div class="card">
<form method="POST">

    <input name="name" placeholder="Course name" required>

    <input name="credits" type="number" placeholder="Credits" required>

    <select name="semester_id">
        <?php foreach($semesters as $s): ?>
            <option value="<?= $s['id'] ?>"><?= $s['label'] ?></option>
        <?php endforeach; ?>
    </select>

    <button name="add">Add Course</button>

</form>
</div>

<!-- SEARCH -->
<input type="text" id="searchCourse" placeholder="🔍 Search course...">

<!-- TABLE -->
<div class="card">
<table id="courseTable">

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Credits</th>
    <th>Semester</th>
    <th>Action</th>
</tr>

<?php foreach($courses as $c): ?>
<tr>
    <td><?= $c['id'] ?></td>
    <td><?= $c['name'] ?></td>
    <td><?= $c['credits'] ?></td>
    <td><?= $c['label'] ?></td>
    <td>
        <a href="index.php?page=courses&delete=<?= $c['id'] ?>" onclick="return confirm('Delete?')">❌</a>
    </td>
</tr>
<?php endforeach; ?>

</table>
</div>

<!-- SEARCH SCRIPT -->
<script>
document.getElementById("searchCourse").addEventListener("keyup", function() {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll("#courseTable tr");

    rows.forEach((row, index) => {
        if(index === 0) return;
        row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
    });
});
</script>