<?php
// تأكد من أن ملف الكلاس وقاعدة البيانات مدمجين في الصفحة
// include_once 'models/user.php'; 

// التحقق من هوية الطالب (استخدام المعرف 34 إذا لم تكن الجلسة مفعلة)
$student_id = $_SESSION['user_id'] ?? 34;
$current_semester = 1;

// نقوم بإنشاء كائن جديد باسم مختلف لتجنب التضارب مع متغيرات النظام الأخرى
$userModel = new User($pdo); 

// 1. جلب التقرير باستخدام الكائن الجديد
$report = $userModel->getStudentFullReport($student_id, $current_semester);

$total_avg = 0;
$count = 0;

if (is_array($report) && !empty($report)) {
    foreach ($report as $row) {
        if (isset($row['average']) && $row['average'] > 0) {
            $total_avg += $row['average'];
            $count++;
        }
    }
    $average = ($count > 0) ? ($total_avg / $count) : 0;
} else {
    $average = 0;
}

// 2. جلب الترتيب
$rank = $userModel->getStudentRank($student_id);

// 3. نسبة الحضور
$attendance = 85; 
?>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-blue-950 to-slate-950 p-6 md:p-10 text-white" dir="rtl">
    
    <!-- الترويسة -->
    <div class="flex flex-col md:flex-row justify-between items-start mb-10 gap-6">
        <div>
            <h1 class="text-4xl font-black bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-emerald-400">
                الملف الشخصي للطالب
            </h1>
            <p class="text-slate-400 mt-2 italic">إدارة معلوماتك الشخصية والأكاديمية</p>
        </div>
        <div class="flex gap-4">
            <div class="bg-white/5 backdrop-blur-md p-4 rounded-3xl border border-white/10 flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-500/20 rounded-2xl flex items-center justify-center text-indigo-400 border border-indigo-500/30">
                    <i class="fas fa-fingerprint text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] text-slate-500 uppercase font-black">رقم الطالب</p>
                    <p class="text-lg font-bold">#<?php echo $_SESSION['user']['id']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- نموذج التحديث الرئيسي -->
    <form action="index.php?page=update_profile" method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- بطاقة الهوية الرقمية (تعديل الصورة) -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[3rem] p-8 text-center relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-b from-blue-600/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="relative z-10">
                        <div class="w-32 h-32 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-[2.5rem] mx-auto p-1 shadow-2xl mb-6 relative group/avatar">
                            <div class="w-full h-full bg-slate-900 rounded-[2.2rem] flex items-center justify-center text-5xl font-black text-white overflow-hidden">
                                <?php if(isset($_SESSION['user']['avatar']) && $_SESSION['user']['avatar']): ?>
                                    <img src="uploads/avatars/<?php echo $_SESSION['user']['avatar']; ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <?php echo strtoupper(substr($_SESSION['user']['name'], 0, 1)); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <h3 class="text-2xl font-black text-white"><?php echo htmlspecialchars($_SESSION['user']['name']); ?></h3>
                        <p class="text-blue-400 font-bold text-sm mt-1"><?php echo $_SESSION['user']['email']; ?></p>
                        
                       <div class="mt-8 pt-8 border-t border-white/5 grid grid-cols-2 gap-4 text-center">
    <div>
        <p class="text-2x1 font-black text-emerald-400">
            <?php 
                // حساب المعدل من مصفوفة التقرير $report التي تأتي من الدالة getStudentFullReport
                $total = 0; $count = 0;
                if(isset($report) && !empty($report)) {
                    foreach($report as $r) { if($r['average'] > 0) { $total += $r['average']; $count++; } }
                }
                echo ($count > 0) ? number_format($total / $count, 2) : '0.00'; 
            ?>
        </p>
        <p class="text-[10px] text-slate-500 uppercase">المعدل الحالي</p>
    </div>
    <div>
        <p class="text-2x1 font-black text-blue-400">
            <?php echo isset($rank) ? $rank : '--'; ?>
        </p>
        <p class="text-[10px] text-slate-500 uppercase">الترتيب</p>
    </div>
</div>
                                </div>
                                </div>

                <!-- زر رفع الصورة التفاعلي -->
                <div class="relative">
                    <input type="file" name="profile_image" id="profile_image" class="hidden" accept="image/*">
                    <label for="profile_image" class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white rounded-3xl font-black shadow-lg shadow-blue-900/40 transition-all flex items-center justify-center gap-3 cursor-pointer">
                        <i class="fas fa-camera"></i> تغيير صورة الملف
                    </label>
                </div>
            </div>

            <!-- تفاصيل الحساب والبيانات التفاعلية -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[3rem] p-10">
                    <h3 class="text-xl font-black mb-8 flex items-center gap-4 text-emerald-400">
                        <i class="fas fa-user-shield"></i>
                        البيانات الشخصية القابلة للتعديل
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- الاسم الكامل -->
                        <div class="space-y-2">
                            <label class="text-[10px] text-slate-500 font-black uppercase mr-4 text-right block">الاسم بالكامل</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($_SESSION['user']['name']); ?>" 
                                   class="w-full p-4 bg-white/5 rounded-2xl border border-white/10 font-bold text-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div class="space-y-2">
                            <label class="text-[10px] text-slate-500 font-black uppercase mr-4 text-right block">البريد الإلكتروني</label>
                            <input type="email" name="email" value="<?php echo $_SESSION['user']['email']; ?>" 
                                   class="w-full p-4 bg-white/5 rounded-2xl border border-white/10 font-bold text-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>

                        <!-- التخصص (عرض فقط) -->
                        <div class="space-y-2">
                            <label class="text-[10px] text-slate-500 font-black uppercase mr-4 text-right block">التخصص الأكاديمي</label>
                            <div class="p-4 bg-white/10 rounded-2xl border border-white/5 font-bold text-slate-400 cursor-not-allowed">
                                Computer Science (POO & SQL)
                            </div>
                        </div>

                        <!-- كلمة المرور -->
                        <div class="space-y-2">
                            <label class="text-[10px] text-slate-500 font-black uppercase mr-4 text-right block">تغيير كلمة المرور</label>
                            <input type="password" name="password" placeholder="اتركها فارغة لعدم التغيير" 
                                   class="w-full p-4 bg-white/5 rounded-2xl border border-white/10 font-bold text-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                    </div>

                    <!-- زر الحفظ -->
                    <div class="mt-10 pt-8 border-t border-white/5">
                        <button type="submit" class="w-full md:w-auto px-12 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black transition-all shadow-xl shadow-emerald-900/20 flex items-center justify-center gap-3">
                            <i class="fas fa-save"></i> حفظ جميع التغييرات
                        </button>
                    </div>
                </div>

                <!-- إحصائيات سريعة -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-gradient-to-br from-emerald-600/20 to-transparent border border-emerald-500/20 rounded-[2.5rem] p-8 flex items-center gap-6">
        <div class="w-16 h-16 bg-emerald-500/20 rounded-3xl flex items-center justify-center text-emerald-400">
            <i class="fas fa-check-double text-2xl"></i>
        </div>
        <div>
            <h4 class="text-2xl font-black text-white">
                <?php 
                    // استخراج المعدل للحالة
                    $temp_avg = 0; $c = 0;
                    if(isset($report)) {
                        foreach($report as $r) { if($r['average'] > 0) { $temp_avg += $r['average']; $c++; } }
                    }
                    $final_avg = ($c > 0) ? ($temp_avg / $c) : 0;

                    if($final_avg == 0) echo "قيد المعالجة";
                    else echo ($final_avg >= 10) ? "ناجح" : "راسب";
                ?>
            </h4>
            <p class="text-xs text-emerald-500/70 font-bold">الحالة الأكاديمية للسداسي الحالي</p>
        </div>
    </div>

    <div class="bg-gradient-to-br from-blue-600/20 to-transparent border border-blue-500/20 rounded-[2.5rem] p-8 flex items-center gap-6">
        <div class="w-16 h-16 bg-blue-500/20 rounded-3xl flex items-center justify-center text-blue-400">
            <i class="fas fa-hourglass-half text-2xl"></i>
        </div>
        <div>
            <h4 class="text-2xl font-black text-white">
                <?php echo isset($attendance) ? $attendance . '%' : '0%'; ?>
            </h4>
            <p class="text-xs text-blue-500/70 font-bold">نسبة حضور المحاضرات</p>
        </div>
    </div>
</div>
</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
