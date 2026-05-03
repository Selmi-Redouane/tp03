
<?php
requireRole("student");

$controller = new AdminController($pdo);

/* ADD */
if (isset($_POST['add'])) {
    $controller->addUser([
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'role' => 'student'
    ]);
}

/* DELETE */
if (isset($_GET['delete'])) {
    $controller->deleteUser($_GET['delete']);
}

/* FETCH */
$students = $controller->students();
?>
<h2>🧑‍🎓 Students Management</h2>

<!-- ADD FORM -->
<div class="card">
<form method="POST">

    <input name="name" placeholder="Name" required>
    <input name="email" placeholder="Email" required>
    <input name="password" placeholder="Password" required>

    <button name="add">Add Student</button>

</form>
</div>

<!-- SEARCH -->
<input type="text" id="search" placeholder="🔍 Search student...">

<!-- TABLE -->
<div class="card">
<table id="studentsTable">

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Action</th>
</tr>

<?php foreach($students as $s): ?>
<tr>
    <td><?= $s['id'] ?></td>
    <td><?= $s['name'] ?></td>
    <td><?= $s['email'] ?></td>
    <td>
        <a href="index.php?page=students&delete=<?= $s['id'] ?>" onclick="return confirm('Delete?')">❌</a>
    </td>
</tr>
<?php endforeach; ?>

</table>
</div>

<!-- JS -->
<script>
document.getElementById("search").addEventListener("keyup", function() {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll("#studentsTable tr");

    rows.forEach((row, index) => {
        if(index === 0) return;
        row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
    });
});
</script>
<script src="public/js/student.js"></script>