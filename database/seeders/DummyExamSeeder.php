<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\MockTest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyExamSeeder extends Seeder
{
    public function run()
    {
        // Add SSC CGL
        $ssc = Course::create([
            'title_hi' => 'SSC CGL Tier 1',
            'title_en' => 'SSC CGL Tier 1',
            'slug' => 'ssc-cgl-tier-1',
            'description_hi' => 'Complete Webbook and Mock Test Series for SSC CGL Tier 1 Exam 2026.',
            'price' => 1999,
            'discounted_price' => 599,
            'is_active' => true,
        ]);

        $sscSubjects = [
            ['name_hi' => 'Quantitative Aptitude', 'name_en' => 'Maths', 'code' => 'SSC-MATH', 'marks' => 50, 'order' => 1],
            ['name_hi' => 'General Intelligence & Reasoning', 'name_en' => 'Reasoning', 'code' => 'SSC-REAS', 'marks' => 50, 'order' => 2],
            ['name_hi' => 'English Comprehension', 'name_en' => 'English', 'code' => 'SSC-ENG', 'marks' => 50, 'order' => 3],
            ['name_hi' => 'General Awareness', 'name_en' => 'GK', 'code' => 'SSC-GK', 'marks' => 50, 'order' => 4],
        ];

        foreach ($sscSubjects as $sub) {
            $subject = Subject::create([
                'course_id' => $ssc->id,
                'name_hi' => $sub['name_hi'],
                'name_en' => $sub['name_en'],
                'code' => $sub['code'],
                'total_marks' => $sub['marks'],
                'order' => $sub['order'],
            ]);

            // Add chapters
            for ($i = 1; $i <= 3; $i++) {
                Chapter::create([
                    'subject_id' => $subject->id,
                    'chapter_number' => $i,
                    'title_hi' => "SSC Chapter {$i} for {$sub['name_en']}",
                    'title_en' => "SSC Chapter {$i} for {$sub['name_en']}",
                    'notes_content_hi' => "<p>Dummy content for SSC {$sub['name_en']} chapter {$i}</p>",
                    'notes_content_en' => "<p>Dummy content for SSC {$sub['name_en']} chapter {$i}</p>",
                    'is_free_preview' => ($i == 1),
                ]);
            }
        }

        MockTest::create([
            'course_id' => $ssc->id,
            'test_number' => 1,
            'title_hi' => 'SSC CGL Tier 1 Full Mock 1',
            'title_en' => 'SSC CGL Tier 1 Full Mock 1',
            'duration_minutes' => 60,
            'total_questions' => 100,
            'total_marks' => 200,
            'is_free' => true,
        ]);

        // Add MP Patwari
        $patwari = Course::create([
            'title_hi' => 'MP Patwari Exam 2026',
            'title_en' => 'MP Patwari Exam 2026',
            'slug' => 'mp-patwari-2026',
            'description_hi' => 'MP Patwari full course with General Management, Hindi, Computer and more.',
            'price' => 1599,
            'discounted_price' => 499,
            'is_active' => true,
        ]);

        $patwariSubjects = [
            ['name_hi' => 'सामान्य प्रबंधन', 'name_en' => 'General Management', 'code' => 'PAT-MGT', 'marks' => 25, 'order' => 1],
            ['name_hi' => 'सामान्य कम्प्यूटर', 'name_en' => 'Computer', 'code' => 'PAT-COMP', 'marks' => 25, 'order' => 2],
            ['name_hi' => 'सामान्य हिंदी', 'name_en' => 'Hindi', 'code' => 'PAT-HIN', 'marks' => 25, 'order' => 3],
        ];

        foreach ($patwariSubjects as $sub) {
            $subject = Subject::create([
                'course_id' => $patwari->id,
                'name_hi' => $sub['name_hi'],
                'name_en' => $sub['name_en'],
                'code' => $sub['code'],
                'total_marks' => $sub['marks'],
                'order' => $sub['order'],
            ]);

            // Add chapters
            for ($i = 1; $i <= 3; $i++) {
                Chapter::create([
                    'subject_id' => $subject->id,
                    'chapter_number' => $i,
                    'title_hi' => "Patwari Chapter {$i} for {$sub['name_en']}",
                    'title_en' => "Patwari Chapter {$i} for {$sub['name_en']}",
                    'notes_content_hi' => "<p>Dummy content for Patwari {$sub['name_en']} chapter {$i}</p>",
                    'notes_content_en' => "<p>Dummy content for Patwari {$sub['name_en']} chapter {$i}</p>",
                    'is_free_preview' => ($i == 1),
                ]);
            }
        }
        
        MockTest::create([
            'course_id' => $patwari->id,
            'test_number' => 1,
            'title_hi' => 'MP Patwari Full Mock 1',
            'title_en' => 'MP Patwari Full Mock 1',
            'duration_minutes' => 180,
            'total_questions' => 200,
            'total_marks' => 200,
            'is_free' => true,
        ]);
    }
}
