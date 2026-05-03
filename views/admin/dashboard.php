<?php
$userModel = new User($pdo);
$stats = $userModel->getStats();
$allUsers = $userModel->getAll();
?>

<div class="space-y-8">
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-black text-slate-800 italic">نظرة عامة على النظام</h2>
        <span class="text-slate-400 text-sm font-bold"><?= date('Y-m-d') ?></span>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-[2rem] shadow-xl border-b-4 border-blue-500 transition-transform hover:scale-105">
            <p class="text-slate-400 font-bold text-xs uppercase mb-2">إجمالي المسجلين</p>
            <h3 class="text-4xl font-black text-slate-800"><?= $stats['total'] ?></h3>
        </div>
        <div class="bg-white p-6 rounded-[2rem] shadow-xl border-b-4 border-amber-500 transition-transform hover:scale-105">
            <p class="text-slate-400 font-bold text-xs uppercase mb-2">طلبات معلقة</p>
            <h3 class="text-4xl font-black text-amber-600"><?= $stats['pending'] ?></h3>
        </div>
        <div class="bg-white p-6 rounded-[2rem] shadow-xl border-b-4 border-emerald-500 transition-transform hover:scale-105">
            <p class="text-slate-400 font-bold text-xs uppercase mb-2">حسابات مفعلة</p>
            <h3 class="text-4xl font-black text-emerald-600"><?= $stats['active'] ?></h3>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-xl overflow-hidden border border-slate-100">
        <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-black text-slate-700 uppercase tracking-tighter italic">آخر النشاطات</h3>
            <a href="index.php?page=user_requests" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-lg shadow-blue-600/20">إدارة الطلبات</a>
        </div>
        <table class="w-full text-right">
            <thead class="text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-50">
                <tr>
                    <th class="p-6">المستخدم</th>
                    <th class="p-6">الرتبة</th>
                    <th class="p-6">تاريخ التسجيل</th>
                    <th class="p-6">الحالة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach(array_slice($allUsers, 0, 5) as $u): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="p-6 font-bold text-slate-700"><?= htmlspecialchars($u['name']) ?></td>
                    <td class="p-6 uppercase text-[10px] font-black text-blue-600"><?= $u['role'] ?></td>
                    <td class="p-6 text-slate-400 text-xs italic"><?= substr($u['created_at'], 0, 10) ?></td>
                    <td class="p-6">
                        <?php if($u['status'] == '1' || $u['status'] == 'active'): ?>
                            <span class="bg-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-[10px] font-black">نشط</span>
                        <?php else: ?>
                            <span class="bg-amber-100 text-amber-600 px-3 py-1 rounded-full text-[10px] font-black">معلق</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>