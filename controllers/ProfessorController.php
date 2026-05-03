<?php
require_once "models/Grade.php";
require_once "models/Assignment.php";
require_once "models/User.php";

class ProfessorController {

    private $grade;
    private $assignment;
    private $user;

    public function __construct($pdo){
        $this->grade = new Grade($pdo);
        $this->assignment = new Assignment($pdo);
        $this->user = new User($pdo);
    }

    public function students(){
        return $this->user->getStudents();
    }

    public function assignments(){
        return $this->assignment->getAll();
    }

    public function addGrade($data){
        return $this->grade->add(
            $data['student_id'],
            $data['course_id'],
            $data['semester_id'],
            $_SESSION['user']['id'],
            $data['grade']
        );
    }
}