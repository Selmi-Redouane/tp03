<?php
class AuthController {
    private $userModel;

    public function __construct($pdo) { 
        $this->userModel = new User($pdo); 
    }

    public function login($email, $password) {
    $user = $this->userModel->findByEmail($email);

    if ($user) {
        // فحص مرن: يقبل التشفير الجديد، أو الكلمة العادية (للطوارئ)
        $isPasswordCorrect = password_verify($password, $user['password']) || ($password === $user['password']);
        
        if ($isPasswordCorrect) {
            if ($user['status'] == '1' || $user['status'] == 'active') {
                // إعادة تشغيل الجلسة لضمان نظافتها
                session_regenerate_id(true);
                $_SESSION['user'] = $user;
                header("Location: index.php?page=dashboard");
                exit();
            } else {
                echo "<script>alert('حسابك معلق، بانتظار تفعيل الإدارة'); window.location.href='index.php?page=login';</script>";
                exit();
            }
        }
    }
    echo "<script>alert('البريد أو كلمة المرور غير صحيحة'); window.location.href='index.php?page=login';</script>";
    exit();
}

    public function logout() {
        session_destroy();
        header("Location: index.php?page=login");
        exit();
    }
}