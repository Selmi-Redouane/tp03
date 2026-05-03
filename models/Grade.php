<?php
class Grade {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function add($student,$course,$semester,$prof,$grade){
        $stmt = $this->pdo->prepare("
            INSERT INTO grades(student_id,course_id,semester_id,professor_id,grade)
            VALUES(?,?,?,?,?)
        ");
        return $stmt->execute([$student,$course,$semester,$prof,$grade]);
    }

    public function getStudentGrades($student){
        $stmt = $this->pdo->prepare("
            SELECT grades.*, courses.name 
            FROM grades
            JOIN courses ON grades.course_id = courses.id
            WHERE student_id=?
        ");
        $stmt->execute([$student]);
        return $stmt->fetchAll();
    }
}