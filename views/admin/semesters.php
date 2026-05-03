<?php
requireRole("admin");

$controller = new AdminController($pdo);

/* ADD */
if (isset($_POST['add'])) {
    $controller->addSemester($_POST['label']);
}

/* FETCH */
$semesters = $controller->semesters();
?>

<h2>📅 Semesters</h2>

<div class="card">
<form method="POST">
    <input name="label" placeholder="Semester name (ex: S1, S2)" required>
    <button name="add">Add</button>
</form>
</div>

<div class="card">
<table>

<tr>
    <th>ID</th>
    <th>Label</th>
    <th>Action</th>
</tr>

<?php foreach($semesters as $s): ?>
<tr>
    <td><?= $s['id'] ?></td>
    <td><?= $s['label'] ?></td>
    <td>
        <a href="index.php?page=semesters&delete=<?= $s['id'] ?>" onclick="return confirm('Delete?')">❌</a>
    </td>
</tr>
<?php endforeach; ?>

</table>
</div>