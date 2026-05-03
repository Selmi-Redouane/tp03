<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPA Smart System | نظام المعدلات الذكي</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; scroll-behavior: smooth; }
        .glass-sidebar { background: rgba(15, 23, 42, 0.98); backdrop-filter: blur(12px); border-left: 1px solid rgba(255, 255, 255, 0.05); }
        .nav-link { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); color: #94a3b8; }
        .nav-link:hover { color: #f8fafc; background: rgba(255, 255, 255, 0.05); }
        .nav-link.active { background: linear-gradient(to left, rgba(59, 130, 246, 0.2), transparent); border-right: 4px solid #3b82f6; color: #60a5fa; font-weight: 900; }
    </style>
</head>
<body class="bg-slate-950 text-slate-200">

<div class="flex min-h-screen">
    <?php if(isset($_SESSION['user'])): ?>
    <!-- القائمة الجانبية -->
    <aside class="glass-sidebar w-72 text-white fixed h-full z-50 shadow-2xl">
        <div class="p-8 text-center border-b border-white/5">
            <div class="w-16 h-16 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-2xl mx-auto flex items-center justify-center shadow-lg mb-4 shadow-blue-500/20">
                <i class="fa fa-graduation-cap text-3xl text-white"></i>
            </div>
            <h2 class="text-xl font-black tracking-widest text-blue-400 italic">GPA SMART</h2>
        </div>

        <nav class="mt-6 px-4 space-y-2">
            <?php if($_SESSION['user']['role'] == "admin"): ?>
                <p class="text-[10px] font-bold text-slate-500 px-4 mb-2 uppercase tracking-widest italic">إدارة النظام</p>
                <a href="index.php?page=dashboard" class="nav-link flex items-center p-4 rounded-2xl gap-3 <?php echo ($page == 'dashboard') ? 'active' : ''; ?>">
                    <i class="fa fa-chart-pie w-5"></i> الإحصائيات العامة
                </a>
                <a href="index.php?page=manage_academy" class="nav-link flex items-center p-4 rounded-2xl gap-3 <?php echo ($page == 'manage_academy') ? 'active' : ''; ?>">
                    <i class="fa fa-university w-5"></i> إدارة الأكاديمية
                </a>
            <?php endif; ?>

            <?php if($_SESSION['user']['role'] == "student"): ?>
                <p class="text-[10px] font-bold text-slate-500 px-4 mb-2 uppercase tracking-widest italic">بوابة الطالب</p>
                <!-- لوحة النتائج الذكية -->
                <a href="index.php?page=student_report" class="nav-link flex items-center p-4 rounded-2xl gap-3 <?php echo ($page == 'student_report') ? 'active' : ''; ?>">
                    <i class="fa fa-columns w-5 text-blue-400"></i> لوحة النتائج الذكية
                </a>
                <!-- الملف الشخصي -->
                <a href="index.php?page=dashboard" class="nav-link flex items-center p-4 rounded-2xl gap-3 <?php echo ($page == 'dashboard') ? 'active' : ''; ?>">
                    <i class="fa fa-user-astronaut w-5 text-emerald-400"></i> ملفي الشخصي
                </a>
                <!-- تم إصلاح التكرار وربط الزر بصفحة التقارير لتقديم طعن -->
                <a href="index.php?page=student_report" class="nav-link flex items-center p-4 rounded-2xl gap-3">
                    <i class="fa fa-envelope-open-text w-5 text-amber-400"></i> الطعون والطلبات
                </a>
            <?php endif; ?>
        </nav>

        <div class="absolute bottom-0 w-full p-6 border-t border-white/5">
            <a href="index.php?page=logout" class="flex items-center justify-center gap-2 p-4 rounded-2xl bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white transition-all font-black italic">
                <i class="fa fa-power-off"></i> تسجيل الخروج
            </a>
        </div>
    </aside>
    <?php endif; ?>

    <!-- المحتوى الرئيسي -->
    <main class="flex-1 <?php echo isset($_SESSION['user']) ? 'mr-72' : ''; ?> transition-all">
        <?php if(isset($_SESSION['user'])): ?>
        <header class="sticky top-0 z-40 bg-slate-950/50 backdrop-blur-xl border-b border-white/5 px-8 py-4 flex justify-between items-center">
            <div class="text-slate-500 flex gap-6">
                <i class="fa fa-search hover:text-blue-400 cursor-pointer transition-colors"></i>
                <div class="relative">
                    <i class="fa fa-bell hover:text-blue-400 cursor-pointer transition-colors"></i>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-rose-500 rounded-full"></span>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest"><?php echo $_SESSION['user']['role']; ?></p>
                    <p class="text-sm font-bold text-white"><?php echo htmlspecialchars($_SESSION['user']['name']); ?></p>
                </div>
                <div class="w-10 h-10 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white font-black shadow-lg">
                    <?php echo strtoupper(substr($_SESSION['user']['name'], 0, 1)); ?>
                </div>
            </div>
        </header>
        <?php endif; ?>

        <div class="p-0"> 
            <?php 
                if(isset($content) && file_exists($content)) { include $content; } 
                else { include "views/login.php"; }
            ?>
        </div>
    </main>
</div>
</body>
</html>