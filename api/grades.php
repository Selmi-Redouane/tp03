<?php
require "../config.php";
require "../models/Grade.php";

header("Content-Type: application/json");

$grade = new Grade($pdo);

echo json_encode($grade->getStudentGrades($_GET['student_id']));