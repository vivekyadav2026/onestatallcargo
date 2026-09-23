<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Course;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CoursePurchasedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $course;
    public $payment;

    public function __construct(User $user, Course $course, Payment $payment)
    {
        $this->user = $user;
        $this->course = $course;
        $this->payment = $payment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Course Purchase Confirmation - ' . ($this->course->title_hi ?? $this->course->title_en),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.course_purchased',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
