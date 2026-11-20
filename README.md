# 🗳️ VoteSnap — منصة التصويت والاستطلاعات الفورية

<p align="center">
  <img src="https://raw.githubusercontent.com/lucide-icons/lucide/main/icons/vote.svg" width="80" height="80" alt="VoteSnap Logo" />
</p>

<p align="center">
  <strong>Create. Share. Vote. — أنشئ. شارك. صوّت.</strong><br>
  المنصة العصرية والأسرع لإنشاء الاستطلاعات والتصويتات الإلكترونية ومشاركتها عبر روابط مباشرة ورموز QR ومتابعة النتائج الحية لحظة بلحظة.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12" />
  <img src="https://img.shields.io/badge/Livewire-v4-FB70A9?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire 4" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
  <img src="https://img.shields.io/badge/PostgreSQL-Supabase-3ECF8E?style=for-the-badge&logo=supabase&logoColor=white" alt="Supabase PostgreSQL" />
  <img src="https://img.shields.io/badge/Chart.js-Interactive-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white" alt="Chart.js" />
  <img src="https://img.shields.io/badge/License-MIT-blue?style=for-the-badge" alt="MIT License" />
</p>

---

## 🌟 نظرة عامة عن المشروع

**VoteSnap** هو تطبيق ويب متكامل بنظام البرمجيات كخدمة (SaaS)، يهدف لتسهيل عملية جمع الآراء وإجراء التصويتات الإلكترونية بسرعة وأمان:
- إنشاء تصويت متكامل خلال ثوانٍ معدودة دون خطوات معقدة.
- إمكانية مشاركة رابط التصويت المباشر عبر واتساب وتلغرام والبريد الإلكتروني، أو عبر رمز استجابة سريعة **QR Code** فوري عالي الدقة بصيغة PNG قابل للتحميل بنقرة واحدة.
- مشاركة وتصويت مباشر للزوار دون اشتراط تسجيل حساب.
- نتائج حية وفورية تتحدث تلقائياً كل 5 ثوانٍ باستخدام **Livewire Polling** مع أشرطة تقدم تفاعلية ومخططات بيانية (**Chart.js**) بنمط الأعمدة والنمط الدائري.
- نظام حماية ذكي لمنع التلاعب وتكرار الأصوات بالاعتماد على بصمة الجلسة والـ IP وعمليات قاعدة البيانات المترابطة (**Transactions**).
- دعم كامل للوضعين **الفاتح (Light Mode)** و **الداكن (Dark Mode)** مع التبديل السلس والحفظ التلقائي في المتصفح.
- دعم ثنائي حقيقي ومتكامل للغتين **العربية (RTL)** و **الإنجليزية (LTR)**.

---

## 🗄️ قاعدة البيانات والربط مع Supabase PostgreSQL

المشروع مبني ليعمل مباشرة مع قاعدة بيانات **Supabase PostgreSQL**. لديك طريقتان سهلتان لتهيئة قاعدة البيانات في حسابك على Supabase:

### 🔹 الطريقة الأولى: التشغيل التلقائي عبر Laravel Migrations (موصى بها)

1. افتح حسابك في [Supabase](https://supabase.com) وأنشئ مشروعاً جديداً.
2. توجه إلى **Project Settings** ثم اضغط على **Database**.
3. انسخ بيانات الاتصال بقاعدة البيانات وضعها داخل ملف `.env` في مشروعك:

```env
DB_CONNECTION=pgsql
DB_HOST=aws-0-[your-region].pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.[your-project-ref]
DB_PASSWORD=[your-supabase-password]
DB_SSLMODE=require
```

4. شغّل أمر ترحيل الجداول في الطرفية:
```bash
php artisan migrate
```

---

### 🔹 الطريقة الثانية: الاستيراد المباشر عبر ملف SQL (`database/supabase_schema.sql`)

إذا كنت تفضل إنشاء الجداول مباشرة داخل لوحة تحكم Supabase دون الحاجة للربط المحلي أولاً:
1. ادخل إلى مشروعك في [Supabase](https://supabase.com).
2. من القائمة الجانبية، افتح **SQL Editor**.
3. اضغط على **New query**.
4. افتح الملف المرفق في المشروع: [`database/supabase_schema.sql`](database/supabase_schema.sql).
5. انسخ كامل محتوى الملف، والصقه في محرر استعلامات Supabase، ثم اضغط على زر **Run**.
6. سيتم إنشاء كامل الجداول، الفهارس، والقيود بنجاح في ثوانٍ معدودة.

---

## 🛠️ دليل التثبيت والتشغيل المحلي (Setup Guide)

### المتطلبات:
- PHP 8.2 أو أحدث (مع إضافات `pdo`, `mbstring`, `openssl`, `curl`).
- Composer.
- Node.js (v18+) & NPM.

### خطوات التشغيل:

```bash
# 1. استنساخ المستودع
git clone https://github.com/your-username/VoteSnap.git
cd VoteSnap

# 2. تثبيت حزم PHP
composer install

# 3. تثبيت حزم الواجهة وبناء الأصول
npm install
npm run build

# 4. إعداد ملف البيئة
cp .env.example .env
php artisan key:generate

# 5. تشغيل المهاجرات لبناء الجداول
php artisan migrate

# 6. تشغيل خادم التطوير
php artisan serve
```

افتح المتصفح على الرابط: `http://localhost:8000`

---

## 🧪 الاختبارات التلقائية (Automated Testing Suite)

يحتوي المشروع على حزمة اختبارات شاملة مبنية بواسطة **PHPUnit**:
- اختبار إنشاء التصويت عبر Livewire والتأكد من صحة الحقول.
- اختبار التصويت الفردي والمتعدد وضوابط الحدود القصوى.
- اختبار منع التصويت المكرر وفق السياسة المعتمدة.
- اختبار إمكانية تعديل الصوت عند تفعيلها ومنعها عند تعطيلها.
- اختبار رفض التصويت للاستطلاعات المنتهية أو المجدولة.
- اختبار الصلاحيات عبر `PollPolicy` (التعديل، الحذف، الإغلاق للمالك فقط).
- اختبارات المصادقة وإدارة الحسابات.

لتشغيل الاختبارات:
```bash
php artisan test
```

---

## 🎯 الميزات الرئيسية بالتفصيل

| الميزة | الوصف |
| :--- | :--- |
| **الصفحة الرئيسية (Landing Page)** | تصميم SaaS عصري بلمسات أنيقة، مع معاينة استطلاع تفاعلي مباشر، وشرح الخطوات والمزايا. |
| **إنشاء التصويت (Create Poll)** | واجهة سهلة وسريعة تركز على السؤال والخيارات، مع إمكانية تحديد نوع الاختيار ومواعيد البداية والانتهاء بدقة. |
| **التصويت المباشر (Poll Page)** | رابط فريد لكل تصويت `/poll/{slug}`، متوافق مع كافة مقاسات الشاشات، مع عد تنازلي حي حسب توقيت مكة/اليمن. |
| **النتائج الحية (Live Results)** | تتحدث كل 5 ثوانٍ تلقائياً عبر Livewire دون إعادة تحميل الصفحة، مع رسم بياني تفاعلي (أعمدة / دائري). |
| **المشاركة السريعة (Share)** | نسخ الرابط بنقرة واحدة، أزرار مشاركة لتطبيقات التواصل، وتوليد فوري لرمز QR Code مع إمكانية حفظه كصورة PNG. |
| **لوحة التحكم (Dashboard)** | بطاقات إحصائية، تصفية حسب حالة التصويت (نشط، مجدول، مغلق)، بحث سريع، وتكرار الاستطلاع كمسودة بنقرة واحدة. |
| **حماية الأصوات** | قفل تلقائي لتعديل الخيارات إذا كان التصويت قد استقبل أصواتاً سابقة للحفاظ على مصداقية النتائج. |
| **Dark & Light Mode** | دعم التبديل السلس بين الوضعين مع الحفظ التلقائي في LocalStorage والتوافق التام مع فئة CSS. |
| **دعم RTL & LTR** | دعم كامل ومطابق للغتين العربية والإنجليزية عبر ملفات الترجمة القياسية. |

---

## 📄 الترخيص (License)

هذا المشروع مرخص تحت رخصة **MIT License** — حقوق النشر &copy; 2026 **Yahya-Studio**. راجع ملف [`LICENSE`](LICENSE) للمزيد من التفاصيل.
