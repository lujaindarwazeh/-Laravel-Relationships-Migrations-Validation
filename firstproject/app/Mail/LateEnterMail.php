<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Student;

class LateEnterMail extends Mailable
{
    use Queueable, SerializesModels;
    public $lateStudents;
    
    


    /**
     * Create a new message instance.
     *
     * @return void
     */
     public function __construct($lateStudents)
    {
        $this->lateStudents = $lateStudents;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Late Enter Mail',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.late_entry',
            with: [
                'lateStudents' => $this->lateStudents,
            ]
        );
    }

    public function attachments()
    {
        return [];
    }
}
