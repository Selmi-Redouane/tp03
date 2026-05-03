<?php
class Course {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function getAll(){
        return $this->pdo->query("
            SELECT courses.*, semesters.label 
            FROM courses 
            JOIN semesters ON courses.semester_id = semesters.id
        ")->fetchAll();
    }

    public function create($name,$credits,$semester){
        $stmt = $this->pdo->prepare("INSERT INTO courses(name,credits,semester_id) VALUES(?,?,?)");
        return $stmt->execute([$name,$credits,$semester]);
    }
}