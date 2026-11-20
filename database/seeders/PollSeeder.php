<?php

namespace Database\Seeders;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoteAnswer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create or retrieve demo user
        $user = User::firstOrCreate(
            ['email' => 'demo@votesnap.com'],
            [
                'name' => 'سليمان الحربي',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Poll 1: Active Single Choice Poll
        $poll1 = Poll::create([
            'user_id' => $user->id,
            'slug' => 'best-web-stack-2026',
            'title' => 'ما هي لغة البرمجة وإطار العمل الأفضل لبناء تطبيقات SaaS في 2026؟',
            'question' => 'شاركنا تقنيتك المفضلة عند إطلاق مشروع برمجي متكامل وسريع.',
            'description' => 'استطلاع مفتوح لجميع المطورين لمشاركة آرائهم وخبراتهم حول التقنيات الأكثر كفاءة وإنتاجية.',
            'poll_type' => 'single',
            'max_choices' => 1,
            'start_at' => now()->subDays(2),
            'end_at' => now()->addDays(5),
            'visibility' => 'public',
            'show_results' => true,
            'results_visibility' => 'always',
            'allow_vote_change' => true,
            'randomize_options' => false,
            'votes_count' => 0,
        ]);

        $opts1 = [
            ['text' => 'Laravel 12 + Livewire + Tailwind CSS', 'votes' => 64],
            ['text' => 'Python + FastAPI + PostgreSQL', 'votes' => 38],
            ['text' => 'TypeScript + Node.js / Bun', 'votes' => 29],
            ['text' => 'Go + Templ / HTMX', 'votes' => 17],
        ];

        foreach ($opts1 as $idx => $opt) {
            $optionModel = $poll1->options()->create([
                'option_text' => $opt['text'],
                'order' => $idx,
                'votes_count' => $opt['votes'],
            ]);

            // Create sample vote records for statistical integrity
            for ($i = 0; $i < $opt['votes']; $i++) {
                $fakeVote = Vote::create([
                    'poll_id' => $poll1->id,
                    'user_id' => null,
                    'voter_identifier' => 'seed_' . $optionModel->id . '_' . $i . '_' . Str::random(10),
                    'ip_address' => '127.0.0.' . rand(2, 250),
                    'user_agent' => 'Mozilla/5.0 Demo Browser',
                ]);
                VoteAnswer::create([
                    'vote_id' => $fakeVote->id,
                    'poll_option_id' => $optionModel->id,
                ]);
            }
        }
        $poll1->update(['votes_count' => array_sum(array_column($opts1, 'votes'))]);

        // 3. Poll 2: Multiple Choice Poll
        $poll2 = Poll::create([
            'user_id' => $user->id,
            'slug' => 'remote-work-perks',
            'title' => 'ما هي أهم المزايا التي تفضلها في بيئة العمل عن بُعد؟',
            'question' => 'يمكنك اختيار حتى 3 مزايا تعتبرها الأكثر تأثيراً على إنتاجيتك وراحتك.',
            'description' => 'استبيان لمساعدة فرق العمل على تحسين بيئات العمل المرنة.',
            'poll_type' => 'multiple',
            'max_choices' => 3,
            'start_at' => now()->subDay(),
            'end_at' => now()->addDays(7),
            'visibility' => 'public',
            'show_results' => true,
            'results_visibility' => 'after_vote',
            'allow_vote_change' => false,
            'randomize_options' => false,
            'votes_count' => 0,
        ]);

        $opts2 = [
            ['text' => 'مرونة ساعات العمل اليومية وتحديد وقت الإنتاجية', 'votes' => 45],
            ['text' => 'توفير وقت وزحام المواصلات اليومية', 'votes' => 52],
            ['text' => 'التواصل غير المتزامن (Asynchronous Communication)', 'votes' => 31],
            ['text' => 'ميزانية لتجهيز مكتب منزلي مريح وأدوات تقنية', 'votes' => 28],
        ];

        foreach ($opts2 as $idx => $opt) {
            $optionModel = $poll2->options()->create([
                'option_text' => $opt['text'],
                'order' => $idx,
                'votes_count' => $opt['votes'],
            ]);

            for ($i = 0; $i < $opt['votes']; $i++) {
                $fakeVote = Vote::create([
                    'poll_id' => $poll2->id,
                    'user_id' => null,
                    'voter_identifier' => 'seed2_' . $optionModel->id . '_' . $i . '_' . Str::random(10),
                    'ip_address' => '10.0.0.' . rand(2, 250),
                    'user_agent' => 'Mozilla/5.0 Demo Browser',
                ]);
                VoteAnswer::create([
                    'vote_id' => $fakeVote->id,
                    'poll_option_id' => $optionModel->id,
                ]);
            }
        }
        $poll2->update(['votes_count' => array_sum(array_column($opts2, 'votes'))]);

        // 4. Poll 3: Scheduled Poll (Starts in 2 days)
        $poll3 = Poll::create([
            'user_id' => $user->id,
            'slug' => 'hackathon-voting-2026',
            'title' => 'التصويت لاختيار أفضل مشروع في هاكاثون الابتكار 2026',
            'question' => 'سيبدأ التصويت الرسمي لتقييم المشاريع المشاركة قريباً.',
            'description' => 'التصويت مخصص للجمهور ولجنة التحكيم وسينطلق فور انتهاء العروض التقديمية.',
            'poll_type' => 'single',
            'max_choices' => 1,
            'start_at' => now()->addDays(2),
            'end_at' => now()->addDays(4),
            'visibility' => 'public',
            'show_results' => true,
            'results_visibility' => 'after_end',
            'allow_vote_change' => false,
            'randomize_options' => true,
            'votes_count' => 0,
        ]);

        $opts3 = [
            'مشروع EcoSense للذكاء الاصطناعي البيئي',
            'تطبيق HealthSync لمتابعة المرضى عن بُعد',
            'منصة EduLeap للتعليم التفاعلي ثلاثي الأبعاد',
            'خدمة SmartChain للخدمات اللوجستية',
        ];

        foreach ($opts3 as $idx => $text) {
            $poll3->options()->create([
                'option_text' => $text,
                'order' => $idx,
                'votes_count' => 0,
            ]);
        }

        // 5. Poll 4: Manually Closed Poll with final results
        $poll4 = Poll::create([
            'user_id' => $user->id,
            'slug' => 'community-event-location',
            'title' => 'اختيار مقر ملتقى مطوري الويب القادم في الرياض',
            'question' => 'تم إغلاق التصويت واعتماد الموقع الفائز بناء على أعلى نسبة أصوات.',
            'description' => 'شكراً لكل من شارك بصوته واقتراحاته.',
            'poll_type' => 'single',
            'max_choices' => 1,
            'start_at' => now()->subDays(10),
            'end_at' => now()->subDays(2),
            'is_closed_manually' => true,
            'closed_at' => now()->subDays(2),
            'visibility' => 'public',
            'show_results' => true,
            'results_visibility' => 'always',
            'allow_vote_change' => false,
            'randomize_options' => false,
            'votes_count' => 0,
        ]);

        $opts4 = [
            ['text' => 'واجهة الرياض للمعارض والمؤتمرات', 'votes' => 114],
            ['text' => 'مركز الملك عبد الله المالي (KAFD)', 'votes' => 186],
            ['text' => 'مقر The Garage لدعم الشركات الناشئة', 'votes' => 205],
        ];

        foreach ($opts4 as $idx => $opt) {
            $optionModel = $poll4->options()->create([
                'option_text' => $opt['text'],
                'order' => $idx,
                'votes_count' => $opt['votes'],
            ]);
            for ($i = 0; $i < $opt['votes']; $i++) {
                $fakeVote = Vote::create([
                    'poll_id' => $poll4->id,
                    'user_id' => null,
                    'voter_identifier' => 'seed4_' . $optionModel->id . '_' . $i . '_' . Str::random(10),
                    'ip_address' => '172.16.0.' . rand(2, 250),
                    'user_agent' => 'Mozilla/5.0 Demo Browser',
                ]);
                VoteAnswer::create([
                    'vote_id' => $fakeVote->id,
                    'poll_option_id' => $optionModel->id,
                ]);
            }
        }
        $poll4->update(['votes_count' => array_sum(array_column($opts4, 'votes'))]);
    }
}
