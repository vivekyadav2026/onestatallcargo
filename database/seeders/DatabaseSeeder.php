<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\MockTest;
use App\Models\Question;
use App\Models\TestAttempt;
use App\Models\Certificate;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@testwise.in'],
            [
                'name' => 'Testwise Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_pro' => true,
                'phone' => '9876543210',
                'goal' => 'Platform Management & Analytics',
            ]
        );

        $studentRahul = User::firstOrCreate(
            ['email' => 'rahul.sharma@testwise.edu'],
            [
                'name' => 'Rahul Sharma',
                'password' => Hash::make('password'),
                'role' => 'student',
                'is_pro' => true, // Pro user
                'phone' => '9812345678',
                'goal' => 'MP Police GD 2026 में अंतिम चयन',
                'daily_study_goal_minutes' => 120,
            ]
        );

        $studentQa = User::firstOrCreate(
            ['email' => 'qa_student_1@testwise.in'],
            [
                'name' => 'QA Test Student',
                'password' => Hash::make('password'),
                'role' => 'student',
                'is_pro' => false, // Free user
                'phone' => '9811122233',
                'goal' => 'MP Police GD 2026',
            ]
        );

        $studentRajesh = User::firstOrCreate(
            ['email' => 'rajesh.verma@testwise.in'],
            [
                'name' => 'Rajesh Verma',
                'password' => Hash::make('password'),
                'role' => 'student',
                'is_pro' => true,
                'phone' => '9899988877',
                'goal' => 'MP Police GD 2026 Top Rank',
            ]
        );

        // 2. Create Subjects
        $gk = Subject::create([
            'name_hi' => 'सामान्य ज्ञान एवं समसामयिक विषय',
            'name_en' => 'General Knowledge & Current Affairs',
            'code' => 'gk',
            'total_marks' => 40,
            'total_chapters' => 10,
            'icon' => 'globe',
            'order' => 1,
        ]);

        $reasoning = Subject::create([
            'name_hi' => 'तार्किक क्षमता एवं मानसिक अभिरुचि',
            'name_en' => 'Reasoning & Mental Ability',
            'code' => 'reasoning',
            'total_marks' => 30,
            'total_chapters' => 10,
            'icon' => 'brain',
            'order' => 2,
        ]);

        $mathScience = Subject::create([
            'name_hi' => 'विज्ञान एवं सरल अंकगणित',
            'name_en' => 'Science & Elementary Mathematics',
            'code' => 'math_science',
            'total_marks' => 30,
            'total_chapters' => 12,
            'icon' => 'calculator',
            'order' => 3,
        ]);

        // 3. Create 32 Chapters across subjects
        $chaptersData = [
            // GK (10 Chapters)
            ['subject' => $gk, 'num' => 1, 'hi' => 'मध्य प्रदेश का सामान्य परिचय', 'en' => 'General Introduction of Madhya Pradesh', 'free' => true],
            ['subject' => $gk, 'num' => 2, 'hi' => 'मध्य प्रदेश का इतिहास व प्रमुख राजवंश', 'en' => 'History & Major Dynasties of MP', 'free' => true],
            ['subject' => $gk, 'num' => 3, 'hi' => 'मध्य प्रदेश का भूगोल एवं नदियाँ', 'en' => 'Geography & Rivers of MP', 'free' => true],
            ['subject' => $gk, 'num' => 4, 'hi' => 'मध्य प्रदेश की जलवायु एवं मृदा', 'en' => 'Climate & Soils of MP', 'free' => false],
            ['subject' => $gk, 'num' => 5, 'hi' => 'मध्य प्रदेश के राष्ट्रीय उद्यान एवं अभयारण्य', 'en' => 'National Parks & Wildlife Sanctuaries', 'free' => false],
            ['subject' => $gk, 'num' => 6, 'hi' => 'मध्य प्रदेश की जनसांख्यिकी एवं लोक कला संस्कृति', 'en' => 'Demographics & Folk Art Culture', 'free' => false],
            ['subject' => $gk, 'num' => 7, 'hi' => 'मध्य प्रदेश का प्रशासनिक ढांचा एवं पंचायती राज', 'en' => 'Administrative Structure & Panchayati Raj', 'free' => false],
            ['subject' => $gk, 'num' => 8, 'hi' => 'मध्य प्रदेश के प्रमुख व्यक्तित्व एवं पुरस्कार', 'en' => 'Famous Personalities & Awards', 'free' => false],
            ['subject' => $gk, 'num' => 9, 'hi' => 'भारतीय संविधान एवं शासन व्यवस्था', 'en' => 'Indian Constitution & Governance', 'free' => false],
            ['subject' => $gk, 'num' => 10, 'hi' => 'समसामयिक घटनाक्रम (Current Affairs 2025-26)', 'en' => 'Current Affairs 2025-26', 'free' => false],

            // Reasoning (10 Chapters)
            ['subject' => $reasoning, 'num' => 11, 'hi' => 'कोडिंग - डिकोडिंग (Coding - Decoding)', 'en' => 'Coding - Decoding', 'free' => false],
            ['subject' => $reasoning, 'num' => 12, 'hi' => 'रक्त संबंध (Blood Relations)', 'en' => 'Blood Relations', 'free' => false],
            ['subject' => $reasoning, 'num' => 13, 'hi' => 'दिशा ज्ञान परीक्षण (Direction Sense)', 'en' => 'Direction Sense Test', 'free' => false],
            ['subject' => $reasoning, 'num' => 14, 'hi' => 'क्रम एवं रैंकिंग (Ordering & Ranking)', 'en' => 'Ordering & Ranking', 'free' => false],
            ['subject' => $reasoning, 'num' => 15, 'hi' => 'श्रृंखला परीक्षण (Series Test)', 'en' => 'Series Test', 'free' => false],
            ['subject' => $reasoning, 'num' => 16, 'hi' => 'सादृश्यता एवं वर्गीकरण (Analogy & Classification)', 'en' => 'Analogy & Classification', 'free' => false],
            ['subject' => $reasoning, 'num' => 17, 'hi' => 'न्याय निगमन (Syllogism)', 'en' => 'Syllogism', 'free' => false],
            ['subject' => $reasoning, 'num' => 18, 'hi' => 'बैठक व्यवस्था (Seating Arrangement)', 'en' => 'Seating Arrangement', 'free' => false],
            ['subject' => $reasoning, 'num' => 19, 'hi' => 'लुप्त पद ज्ञात करना (Missing Number)', 'en' => 'Finding Missing Terms', 'free' => false],
            ['subject' => $reasoning, 'num' => 20, 'hi' => 'गैर-शाब्दिक तर्कशास्त्र (Non-Verbal Reasoning)', 'en' => 'Non-Verbal Reasoning', 'free' => false],

            // Science & Maths (12 Chapters)
            ['subject' => $mathScience, 'num' => 21, 'hi' => 'भौतिक विज्ञान: गति, बल एवं ऊर्जा', 'en' => 'Physics: Motion, Force & Energy', 'free' => false],
            ['subject' => $mathScience, 'num' => 22, 'hi' => 'रसायन विज्ञान: पदार्थ एवं रासायनिक अभिक्रियाएं', 'en' => 'Chemistry: Matter & Reactions', 'free' => false],
            ['subject' => $mathScience, 'num' => 23, 'hi' => 'जीव विज्ञान: मानव शरीर एवं पोषण', 'en' => 'Biology: Human Body & Nutrition', 'free' => false],
            ['subject' => $mathScience, 'num' => 24, 'hi' => 'पर्यावरण एवं पारिस्थिकी', 'en' => 'Environment & Ecology', 'free' => false],
            ['subject' => $mathScience, 'num' => 25, 'hi' => 'संख्या पद्धति (Number System)', 'en' => 'Number System', 'free' => false],
            ['subject' => $mathScience, 'num' => 26, 'hi' => 'प्रतिशत (Percentage)', 'en' => 'Percentage', 'free' => false],
            ['subject' => $mathScience, 'num' => 27, 'hi' => 'लाभ एवं हानि (Profit & Loss)', 'en' => 'Profit & Loss', 'free' => false],
            ['subject' => $mathScience, 'num' => 28, 'hi' => 'साधारण एवं चक्रवृद्धि ब्याज (Simple & Compound Interest)', 'en' => 'Simple & Compound Interest', 'free' => false],
            ['subject' => $mathScience, 'num' => 29, 'hi' => 'अनुपात एवं समानुपात (Ratio & Proportion)', 'en' => 'Ratio & Proportion', 'free' => false],
            ['subject' => $mathScience, 'num' => 30, 'hi' => 'कार्य और समय (Time and Work)', 'en' => 'Time & Work', 'free' => false],
            ['subject' => $mathScience, 'num' => 31, 'hi' => 'समय, चाल और दूरी (Time, Speed & Distance)', 'en' => 'Time, Speed & Distance', 'free' => false],
            ['subject' => $mathScience, 'num' => 32, 'hi' => 'क्षेत्रमिति (Mensuration 2D & 3D)', 'en' => 'Mensuration 2D & 3D', 'free' => false],
        ];

        $allChapters = [];
        foreach ($chaptersData as $index => $data) {
            $ch = Chapter::create([
                'subject_id' => $data['subject']->id,
                'chapter_number' => $index + 1,
                'title_hi' => $data['hi'],
                'title_en' => $data['en'],
                'description_hi' => 'MP Police GD परीक्षा Blueprint के अनुसार संपूर्ण सिद्धांत एवं अभ्यास प्रश्न।',
                'is_free_preview' => $data['free'],
                'duration_minutes' => 25,
                'total_questions' => 15,
                'notes_content_hi' => '<h3>' . $data['hi'] . ' - संपूर्ण अध्ययन सामग्री</h3><p>यह अध्याय मध्य प्रदेश पुलिस आरक्षक भर्ती 2026 के लिए अत्यंत महत्वपूर्ण है। इसमें निम्नलिखित मुख्य बिंदु शामिल हैं:</p><ul><li>परीक्षा में बार-बार पूछे जाने वाले सिद्धांत एवं सूत्र</li><li>विगत वर्षों (2017-2023) के प्रश्नों का गहन विश्लेषण</li><li>शॉर्टकट ट्रिक्स एवं अभ्यास हेतु उदाहरण</li></ul>',
                'notes_content_en' => '<h3>' . $data['en'] . ' - Complete Study Material</h3><p>This chapter is crucial for MP Police Constable GD 2026. Key coverage includes core formulas, past year trends, and practice sets.</p>',
                'order' => $index + 1,
            ]);
            $allChapters[] = $ch;
        }

        // 4. Create 10 Full Mock Tests
        $mockTests = [];
        for ($i = 1; $i <= 10; $i++) {
            $mt = MockTest::create([
                'test_number' => $i,
                'title_hi' => sprintf('फुल मॉक टेस्ट %02d (सम्पूर्ण पाठ्यक्रम)', $i),
                'title_en' => sprintf('Full Mock Test %02d (Complete Syllabus)', $i),
                'duration_minutes' => 120,
                'total_questions' => 100,
                'total_marks' => 100,
                'is_free' => ($i == 1), // Test 1 is free preview
                'description_hi' => '100 प्रश्न | 120 मिनट | सामान्य ज्ञान (40), रीजनिंग (30), विज्ञान व गणित (30)',
            ]);
            $mockTests[] = $mt;
        }

        // 5. Seed Questions for Chapter 1, Chapter 2, Chapter 3 & Mock Test 1
        $sampleQuestions = [
            [
                'chapter_id' => $allChapters[0]->id,
                'subject_id' => $gk->id,
                'hi' => 'मध्य प्रदेश का गठन किस वर्ष हुआ था?',
                'en' => 'In which year was Madhya Pradesh formed?',
                'a' => '1 नवंबर 1956', 'b' => '15 अगस्त 1947', 'c' => '26 जनवरी 1950', 'd' => '1 नवंबर 2000',
                'correct' => 'A',
                'exp_hi' => 'मध्य प्रदेश का गठन राज्य पुनर्गठन आयोग की सिफारिश पर 1 नवंबर 1956 को किया गया था।',
            ],
            [
                'chapter_id' => $allChapters[0]->id,
                'subject_id' => $gk->id,
                'hi' => 'मध्य प्रदेश का राज्य पशु कौन सा है?',
                'en' => 'Which is the state animal of Madhya Pradesh?',
                'a' => 'बाघ (Tiger)', 'b' => 'बारासिंगा (Branderi Barasingha)', 'c' => 'सफेद शेर', 'd' => 'चिंकारा',
                'correct' => 'B',
                'exp_hi' => 'मध्य प्रदेश का राजकीय पशु ब्रेडरी प्रजाति का बारासिंगा है जो मुख्य रूप से कान्हा किसली राष्ट्रीय उद्यान में पाया जाता है।',
            ],
            [
                'chapter_id' => $allChapters[1]->id,
                'subject_id' => $gk->id,
                'hi' => 'खजुराहो के प्रसिद्ध मंदिरों का निर्माण किस राजवंश ने करवाया था?',
                'en' => 'Which dynasty built the famous temples of Khajuraho?',
                'a' => 'परमार वंश', 'b' => 'चन्देल वंश (Chandela Dynasty)', 'c' => 'मौर्य वंश', 'd' => 'गुप्त वंश',
                'correct' => 'B',
                'exp_hi' => 'खजुराहो के विश्व प्रसिद्ध मंदिरों का निर्माण 950 से 1050 ईस्वी के मध्य चन्देल राजाओं द्वारा करवाया गया था।',
            ],
            [
                'chapter_id' => $allChapters[2]->id,
                'subject_id' => $gk->id,
                'hi' => 'मध्य प्रदेश की सबसे लंबी नदी कौन सी है जिसे MP की जीवनरेखा कहा जाता है?',
                'en' => 'Which is the longest river in MP, known as the lifeline of MP?',
                'a' => 'चंबल नदी', 'b' => 'नर्मदा नदी (Narmada River)', 'c' => 'ताप्ती नदी', 'd' => 'बेतवा नदी',
                'correct' => 'B',
                'exp_hi' => 'नर्मदा नदी की कुल लंबाई 1312 किमी है जिसमें से 1077 किमी मध्य प्रदेश में बहती है। इसे मध्य प्रदेश की जीवन रेखा कहा जाता है।',
            ],
            [
                'chapter_id' => $allChapters[11]->id,
                'subject_id' => $reasoning->id,
                'hi' => 'यदि किसी कूट भाषा में POLICE को QPMJDF लिखा जाता है, तो MPPOLICE को कैसे लिखा जाएगा?',
                'en' => 'If POLICE is coded as QPMJDF, how will MPPOLICE be coded?',
                'a' => 'NQQPJMDF', 'b' => 'NQJQPMDF', 'c' => 'NQQPJFMD', 'd' => 'NQQPMJDF',
                'correct' => 'D',
                'exp_hi' => 'प्रत्येक अक्षर में +1 जोड़ा गया है: M+1=N, P+1=Q, P+1=Q, O+1=P, L+1=M, I+1=J, C+1=D, E+1=F।',
            ],
            [
                'chapter_id' => $allChapters[12]->id,
                'subject_id' => $reasoning->id,
                'hi' => 'A, B का भाई है। C, A की माता है। D, C का पिता है। B का D से क्या संबंध है?',
                'en' => 'A is B\'s brother. C is A\'s mother. D is C\'s father. How is B related to D?',
                'a' => 'पुत्र', 'b' => 'नवासी / नाती (Grandchild)', 'c' => 'पिता', 'd' => 'दादा',
                'correct' => 'B',
                'exp_hi' => 'D -> C (पुत्री) -> A, B (संतान)। इसलिए B, D का नाती या नातिन (Grandchild) है।',
            ],
            [
                'chapter_id' => $allChapters[24]->id,
                'subject_id' => $mathScience->id,
                'hi' => 'प्रथम 10 सम प्राकृतिक संख्याओं का औसत क्या होगा?',
                'en' => 'What is the average of the first 10 even natural numbers?',
                'a' => '10', 'b' => '11', 'c' => '12', 'd' => '10.5',
                'correct' => 'B',
                'exp_hi' => 'प्रथम n सम प्राकृतिक संख्याओं का औसत (n + 1) होता है। अतः औसत = 10 + 1 = 11।',
            ],
            [
                'chapter_id' => $allChapters[29]->id,
                'subject_id' => $mathScience->id,
                'hi' => 'यदि A और B किसी कार्य को 10 दिन में कर सकते हैं और A अकेला इसे 15 दिन में करता है, तो B अकेला कितने दिन में करेगा?',
                'en' => 'If A and B complete work in 10 days, A alone in 15 days, how many days for B alone?',
                'a' => '20 दिन', 'b' => '25 दिन', 'c' => '30 दिन', 'd' => '35 दिन',
                'correct' => 'C',
                'exp_hi' => 'B का 1 दिन का कार्य = (1/10) - (1/15) = (3 - 2)/30 = 1/30। अतः B अकेला 30 दिन में पूरा करेगा।',
            ],
        ];

        foreach ($sampleQuestions as $q) {
            Question::create([
                'chapter_id' => $q['chapter_id'],
                'subject_id' => $q['subject_id'],
                'mock_test_id' => $mockTests[0]->id, // Add to Full Mock 1 as well
                'question_text_hi' => $q['hi'],
                'question_text_en' => $q['en'],
                'option_a' => $q['a'],
                'option_b' => $q['b'],
                'option_c' => $q['c'],
                'option_d' => $q['d'],
                'correct_option' => $q['correct'],
                'explanation_hi' => $q['exp_hi'],
                'explanation_en' => 'Detailed explanation provided.',
                'difficulty_level' => 'medium',
            ]);
        }

        // Add additional 15 mock test questions for Mock 1
        for ($k = 1; $k <= 12; $k++) {
            Question::create([
                'mock_test_id' => $mockTests[0]->id,
                'subject_id' => $gk->id,
                'question_text_hi' => "मध्य प्रदेश अभ्यास प्रश्न #{$k}: सांची का स्तूप किस जिले में स्थित है?",
                'question_text_en' => "Practice Question #{$k}: In which district Sanchi Stupa is located?",
                'option_a' => 'रायसेन (Raisen)',
                'option_b' => 'विदिशा',
                'option_c' => 'भोपाल',
                'option_d' => 'सीहोर',
                'correct_option' => 'A',
                'explanation_hi' => 'सांची स्तूप मध्य प्रदेश के रायसेन जिले में बेतवा नदी के तट पर स्थित महान बौद्ध स्मारक है।',
            ]);
        }

        // 6. Seed Sample Test Attempts for Rahul Sharma
        TestAttempt::create([
            'user_id' => $studentRahul->id,
            'test_type' => 'chapter',
            'chapter_id' => $allChapters[0]->id,
            'total_questions' => 15,
            'attempted_questions' => 15,
            'correct_answers' => 13,
            'wrong_answers' => 2,
            'score' => 13.0,
            'total_marks' => 15,
            'percentage' => 86.67,
            'accuracy_percentage' => 86.67,
            'time_taken_seconds' => 740,
            'completed_at' => Carbon::now()->subDays(2),
        ]);

        TestAttempt::create([
            'user_id' => $studentRahul->id,
            'test_type' => 'full_mock',
            'mock_test_id' => $mockTests[0]->id,
            'total_questions' => 100,
            'attempted_questions' => 92,
            'correct_answers' => 74,
            'wrong_answers' => 18,
            'score' => 74.0,
            'total_marks' => 100,
            'percentage' => 74.0,
            'accuracy_percentage' => 80.43,
            'time_taken_seconds' => 6400,
            'completed_at' => Carbon::now()->subHours(12),
        ]);

        // 7. Seed Official Certificate for Rahul
        Certificate::create([
            'user_id' => $studentRahul->id,
            'certificate_code' => 'TW-CERT-2026-GD-101',
            'course_name' => 'MP Police Constable GD 2026',
            'issue_date' => Carbon::now()->subDay(),
            'score_achieved' => 86.5,
            'is_verified' => true,
        ]);

        // 8. Seed Payments
        Payment::create([
            'user_id' => $studentRahul->id,
            'order_id' => 'ORD_2026_98215',
            'amount' => 499.00,
            'payment_method' => 'UPI (Google Pay)',
            'status' => 'PAID',
            'transaction_ref' => 'order_mtyedfttu_c3950',
        ]);

        Payment::create([
            'user_id' => $studentRajesh->id,
            'order_id' => 'ORD_2026_98216',
            'amount' => 499.00,
            'payment_method' => 'Debit Card',
            'status' => 'PAID',
            'transaction_ref' => 'order_mtyejHqj_cicv',
        ]);

        Payment::create([
            'user_id' => $studentQa->id,
            'order_id' => 'ORD_2026_98217',
            'amount' => 249.00,
            'payment_method' => 'UPI',
            'status' => 'PAID',
            'transaction_ref' => 'order_mtyeg5kd_a47c5',
        ]);
    }
}
