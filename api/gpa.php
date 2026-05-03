<?php
class GPA {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function calculate($student_id){

        $stmt = $this->pdo->prepare("
            SELECT SUM(grades.grade * courses.credits) AS total_points,
                   SUM(courses.credits) AS total_credits
            FROM grades
            JOIN courses ON courses.id = grades.course_id
            WHERE grades.student_id = ?
        ");

        $stmt->execute([$student_id]);
        $data = $stmt->fetch();

        if($data['total_credits'] == 0){
            return 0;
        }

        return round($data['total_points'] / $data['total_credits'], 2);
    }
}