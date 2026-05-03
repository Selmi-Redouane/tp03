<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// استدعاء الملفات الأساسية للاتصال بقاعدة البيانات والنظام
require_once "config.php";
require_once "models/User.php";
require_once "controllers/AuthController.php";

$page = $_GET['page'] ?? 'login';
$user_role = $_SESSION['user']['role'] ?? null;
$userModel = new User($pdo);

// نظام حماية المسارات: يمنع الدخول لأي صفحة دون تسجيل دخول ما عدا صفحات البداية[cite: 13]
if (!isset($_SESSION['user']) && !in_array($page, ['login', 'doLogin', 'add_user', 'save_user'])) {
    header("Location: index.php?page=login");
    exit;
}

// محرك الصفحات الشامل لجميع الأدوار[cite: 13]
switch($page){
    // --- نظام الدخول والخروج ---
    case "login":
        if(isset($_SESSION['user'])) { header("Location: index.php?page=dashboard"); exit; }
        $content = "views/login.php"; 
        break;

    case "doLogin":
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            (new AuthController($pdo))->login(trim($_POST['email'] ?? ''), $_POST['password'] ?? '');
        } 
        exit;

    case "logout":
        (new AuthController($pdo))->logout(); 
        exit;

    // --- نظام لوحة التحكم (الرئيسية) لكل مستخدم[cite: 13] ---
    case "dashboard":
        if ($user_role == 'admin') {
            $content = "views/admin/dashboard.php";
        } elseif ($user_role == 'professor') {
            $myCourses = $userModel->getProfessorCourses($_SESSION['user']['id']);
            $content = "views/professor/dashboard.php";
        } else {
            // صفحة الطالب الشخصية
            $content = "views/student/dashboard.php"; 
        }
        break;

    // --- بوابة الطالب وإصلاح الأيقونات (التقويم، الاختبارات، المعدل، الطعون)[cite: 11, 13] ---
    case "student_report":
    case "calendar": 
    case "exams":    
    case "grades":   
    case "appeals":  
        $student_id = $_SESSION['user']['id'];
        $semester = $_GET['semester'] ?? 1;
        $report = $userModel->getStudentFullReport($student_id, $semester);
        $rank = $userModel->getStudentRank($student_id); 
        $content = "views/student/report.php"; // تأكد أن الملف بهذا الاسم في مجلد student
        break;

    case "submit_appeal":
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel->addAppeal($_POST['student_id'], $_POST['course_id'], $_POST['message']);
            header("Location: index.php?page=student_report&success=1");
        }
        break;

    case "update_profile":
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel->updateProfile($_SESSION['user']['id'], $_POST['name'], $_POST['email'], $_POST['new_password']);
            $_SESSION['user']['name'] = $_POST['name'];
            $_SESSION['user']['email'] = $_POST['email'];
            header("Location: index.php?page=dashboard&success=profile_updated");
        }
        exit;

    // --- نظام الأستاذ (Professor)[cite: 13] ---
    case "professor_dashboard":
        if ($user_role !== 'professor') die("Access Denied");
        $myCourses = $userModel->getProfessorCourses($_SESSION['user']['id']); 
        $content = "views/professor/dashboard.php";
        break;

    case "professor_grades":
        if ($user_role !== 'professor') die("Access Denied.");
        $myCourses = $userModel->getProfessorCourses($_SESSION['user']['id']);
        $selected_course_id = $_GET['course_id'] ?? null;
        $students = $selected_course_id ? $userModel->getStudentsGradesForCourse($selected_course_id) : [];
        $content = "views/professor/enter_grades.php"; 
        break;

    case "save_all_grades":
        if ($user_role !== 'professor') die("Access Denied");
        $course_id = $_POST['course_id'];
        if (isset($_POST['grades']) && is_array($_POST['grades'])) {
            foreach ($_POST['grades'] as $student_id => $types) {
                foreach ($types as $type => $value) {
                    if ($value !== "" && $value !== null) {
                        $userModel->saveStudentGrade($student_id, $course_id, 1, $_SESSION['user']['id'], $value, $type);
                    }
                }
            }
        }
        header("Location: index.php?page=professor_grades&course_id=" . $course_id . "&status=success");
        exit;

    // --- نظام الإدارة (Admin)[cite: 13] ---
    case "add_user":
        $content = "views/admin/add_user.php"; break;

    case "save_user":
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($userModel->createWithStatus($_POST['name'], $_POST['email'], $_POST['password'], $_POST['role'], 0)) {
                echo "<script>alert('تم إرسال طلبك بنجاح!'); window.location.href='index.php?page=login';</script>";
            }
        } 
        exit;

    case "user_requests":
        if ($user_role !== 'admin') die("Access Denied.");
        $pendingUsers = $userModel->getPendingUsers();
        $content = "views/admin/requests.php"; 
        break;

    case "approve_user":
        if ($user_role !== 'admin') die("Access Denied.");
        if ($userModel->approveUser($_GET['id'])) { header("Location: index.php?page=user_requests"); }
        exit;

    case "manage_academy":
        if ($user_role !== 'admin') die("Access Denied.");
        $content = "views/admin/manage_all.php"; 
        break;

    case "save_semester":
        if ($user_role !== 'admin') die("Access Denied.");
        if ($userModel->addSemester($_POST['label'], $_POST['year'])) { header("Location: index.php?page=manage_academy"); }
        exit;

    case "save_course":
        if ($user_role !== 'admin') die("Access Denied.");
        if ($userModel->addCourse($_POST['name'], $_POST['semester_id'], $_POST['credits'], $_POST['coefficient'])) { header("Location: index.php?page=manage_academy"); }
        exit;

    case "assign_course":
        if ($user_role !== 'admin') die("Access Denied.");
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($userModel->assignProfessorToCourse($_POST['professor_id'], $_POST['course_id'], $_POST['semester_id'])) {
                echo "<script>alert('تم التكليف بنجاح!'); window.location.href='index.php?page=manage_academy';</script>";
            }
        } 
        exit;

    case "view_student":
        if ($user_role !== 'admin') die("Access Denied.");
        $student_id = $_GET['id'];
        $report = $userModel->getStudentFullReport($student_id);
        $content = "views/admin/student_report.php";
        break;

    default: 
        $content = "views/login.php"; 
        break;
}

// استدعاء القالب الرئيسي (Layout) مع التحقق من الاسم لتجنب خطأ No such file[cite: 12, 13]
if (file_exists("views/layout.php")) {
    include "views/layout.php";
} elseif (file_exists("views/layout_6.php")) {
    include "views/layout_6.php";
} else {
    // في حال عدم وجود ملف layout، يتم عرض المحتوى مباشرة
    if(isset($content) && file_exists($content)) { 
        include $content; 
    } else {
        echo "Error: Content file not found.";
    }
}
?>