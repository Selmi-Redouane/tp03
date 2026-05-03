
<?php
requireRole("professor");

$controller = new AdminController($pdo);

/* ADD */
if (isset($_POST['add'])) {
    $controller->addUser([
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'role' => 'professor'
    ]);
}

/* DELETE */
if (isset($_GET['delete'])) {
    $controller->deleteUser($_GET['delete']);
}

/* FETCH */
$professors = $controller->professors();
?>
<h2>👨‍🏫 Professors Management</h2>

<!-- ADD FORM -->
<div class="card">
<form method="POST">

    <input name="name" placeholder="Name" required>
    <input name="email" placeholder="Email" required>
    <input name="password" placeholder="Password" required>

    <button name="add">Add Professor</button>

</form>
</div>

<!-- SEARCH -->
<input type="text" id="searchProf" placeholder="🔍 Search professor...">

<!-- TABLE -->
<div class="card">
<table id="profTable">

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Action</th>
</tr>

<?php foreach($professors as $p): ?>
<tr>
    <td><?= $p['id'] ?></td>
    <td><?= $p['name'] ?></td>
    <td><?= $p['email'] ?></td>
    <td>
        <a href="index.php?page=professors&delete=<?= $p['id'] ?>" onclick="return confirm('Delete?')">❌</a>
    </td>
</tr>
<?php endforeach; ?>

</table>
</div>

<!-- SEARCH SCRIPT -->
<script>
document.getElementById("searchProf").addEventListener("keyup", function() {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll("#profTable tr");

    rows.forEach((row, index) => {
        if(index === 0) return;
        row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
    });
});
</script>