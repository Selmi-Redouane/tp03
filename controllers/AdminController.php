<?php
require_once "models/User.php";
require_once "models/Course.php";
require_once "models/Semester.php";
require_once "models/Enrollment.php";

class AdminController {

    private $user;
    private $course;
    private $semester;
    private $enroll;

    public function __construct($pdo){
        $this->user = new User($pdo);
        $this->course = new Course($pdo);
        $this->semester = new Semester($pdo);
        $this->enroll = new Enrollment($pdo);
    }

    // USERS
    public function students(){
        return $this->user->getStudents();
    }

    public function professors(){
        return $this->user->getProfessors();
    }

   public function addUser($data){

    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    return $this->user->create(
        $data['name'],
        $data['email'],
        $hashedPassword,
        $data['role']
    );
}

    public function deleteUser($id){
    return $this->user->delete($id);
    }
    // COURSES
    public function courses(){
        return $this->course->getAll();
    }

    public function addCourse($data){
        return $this->course->create(
            $data['name'],
            $data['credits'],
            $data['semester_id']
        );
    }

    // SEMESTERS
    public function semesters(){
        return $this->semester->getAll();
    }

    public function addSemester($label){
        return $this->semester->create($label);
    }

    // ENROLLMENTS
    public function enrollments(){
        return $this->enroll->getAll();
    }

    public function addEnrollment($data){
        return $this->enroll->create(
            $data['student_id'],
            $data['semester_id']
        );
    }
}