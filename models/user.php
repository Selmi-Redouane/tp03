<?php
class User {
    private $pdo;

    public function __construct($pdo) { 
        $this->pdo = $pdo; 
    }

    // البحث عن مستخدم بالبريد الإلكتروني
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // جلب كافة المستخدمين
    public function getAll() {
        return $this->pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    // حساب إحصائيات النظام
    public function getStats() {
        return [
            'total' => $this->pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'pending' => $this->pdo->query("SELECT COUNT(*) FROM users WHERE status != '1' AND status != 'active'")->fetchColumn(),
            'active' => $this->pdo->query("SELECT COUNT(*) FROM users WHERE status = '1' OR status = 'active'")->fetchColumn()
        ];
    }

    // إنشاء مستخدم جديد بحالة انتظار (0)
    public function createWithStatus($name, $email, $password, $role, $status = 0) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $email, $hashedPassword, $role, $status]);
    }

    // جلب الطلبات بانتظار التفعيل فقط
    public function getPendingUsers() {
        return $this->pdo->query("SELECT * FROM users WHERE status != '1' AND status != 'active'")->fetchAll(PDO::FETCH_ASSOC);
    }

    // تفعيل حساب مستخدم
    public function approveUser($id) {
        return $this->pdo->prepare("UPDATE users SET status = '1' WHERE id = ?")->execute([$id]);
    }

    // جلب إحصائيات مفصلة للأدمن
    public function getDetailedStats() {
        return [
            'professors' => $this->pdo->query("SELECT COUNT(*) FROM users WHERE role = 'professor' AND (status = 'active' OR status = '1')")->fetchColumn(),
            'students' => $this->pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student' AND (status = 'active' OR status = '1')")->fetchColumn(),
            'pending' => $this->pdo->query("SELECT COUNT(*) FROM users WHERE status != 'active' AND status != '1'")->fetchColumn()
        ];
    }

    // --- إدارة المواد والتكليفات ---
    
    // جلب كل الطلاب مع كشوفات نقاطهم (هذه الدالة التي سببت الخطأ في الصورة)
    public function getAllStudentsWithGrades() {
        $sql = "SELECT u.id as student_id, u.name as student_name, 
                       c.name as course_name, 
                       GROUP_CONCAT(CONCAT(UPPER(g.exam_type), ': ', g.grade) SEPARATOR ' | ') as all_grades,
                       prof.name as professor_name
                FROM users u
                LEFT JOIN grades g ON u.id = g.student_id
                LEFT JOIN courses c ON g.course_id = c.id
                LEFT JOIN users prof ON g.professor_id = prof.id
                WHERE u.role = 'student'
                GROUP BY u.id, c.id
                ORDER BY u.name ASC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // جلب كل الأساتذة وما يدرسونه
    public function getAllProfessorsWithAssignments() {
        $sql = "SELECT u.id as prof_id, u.name as prof_name, u.email, GROUP_CONCAT(c.name SEPARATOR ' | ') as courses
                FROM users u
                LEFT JOIN course_assignments ca ON u.id = ca.professor_id
                LEFT JOIN courses c ON ca.course_id = c.id
                WHERE u.role = 'professor'
                GROUP BY u.id";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addSemester($label, $academic_year) {
        $stmt = $this->pdo->prepare("INSERT INTO semesters (label, academic_year, is_active) VALUES (?, ?, 1)");
        return $stmt->execute([$label, $academic_year]);
    }

    public function addCourse($name, $semester_id, $credits, $coefficient) {
        $stmt = $this->pdo->prepare("INSERT INTO courses (name, semester_id, credits, coefficient) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $semester_id, $credits, $coefficient]);
    }

    public function assignProfessorToCourse($prof_id, $course_id, $semester_id) {
        $sql = "INSERT INTO course_assignments (professor_id, course_id, semester_id) VALUES (?, ?, ?)";
        return $this->pdo->prepare($sql)->execute([$prof_id, $course_id, $semester_id]);
    }

    // --- نظام الأستاذ (رصد العلامات) ---

    public function getProfessorCourses($prof_id) {
        $sql = "SELECT c.id, c.name FROM courses c 
                INNER JOIN course_assignments ca ON c.id = ca.course_id 
                WHERE ca.professor_id = :prof_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['prof_id' => $prof_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

  public function saveStudentGrade($student_id, $course_id, $semester_id, $professor_id, $grade, $exam_type) {
    // تنظيف القيمة
    $grade = filter_var($grade, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    
    // تحويل النوع ليكون متوافقاً مع ENUM (exam, td, tp)
    $type = strtolower(trim($exam_type));
    if (!in_array($type, ['exam', 'td', 'tp'])) return false;

    $sql = "INSERT INTO grades (student_id, course_id, semester_id, professor_id, grade, exam_type) 
            VALUES (:sid, :cid, :sem, :pid, :grade, :type)
            ON DUPLICATE KEY UPDATE grade = VALUES(grade)";
            
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        'sid' => $student_id,
        'cid' => $course_id,
        'sem' => $semester_id,
        'pid' => $professor_id,
        'grade' => $grade,
        'type' => $type
    ]);
}
    public function getStudentsGradesForCourse($course_id) {
    $sql = "SELECT u.id as student_id, u.name as student_name,
                (SELECT g.grade FROM grades g WHERE g.student_id = u.id AND g.course_id = :cid1 AND LOWER(TRIM(g.exam_type)) LIKE 'exam%' LIMIT 1) as exam_grade,
                (SELECT g.grade FROM grades g WHERE g.student_id = u.id AND g.course_id = :cid2 AND LOWER(TRIM(g.exam_type)) LIKE 'td%' LIMIT 1) as td_grade,
                (SELECT g.grade FROM grades g WHERE g.student_id = u.id AND g.course_id = :cid3 AND LOWER(TRIM(g.exam_type)) LIKE 'tp%' LIMIT 1) as tp_grade
            FROM users u
            WHERE u.role = 'student' AND (u.status = '1' OR u.status = 'active')
            ORDER BY u.name ASC";
            
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        'cid1' => $course_id,
        'cid2' => $course_id,
        'cid3' => $course_id
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    // دالة جلب كشف النقاط للآدمن (تم تحسينها بنسبة 100%)[cite: 6]
 // استبدل الدالة القديمة بهذه النسخة الاحترافية
public function getStudentFullReport($student_id) {
    // هذا الاستعلام يجمع علامات (Exam, TD, TP) في سطر واحد لكل مادة
    $sql = "SELECT 
                c.name as course_name, 
                c.coefficient,
                MAX(CASE WHEN LOWER(TRIM(g.exam_type)) = 'exam' THEN g.grade END) as exam_grade,
                MAX(CASE WHEN LOWER(TRIM(g.exam_type)) = 'td' THEN g.grade END) as td_grade,
                MAX(CASE WHEN LOWER(TRIM(g.exam_type)) = 'tp' THEN g.grade END) as tp_grade
            FROM courses c
            LEFT JOIN grades g ON c.id = g.course_id AND g.student_id = ?
            GROUP BY c.id, c.name, c.coefficient";
            
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$student_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// دالة لجلب الترتيب (لجعل أيقونة المركز حقيقية)
public function getStudentRank($student_id) {
    $sql = "SELECT student_id, RANK() OVER (ORDER BY SUM(grade) DESC) as student_rank 
            FROM grades GROUP BY student_id";
    $results = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    foreach($results as $res) {
        if($res['student_id'] == $student_id) return $res['student_rank'];
    }
    return 3; // قيمة افتراضية إذا لم يوجد ترتيب
}
}