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
  <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+" />
</p>

---

## 🌟 نظرة عامة عن المشروع

**VoteSnap** هو منتج ويب متكامل (SaaS) مصمم لتقديم أسرع وأبسط تجربة لإنشاء استطلاعات الرأي والتصويتات العامة والخاصة:
- يستطيع المستخدم إنشاء تصويت متكامل خلال ثوانٍ معدودة.
- مشاركة رابط الاستطلاع بنقرة واحدة عبر واتساب وتلغرام والبريد الإلكتروني، أو عبر رمز استجابة سريعة **QR Code** فوري قابل للتحميل كصورة.
- استقبال الأصوات مباشرة من الزوار دون الحاجة لتسجيل حساب.
- نتائج حية وفورية تتحدث تلقائياً كل 5 ثوانٍ باستخدام **Livewire Polling** مع أشرطة تقدم تفاعلية ومخططات بيانية (**Chart.js**).
- حماية قوية ضد التلاعب وتكرار الأصوات باستخدام بصمة الجلسة والـ IP ومعاملات قاعدة البيانات (**Database Transactions**).
- دعم كامل للمظهرين **الفاتح (Light Mode)** و **الداكن (Dark Mode)** مع ثنائية اللغة **العربية (RTL)** و **الإنجليزية (LTR)**.
- ضبط التوقيت الافتراضي بدقة بتوقيت مكة المكرمة واليمن (`Asia/Riyadh` / UTC+3).

---

## 🔑 الحساب التجريبي الجاهز (Demo Account)

المشروع مزود بـ **Database Seeder** متكامل ومحفوظ داخل المستودع (`database/seeders/PollSeeder.php`). عند قيام أي شخص بتحميل المشروع من GitHub وتنفيذ أمر الترحيل والبذر، سيحصل مباشرة على الحساب التجريبي التالي مع استطلاعات حقيقية ببيانات وأصوات متكاملة:

- **البريد الإلكتروني:** `demo@votesnap.com`
- **كلمة المرور:** `password`

### البيانات التجريبية المتوفرة تلقائياً:
1. **تصويت نشط (Single Choice):** ما هي لغة البرمجة وإطار العمل الأفضل لبناء تطبيقات SaaS في 2026؟ (مع أصوات حقيقية وإمكانية تجربة التصويت وتغيير الصوت وعرض المخطط البياني).
2. **تصويت متعدد الخيارات (Multiple Choice):** ما هي أهم المزايا التي تفضلها في بيئة العمل عن بُعد؟
3. **تصويت مجدول (Scheduled Poll):** يبدأ تلقائياً بعد يومين مع عد تنازلي حي.
4. **تصويت مغلق (Closed Poll):** استطلاع منتهي يوضح النتائج النهائية والمخطط البياني.

---

## 🗄️ قاعدة البيانات والربط مع Supabase PostgreSQL

تم بناء المشروع باستخدام بنية Eloquent ومهاجرات Laravel القياسية المتوافقة مع:
- **محلياً (Development):** يدعم محرك MariaDB / MySQL أو PostgreSQL محلي.
- **الإنتاج / السحاب (Production):** يدعم الاتصال المباشر بقاعدة بيانات **Supabase PostgreSQL**.

### جداول قاعدة البيانات الأساسية:
- `users`: المستخدمون المسجلون وإدارة الحسابات.
- `polls`: تفاصيل الاستطلاعات (العنوان، السؤال، الوصف، النوع، المواعيد، الخصوصية، وقواعد التصويت).
- `poll_options`: خيارات الإجابة لكل تصويت مع عدادات الأصوات المباشرة.
- `votes`: سجلات التصويت الفردية مع تشفير البصمة الفريدة لمنع التكرار ومعرف المستخدم/الجلسة.
- `vote_answers`: ربط الصوت بالخيارات المحددة في حالات الاختيار الفردي أو المتعدد.

---

### 🚀 خطوات ربط المشروع مع Supabase PostgreSQL

1. سجّل دخولك إلى حسابك في [Supabase](https://supabase.com) وأنشئ مشروعاً جديداً.
2. اذهب إلى **Project Settings** ثم **Database**.
3. في قسم **Connection Parameters** أو **Connection Pooling**، انسخ بيانات الاتصال:
   - **Host:** مثل `aws-0-[region].pooler.supabase.com`
   - **Port:** `5432` (أو `6543` للـ Session/Transaction Pooler)
   - **Database:** `postgres`
   - **User:** `postgres.[your-project-ref]`
   - **Password:** كلمة المرور التي اخترتها عند إنشاء المشروع في Supabase.
4. افتح ملف `.env` في المشروع وقم بتعديل متغيرات قاعدة البيانات كما يلي:

```env
DB_CONNECTION=pgsql
DB_HOST=aws-0-eu-central-1.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.abcdefghijklmnop
DB_PASSWORD=your_secure_supabase_password
DB_SSLMODE=require
```

5. نفذ أمر بناء الجداول وبذر البيانات التجريبية:
```bash
php artisan migrate:fresh --seed
```
وستنتقل كامل الجداول والبيانات التجريبية إلى Supabase فوراً وبشكل قياسي!

---

## 🛠️ دليل التثبيت والتشغيل المحلي (Quickstart)

### المتطلبات الأساسية:
- PHP 8.2 أو أحدث (مع ملحقات `pdo`, `mbstring`, `openssl`, `curl`).
- Composer.
- Node.js (v18+) & NPM.

### خطوات التشغيل:

```bash
# 1. استنساخ المستودع
git clone https://github.com/your-username/VoteSnap.git
cd VoteSnap

# 2. تثبيت تبعيات PHP
composer install

# 3. تثبيت تبعيات JavaScript وبناء الأصول
npm install
npm run build

# 4. إعداد ملف البيئة وتوليد مفتاح التطبيق
cp .env.example .env
php artisan key:generate

# 5. تشغيل المهاجرات وبذر البيانات التجريبية
php artisan migrate --seed

# 6. تشغيل السيرفر المحلي
php artisan serve
```

افتح المتصفح على: `http://localhost:8000`

---

## 🧪 الاختبارات التلقائية (Automated Testing Suite)

يحتوي المشروع على حزمة اختبارات شاملة مبنية بواسطة **PHPUnit**:
- اختبار إنشاء التصويت عبر مكونات Livewire.
- اختبار التصويت الفردي والمتعدد وضوابط الحدود القصوى.
- اختبار منع التصويت المكرر وفق السياسة المعتمدة.
- اختبار إمكانية تعديل الصوت عند تفعيلها ومنعها عند تعطيلها.
- اختبار رفض التصويت للاستطلاعات المنتهية أو المجدولة.
- اختبار حماية الصلاحيات عبر `PollPolicy` (التعديل، الحذف، الإغلاق متاح للمالك فقط).
- اختبارات المصادقة وإدارة الحسابات.

لتشغيل الاختبارات:
```bash
php artisan test
```

> **النتيجة:** 40 اختباراً ناجحاً بنسبة 100% (40 passed, 100 assertions).

---

## 🎯 المميزات الرئيسية بالتفصيل

| الميزة | الوصف |
| :--- | :--- |
| **الصفحة الرئيسية (Landing Page)** | صفحة تعريفية بتصميم SaaS حديث، تتضمن معاينة تفاعلية حية، شرح للخطوات الثلاث، واستعراض للمزايا. |
| **إنشاء التصويت (Create Poll)** | واجهة سهلة وسريعة تركز على السؤال والخيارات مع خيارات جدولة ومواعيد دقيقة وتحكم في القواعد دون تعقيد. |
| **التصويت المباشر (Poll Page)** | رابط فريد لكل تصويت `/poll/{slug}`، متوافق مع الهواتف والحواسيب، بعد تنازلي حي حسب توقيت مكة/اليمن. |
| **النتائج الحية (Live Results)** | تتحدث كل 5 ثوانٍ تلقائياً عبر Livewire مع أشرطة تقدم ملونة وإمكانية استعراض رسم بياني تفاعلي (أعمدة / دائري). |
| **المشاركة السريعة (Share)** | نسخ الرابط بنقرة واحدة، أزرار مشاركة مباشرة لتطبيقات التواصل، وتوليد فوري لرمز QR Code مع إمكانية حفظه كصورة PNG. |
| **لوحة التحكم (Dashboard)** | 4 بطاقات إحصائية، تصفية حسب حالة التصويت (نشط، مجدول، مغلق)، بحث سريع، وتكرار الاستطلاع كمسودة بنقرة واحدة. |
| **حماية الأصوات** | قفل تلقائي لتعديل الخيارات إذا كان التصويت قد استقبل أصواتاً سابقة للحفاظ على مصداقية النتائج. |
| **Dark & Light Mode** | دعم التبديل السلس بين الوضعين مع الحفظ التلقائي في LocalStorage والتوافق التام مع تفضيلات النظام. |
| **دعم RTL & LTR** | دعم حقيقي للغة العربية والإنجليزية، مع خطوط `Cairo` و `Plus Jakarta Sans`. |

---

## 📄 الترخيص (License)

هذا المشروع مفتوح المصدر ومتاح تحت ترخيص [MIT License](LICENSE).
