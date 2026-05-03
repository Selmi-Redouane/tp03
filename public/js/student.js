fetch("api/students.php")
.then(res => res.json())
.then(data => {

    let table = document.getElementById("studentsTable");

    table.innerHTML = 
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
        </tr>
    ;

    data.forEach(s => {
        table.innerHTML += 
            <tr>
                <td>${s.id}</td>
                <td>${s.name}</td>
                <td>${s.email}</td>
            </tr>
        ;
    });

});