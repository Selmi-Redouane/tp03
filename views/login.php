<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    /* استيراد خطوط احترافية */
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap');

    body {
        font-family: 'Cairo', sans-serif;
        background: #0f172a; /* لون ليلي عميق */
        overflow: hidden;
    }

    /* خلفية متحركة ببطء شديد لإعطاء طابع الفخامة */
    .bg-animate {
        position: fixed;
        top: -50%; left: -50%; width: 200%; height: 200%;
        background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
        animation: rotateBg 20s linear infinite;
        z-index: -1;
    }

    @keyframes rotateBg {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* تأثير الزجاج (Glassmorphism) */
    .glass-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    /* حركات الظهور */
    .fade-up {
        animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* تحسين شكل الحقول */
    .input-field {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .input-field:focus {
        background: rgba(255, 255, 255, 0.08);
        border-color: #3b82f6;
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);
    }
</style>

<div class="bg-animate"></div>

<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-lg fade-up">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 mb-4 shadow-2xl shadow-blue-500/20">
                <i class="fas fa-shield-halved text-2xl text-white"></i>
            </div>
            <h1 class="text-3xl font-black text-white tracking-tight">نظام إدارة المعدلات</h1>
            <p class="text-slate-400 mt-2 font-light">مرحباً بك مجدداً، يرجى تسجيل الدخول للمتابعة</p>
        </div>

        <div class="glass-card rounded-[2.5rem] p-10 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-blue-500 to-transparent opacity-50"></div>

            <form action="index.php?page=doLogin" method="POST" class="space-y-6">
                
                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 block ml-2">البريد الإلكتروني</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-4 flex items-center text-slate-500">
                            <i class="far fa-envelope"></i>
                        </span>
                        <input type="email" name="email" required placeholder="example@univ.dz"
                            class="input-field w-full pl-12 pr-6 py-4 rounded-2xl text-white outline-none placeholder:text-slate-600">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 block ml-2">كلمة المرور</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-4 flex items-center text-slate-500">
                            <i class="fas fa-fingerprint"></i>
                        </span>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="input-field w-full pl-12 pr-6 py-4 rounded-2xl text-white outline-none placeholder:text-slate-600">
                    </div>
                </div>

                <div class="flex items-center justify-between px-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" class="hidden peer">
                        <div class="w-5 h-5 rounded-md border border-slate-700 peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all flex items-center justify-center text-[10px] text-white">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="text-xs text-slate-400 group-hover:text-slate-300 transition-colors">تذكرني</span>
                    </label>
                    <a href="#" class="text-xs text-blue-400 hover:text-blue-300 transition-colors">نسيت كلمة المرور؟</a>
                </div>

                <button type="submit" 
                    class="w-full py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl font-bold shadow-lg shadow-blue-600/20 transition-all active:scale-[0.98] flex items-center justify-center gap-3 group">
                    <span>دخول آمن</span>
                    <i class="fas fa-arrow-right-to-bracket text-sm group-hover:translate-x-1 transition-transform"></i>
                </button>
            </form>

            <div class="mt-10 pt-8 border-t border-white/5 text-center">
                <p class="text-slate-500 text-sm">ليس لديك حساب؟ 
                    <a href="index.php?page=add_user" class="text-white font-bold hover:text-blue-400 transition-colors">طلب انضمام للنظام</a>
                </p>
            </div>
        </div>

        <p class="text-center mt-8 text-slate-600 text-[10px] font-bold uppercase tracking-[0.2em]">
            &copy; 2026 GPA Management System • All Rights Reserved
        </p>
    </div>
</div>