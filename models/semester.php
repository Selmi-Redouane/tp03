<?php
class Semester {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function getAll(){
        return $this->pdo->query("SELECT * FROM semesters")->fetchAll();
    }

    public function create($label){
        $stmt = $this->pdo->prepare("INSERT INTO semesters(label) VALUES(?)");
        return $stmt->execute([$label]);
    }
}