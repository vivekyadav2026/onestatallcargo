<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Chapter;
use App\Models\MockTest;
use App\Models\Question;
use App\Models\TestAttempt;
use App\Models\Certificate;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentController extends Controller
{
    protected function getStudentUser()
    {
        if (!Auth::check() || !Auth::user()->isStudent()) {
            abort(403, 'Unauthorized access.');
        }
        return Auth::user();
    }

    protected function getCurrentCourse()
    {
        $user = $this->getStudentUser();
        $courseId = session('current_course_id');
        
        if ($courseId) {
            $course = \App\Models\Course::find($courseId);
            if ($course) {
                \App\Models\Enrollment::firstOrCreate([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                ]);
                return $course;
            }
        }

        // Fallback to first enrolled course
        $firstEnrollment = $user->enrollments()->first();
        if ($firstEnrollment && $firstEnrollment->course) {
            session(['current_course_id' => $firstEnrollment->course_id]);
            return $firstEnrollment->course;
        }

        // Fallback to default course (ID 1 or first active)
        $defaultCourse = \App\Models\Course::where('is_active', true)->first();
        if ($defaultCourse) {
            \App\Models\Enrollment::firstOrCreate([
                'user_id' => $user->id,
                'course_id' => $defaultCourse->id,
            ]);
            session(['current_course_id' => $defaultCourse->id]);
        }
        return $defaultCourse;
    }

    public function myCourses(Request $request)
    {
        $user = $this->getStudentUser();
        // Fetch courses the student is enrolled in
        $enrollments = $user->enrollments()->with('course')->get();
        // Or if you want to show all active courses with lock/unlock status
        $courses = \App\Models\Course::where('is_active', true)->get();
        
        return view('student.my-courses', compact('user', 'enrollments', 'courses'));
    }

    public function dashboard(Request $request)
    {
        $user = $this->getStudentUser();
        $course = $this->getCurrentCourse();

        // 1. Get Course Content
        $subjects = Subject::where('course_id', $course->id)->with('chapters')->orderBy('order')->get();
        $totalChapters = Chapter::whereHas('subject', function($q) use ($course) {
            $q->where('course_id', $course->id);
        })->count();
        $totalMocks = MockTest::where('course_id', $course->id)->count();

        // 2. Get User Progress for this course
        $attempts = TestAttempt::where('user_id', $user->id)
            ->where(function($query) use ($course) {
                $query->whereHas('chapter.subject', function($q) use ($course) {
                    $q->where('course_id', $course->id);
                })->orWhereHas('mockTest', function($q) use ($course) {
                    $q->where('course_id', $course->id);
                });
            })
            ->get();
            
        $completedChaptersCount = $attempts->where('test_type', 'chapter')->unique('chapter_id')->count();

        $overallAccuracy = $attempts->avg('accuracy_percentage') ?: 0;
        $totalStudySeconds = $attempts->sum('time_taken_seconds');
        $totalStudyMinutes = floor($totalStudySeconds / 60);

        $mockTests = MockTest::where('course_id', $course->id)->orderBy('test_number')->get();
        $mockAttempts = $attempts->where('test_type', 'full_mock')->keyBy('mock_test_id');

        foreach ($subjects as $sub) {
            $chapterIds = $sub->chapters->pluck('id');
            $done = $attempts->whereIn('chapter_id', $chapterIds)->unique('chapter_id')->count();
            $sub->completed_chapters = $done;
            $sub->total_count = $sub->chapters->count();
            $sub->percentage = $sub->total_count > 0 ? round(($done / $sub->total_count) * 100) : 0;
        }

        $recentAttempts = TestAttempt::where('user_id', $user->id)
            ->where(function($query) use ($course) {
                $query->whereHas('chapter.subject', function($q) use ($course) {
                    $q->where('course_id', $course->id);
                })->orWhereHas('mockTest', function($q) use ($course) {
                    $q->where('course_id', $course->id);
                });
            })
            ->with(['chapter', 'mockTest'])
            ->latest()
            ->take(5)
            ->get();

        $certificate = Certificate::where('user_id', $user->id)->where('course_id', $course->id)->first();
        
        // Pass course along with user
        $isPro = $user->isProFor($course->id);

        $weakCount = 2; // Stub

        return view('student.dashboard', compact(
            'user',
            'course',
            'isPro',
            'totalChapters',
            'completedChaptersCount',
            'overallAccuracy',
            'totalStudyMinutes',
            'mockTests',
            'mockAttempts',
            'subjects',
            'recentAttempts',
            'certificate',
            'weakCount'
        ));
    }

    public function course(Request $request)
    {
        $user = $this->getStudentUser();
        $course = $this->getCurrentCourse();
        $isPro = $user->isProFor($course->id);

        $selectedSubject = $request->input('subject', 'all');
        
        $subjects = Subject::where('course_id', $course->id)->orderBy('order')->get();
        
        $query = Chapter::whereHas('subject', function($q) use ($course) {
            $q->where('course_id', $course->id);
        })->with('subject')->orderBy('chapter_number');

        if ($selectedSubject !== 'all') {
            $query->whereHas('subject', function($q) use ($selectedSubject, $course) {
                $q->where('course_id', $course->id)->where('code', $selectedSubject);
            });
        }

        $chapters = $query->get();
        $totalCourseChapters = Chapter::whereHas('subject', function($q) use ($course) {
            $q->where('course_id', $course->id);
        })->count();

        $userAttempts = TestAttempt::where('user_id', $user->id)
            ->where('test_type', 'chapter')
            ->whereHas('chapter.subject', function($q) use ($course) {
                $q->where('course_id', $course->id);
            })
            ->get()
            ->keyBy('chapter_id');

        return view('student.chapter-tests', compact(
            'user',
            'course',
            'isPro',
            'chapters',
            'subjects',
            'selectedSubject',
            'userAttempts',
            'totalCourseChapters'
        ));
    }

    public function switchCourse(Request $request)
    {
        $courseId = $request->input('course_id');
        if ($courseId) {
            $user = $this->getStudentUser();
            \App\Models\Enrollment::firstOrCreate([
                'user_id' => $user->id,
                'course_id' => $courseId,
            ]);
            session(['current_course_id' => $courseId]);
        }
        
        // If coming from my-courses, go to syllabus (course view)
        if (str_contains(url()->previous(), 'my-courses')) {
            return redirect()->route('student.course')->with('success', 'Course activated successfully.');
        }
        
        return redirect()->back()->with('success', 'Course switched successfully.');
    }

    public function readNotes($id)
    {
        $user = $this->getStudentUser();
        $chapter = Chapter::with('subject.course')->findOrFail($id);
        $courseId = $chapter->subject->course_id ?? session('current_course_id') ?? 1;
        $isPro = $user->isProFor($courseId);

        if (!$chapter->is_free_preview && !$isPro) {
            return redirect()->route('student.course')->with('error', 'यह अध्याय Pro सब्सक्रिप्शन के साथ अनलॉक होगा! (This chapter requires Pro unlock ₹499)');
        }

        return view('student.notes', compact('user', 'chapter'));
    }

    public function mockTests()
    {
        $user = $this->getStudentUser();
        $course = $this->getCurrentCourse();
        $isPro = $user->isProFor($course->id);

        $mockTests = MockTest::where('course_id', $course->id)->orderBy('test_number')->get();
        $userAttempts = TestAttempt::where('user_id', $user->id)
            ->where('test_type', 'full_mock')
            ->whereHas('mockTest', function($q) use ($course) {
                $q->where('course_id', $course->id);
            })
            ->get()
            ->keyBy('mock_test_id');

        return view('student.mock-tests', compact('user', 'course', 'isPro', 'mockTests', 'userAttempts'));
    }

    public function takeCbtTest($type, $id)
    {
        $user = $this->getStudentUser();

        if ($type === 'chapter') {
            $item = Chapter::with('subject')->findOrFail($id);
            $courseId = $item->subject->course_id ?? session('current_course_id') ?? 1;
            $isPro = $user->isProFor($courseId);

            if (!$item->is_free_preview && !$isPro) {
                return redirect()->route('student.course')->with('error', 'यह टेस्ट केवल Pro सदस्यों के लिए उपलब्ध है। (Unlock for ₹499)');
            }
            $questions = Question::where('chapter_id', $id)->get();
            if ($questions->count() == 0) {
                // Fallback sample questions
                $questions = Question::whereNotNull('subject_id')->take(10)->get();
            }
            $title = $item->title_hi;
            $durationMinutes = $item->duration_minutes ?: 20;
        } else {
            $item = MockTest::findOrFail($id);
            $courseId = $item->course_id ?? session('current_course_id') ?? 1;
            $isPro = $user->isProFor($courseId);

            if (!$item->is_free && !$isPro) {
                return redirect()->route('student.mock-tests')->with('error', 'यह फुल मॉक टेस्ट Pro सब्सक्रिप्शन के साथ उपलब्ध है।');
            }
            $questions = Question::where('mock_test_id', $id)->get();
            if ($questions->count() == 0) {
                $questions = Question::take(20)->get();
            }
            $title = $item->title_hi;
            $durationMinutes = $item->duration_minutes ?: 120;
        }

        return view('student.cbt-engine', compact('user', 'type', 'id', 'item', 'questions', 'title', 'durationMinutes'));
    }

    public function submitCbtTest(Request $request, $type, $id)
    {
        $user = $this->getStudentUser();
        $answers = $request->input('answers', []);
        $timeTaken = (int) $request->input('time_taken_seconds', 300);

        if ($type === 'chapter') {
            $chapter = Chapter::findOrFail($id);
            $questions = Question::where('chapter_id', $id)->get();
            if ($questions->count() == 0) {
                $questions = Question::take(10)->get();
            }
            $totalQuestions = $questions->count();
        } else {
            $mockTest = MockTest::findOrFail($id);
            $questions = Question::where('mock_test_id', $id)->get();
            if ($questions->count() == 0) {
                $questions = Question::take(20)->get();
            }
            $totalQuestions = $questions->count();
        }

        $correctCount = 0;
        $attemptedCount = 0;
        $wrongCount = 0;
        $processedAnswers = [];

        foreach ($questions as $q) {
            $userAns = $answers[$q->id] ?? null;
            $isCorrect = false;

            if ($userAns) {
                $attemptedCount++;
                if (strtoupper($userAns) === strtoupper($q->correct_option)) {
                    $correctCount++;
                    $isCorrect = true;
                } else {
                    $wrongCount++;
                }
            }

            $processedAnswers[$q->id] = [
                'user_option' => $userAns,
                'correct_option' => $q->correct_option,
                'is_correct' => $isCorrect,
            ];
        }

        $score = $correctCount;
        $percentage = $totalQuestions > 0 ? round(($score / $totalQuestions) * 100, 2) : 0;
        $accuracy = $attemptedCount > 0 ? round(($correctCount / $attemptedCount) * 100, 2) : 0;

        $attempt = TestAttempt::create([
            'user_id' => $user->id,
            'test_type' => $type === 'chapter' ? 'chapter' : 'full_mock',
            'chapter_id' => $type === 'chapter' ? $id : null,
            'mock_test_id' => $type === 'full_mock' ? $id : null,
            'total_questions' => $totalQuestions,
            'attempted_questions' => $attemptedCount,
            'correct_answers' => $correctCount,
            'wrong_answers' => $wrongCount,
            'score' => $score,
            'total_marks' => $totalQuestions,
            'percentage' => $percentage,
            'accuracy_percentage' => $accuracy,
            'time_taken_seconds' => $timeTaken,
            'answers_json' => $processedAnswers,
            'completed_at' => Carbon::now(),
        ]);

        return redirect()->route('student.test-result', $attempt->id);
    }

    public function testResult($attemptId)
    {
        $user = $this->getStudentUser();
        $attempt = TestAttempt::where('user_id', $user->id)
            ->with(['chapter', 'mockTest'])
            ->findOrFail($attemptId);

        if ($attempt->test_type === 'chapter' && $attempt->chapter_id) {
            $questions = Question::where('chapter_id', $attempt->chapter_id)->get();
        } else if ($attempt->mock_test_id) {
            $questions = Question::where('mock_test_id', $attempt->mock_test_id)->get();
        } else {
            $questions = Question::take(10)->get();
        }

        return view('student.test-result', compact('user', 'attempt', 'questions'));
    }

    public function mistakes()
    {
        $user = $this->getStudentUser();
        // Extract wrong questions from user's attempts
        $attempts = TestAttempt::where('user_id', $user->id)->get();
        $wrongQuestionIds = [];

        foreach ($attempts as $att) {
            if ($att->answers_json) {
                foreach ($att->answers_json as $qId => $data) {
                    if (isset($data['is_correct']) && !$data['is_correct'] && $data['user_option']) {
                        $wrongQuestionIds[] = $qId;
                    }
                }
            }
        }

        $mistakeQuestions = Question::whereIn('id', array_unique($wrongQuestionIds))->with('subject')->get();

        return view('student.mistakes', compact('user', 'mistakeQuestions'));
    }

    public function weakTopics()
    {
        $user = $this->getStudentUser();

        // Sample weak topics identified for MP Police GD
        $attempts = TestAttempt::where('user_id', $user->id)->whereNotNull('chapter_id')->with('chapter.subject')->get();
        $chapterStats = [];

        foreach ($attempts as $attempt) {
            $ch = $attempt->chapter;
            if (!$ch) continue;

            $cid = $ch->id;
            if (!isset($chapterStats[$cid])) {
                $chapterStats[$cid] = [
                    'subject' => $ch->subject ? $ch->subject->name_hi : 'General',
                    'topic_hi' => $ch->title_hi,
                    'total_attempted' => 0,
                    'total_correct' => 0,
                ];
            }
            $chapterStats[$cid]['total_attempted'] += $attempt->attempted_questions;
            $chapterStats[$cid]['total_correct'] += $attempt->correct_answers;
        }

        $weakTopics = [];
        foreach ($chapterStats as $cid => $stats) {
            if ($stats['total_attempted'] > 0) {
                $acc = round(($stats['total_correct'] / $stats['total_attempted']) * 100);
                if ($acc < 70) {
                    $weakTopics[] = [
                        'subject' => $stats['subject'],
                        'topic_hi' => $stats['topic_hi'],
                        'accuracy' => $acc,
                        'total_questions' => $stats['total_attempted'], // User's total attempts
                    ];
                }
            }
        }
        
        usort($weakTopics, function($a, $b) {
            return $a['accuracy'] <=> $b['accuracy'];
        });

        return view('student.weak-topics', compact('user', 'weakTopics'));
    }

    public function certificate()
    {
        $user = $this->getStudentUser();
        $certificate = Certificate::where('user_id', $user->id)->first();

        if (!$certificate) {
            $accuracy = \App\Models\TestAttempt::where('user_id', $user->id)->avg('accuracy_percentage') ?: 85.0;

            $certificate = Certificate::create([
                'user_id' => $user->id,
                'certificate_code' => 'TW-CERT-' . date('Y') . '-' . strtoupper(\Illuminate\Support\Str::random(6)),
                'course_name' => 'MP Police Constable GD 2026',
                'issue_date' => \Carbon\Carbon::now(),
                'score_achieved' => round($accuracy, 1),
                'is_verified' => true,
            ]);
        }

        return view('student.certificate', compact('user', 'certificate'));
    }

    public function performance()
    {
        $user = $this->getStudentUser();
        $attempts = TestAttempt::where('user_id', $user->id)->latest()->get();

        return view('student.performance', compact('user', 'attempts'));
    }

    public function unlockPro(Request $request)
    {
        $user = $this->getStudentUser();
        $user->is_pro = true;
        $user->save();

        Payment::create([
            'user_id' => $user->id,
            'order_id' => 'ORD_' . date('Y') . '_' . rand(10000, 99999),
            'amount' => 499.00,
            'payment_method' => 'UPI',
            'status' => 'PAID',
            'transaction_ref' => 'order_' . uniqid(),
        ]);

        return redirect()->back()->with('success', 'बधाई हो! आपका MP Police Constable GD 2026 Pro कोर्स सफलतापूर्वक अनलॉक हो गया है!');
    }

    public function profile()
    {
        $user = $this->getStudentUser();
        return view('student.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = $this->getStudentUser();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'goal' => 'nullable|string|max:255',
            'daily_study_goal_minutes' => 'nullable|integer|min:10',
        ]);

        $user->update($validated);
        return redirect()->back()->with('success', 'आपकी प्रोफाइल जानकारी सफलतापूर्वक अपडेट कर दी गई है।');
    }

    public function updatePassword(Request $request)
    {
        $user = $this->getStudentUser();
        
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'वर्तमान पासवर्ड गलत है।']);
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('success', 'आपका पासवर्ड सफलतापूर्वक बदल दिया गया है।');
    }
}
