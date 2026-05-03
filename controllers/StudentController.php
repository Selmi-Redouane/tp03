<?php
require_once "models/Grade.php";
require_once "models/GPA.php";

class StudentController {

    private $grade;
    private $gpa;

    public function __construct($pdo){
        $this->grade = new Grade($pdo);
        $this->gpa = new GPA($pdo);
    }

    public function myGrades(){
        return $this->grade->getStudentGrades($_SESSION['user']['id']);
    }

    public function myGPA(){
        return $this->gpa->calculate($_SESSION['user']['id']);
    }
}