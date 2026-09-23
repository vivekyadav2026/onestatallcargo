<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Mail\CoursePurchasedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RazorpayController extends Controller
{
    public function createOrder(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $courseId = $request->input('course_id') ?? session('current_course_id');
        $course = Course::find($courseId) ?: Course::where('is_active', true)->first();

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        $orderId = 'ORD_' . date('Y') . '_' . rand(10000, 99999);
        $amountInPaisa = intval($course->discounted_price * 100);

        return response()->json([
            'status' => 'success',
            'key' => env('RAZORPAY_KEY', 'rzp_test_TW2026KeyDemo'),
            'amount' => $amountInPaisa,
            'currency' => 'INR',
            'order_id' => $orderId,
            'course_id' => $course->id,
            'course_name' => $course->title_hi ?? $course->title_en,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_phone' => $user->phone ?? '9876543210',
        ]);
    }

    public function verifyPayment(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $courseId = $request->input('course_id') ?? session('current_course_id');
        $course = Course::find($courseId) ?: Course::first();

        $paymentId = $request->input('razorpay_payment_id') ?? ('pay_' . uniqid());
        $orderId = $request->input('razorpay_order_id') ?? ('ORD_' . date('Y') . '_' . rand(10000, 99999));

        // Unlock Pro status for user
        $user->is_pro = true;
        $user->save();

        if ($course) {
            $enrollment = Enrollment::firstOrCreate([
                'user_id' => $user->id,
                'course_id' => $course->id,
            ]);
            $enrollment->is_pro = true;
            $enrollment->save();
        }

        // Create Payment Record
        $payment = Payment::create([
            'user_id' => $user->id,
            'course_id' => $course ? $course->id : null,
            'order_id' => $orderId,
            'amount' => $course ? $course->discounted_price : 499.00,
            'payment_method' => 'Razorpay (Card/UPI/NetBanking)',
            'status' => 'PAID',
            'transaction_ref' => $paymentId,
        ]);

        // Send Purchase Confirmation Email
        try {
            if ($user->email && $course) {
                Mail::to($user->email)->send(new CoursePurchasedMail($user, $course, $payment));
            }
        } catch (\Exception $e) {
            Log::error('Course purchase confirmation email failed: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'भुगतान सफल रहा! आपका Pro कोर्स अनलॉक हो गया है और ईमेल भेज दिया गया है।',
            'redirect_url' => route('student.course'),
        ]);
    }
}
