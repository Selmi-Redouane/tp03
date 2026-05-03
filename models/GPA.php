<?php
class GPA {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function calculate($student){
        $stmt = $this->pdo->prepare("
            SELECT AVG(grade) as gpa FROM grades WHERE student_id=?
        ");
        $stmt->execute([$student]);
        return $stmt->fetch();
    }
}