<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-black text-slate-800">طلبات الانضمام المعلقة</h2>
        <span class="bg-amber-100 text-amber-700 px-4 py-1 rounded-full text-xs font-bold">
            <?= count($pendingUsers) ?> طلبات بانتظار قرارك
        </span>
    </div>

    <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl border border-slate-100">
        <table class="w-full text-right">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="p-6 text-slate-500 font-bold">الاسم</th>
                    <th class="p-6 text-slate-500 font-bold">البريد الإلكتروني</th>
                    <th class="p-6 text-slate-500 font-bold">الرتبة</th>
                    <th class="p-6 text-slate-500 font-bold text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($pendingUsers as $user): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="p-6 font-bold text-slate-700"><?= htmlspecialchars($user['name']) ?></td>
                    <td class="p-6 text-slate-500"><?= htmlspecialchars($user['email']) ?></td>
                    <td class="p-6">
                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase bg-blue-100 text-blue-600">
                            <?= $user['role'] ?>
                        </span>
                    </td>
                    <td class="p-6">
                        <div class="flex justify-center gap-2">
                            <a href="index.php?page=approve_user&id=<?= $user['id'] ?>" 
                               class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-xl text-sm transition-all shadow-lg shadow-emerald-500/20">
                                <i class="fas fa-check ml-1"></i> تفعيل
                            </a>
                            <a href="index.php?page=delete_user&id=<?= $user['id'] ?>" 
                               onclick="return confirm('هل أنت متأكد من رفض هذا الطلب؟')"
                               class="bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-xl text-sm transition-all shadow-lg shadow-rose-500/20">
                                <i class="fas fa-times ml-1"></i> رفض
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($pendingUsers)): ?>
                <tr>
                    <td colspan="4" class="p-20 text-center text-slate-400">
                        <i class="fas fa-inbox text-4xl mb-4 block opacity-20"></i>
                        لا توجد طلبات انضمام حالياً
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>