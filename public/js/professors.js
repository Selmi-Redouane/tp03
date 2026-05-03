function loadGrades(student_id){

fetch("api/grades.php?student_id=" + student_id)
.then(res => res.json())
.then(data => {

    let table = document.getElementById("gradesTable");

    table.innerHTML = 
        <tr>
            <th>Course</th>
            <th>Grade</th>
        </tr>
    ;

    data.forEach(g => {
        table.innerHTML += 
            <tr>
                <td>${g.name}</td>
                <td>${g.grade}</td>
            </tr>
        ;
    });

});
}