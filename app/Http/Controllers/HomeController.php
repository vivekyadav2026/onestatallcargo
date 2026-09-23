<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Chapter;
use App\Models\MockTest;
use App\Models\Certificate;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $courses = \App\Models\Course::where('is_active', true)->get();
        $totalExams = $courses->count();
        $freeChaptersCount = Chapter::where('is_free_preview', true)->count();
        $mockTestsCount = MockTest::count();

        return view('welcome', compact('courses', 'totalExams', 'freeChaptersCount', 'mockTestsCount'));
    }

    public function courses(Request $request)
    {
        $query = \App\Models\Course::where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title_hi', 'LIKE', "%{$search}%")
                  ->orWhere('title_en', 'LIKE', "%{$search}%")
                  ->orWhere('description_hi', 'LIKE', "%{$search}%");
            });
        }

        $courses = $query->paginate(6)->withQueryString();

        return view('courses', compact('courses'));
    }

    public function examDetails($slug)
    {
        $course = \App\Models\Course::where('slug', $slug)->firstOrFail();
        // You could create a dedicated exam-details view or pass it to exam-info
        return view('exam-details', compact('course'));
    }

    public function enroll($id)
    {
        $course = \App\Models\Course::findOrFail($id);

        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if ($user->isStudent()) {
                \App\Models\Enrollment::firstOrCreate([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                ]);
            }
            session(['current_course_id' => $course->id]);
            return redirect()->route('student.course')->with('success', 'आपने ' . ($course->title_hi ?? $course->title_en) . ' सफलतापूर्वक सेलेक्ट / एनरोल कर लिया है!');
        }

        session(['pending_course_id' => $course->id]);

        return redirect()->route('register', ['course_id' => $course->id])->with('info', 'कृपया ' . ($course->title_hi ?? $course->title_en) . ' में एनरोल करने के लिए रजिस्ट्रेशन करें या लॉगिन करें।');
    }

    public function freeContent()
    {
        $freeChapters = Chapter::where('is_free_preview', true)->with('subject')->get();
        $freeMockTest = MockTest::where('is_free', true)->first();

        return view('free-content', compact('freeChapters', 'freeMockTest'));
    }

    public function verifyCertificate(Request $request)
    {
        $searchCode = $request->input('code');
        $certificate = null;

        if ($searchCode) {
            $certificate = Certificate::where('certificate_code', trim($searchCode))->with('user')->first();
        }

        return view('verify-certificate', compact('certificate', 'searchCode'));
    }

    public function examInfo()
    {
        return view('exam-info');
    }

    public function contact()
    {
        return view('contact');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $chapters = Chapter::where('title_hi', 'LIKE', "%{$query}%")
            ->orWhere('title_en', 'LIKE', "%{$query}%")
            ->get();

        return response()->json([
            'query' => $query,
            'results' => $chapters,
        ]);
    }
}
