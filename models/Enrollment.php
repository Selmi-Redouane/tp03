<?php
class Enrollment {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function getAll(){
        return $this->pdo->query("
            SELECT enrollments.id, users.name, semesters.label
            FROM enrollments
            JOIN users ON enrollments.student_id = users.id
            JOIN semesters ON enrollments.semester_id = semesters.id
        ")->fetchAll();
    }

    public function create($student,$semester){
        $stmt = $this->pdo->prepare("INSERT INTO enrollments(student_id,semester_id) VALUES(?,?)");
        return $stmt->execute([$student,$semester]);
    }
}