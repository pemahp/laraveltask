<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailToEmployee extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $employeeData;
    public function __construct($employee)
    {
        $this->employeeData = $employee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         return $this->from('ppanwar@ahpservicing.com', 'Mailtrap')
            ->subject('Test Queued Email')
            ->view('mails.email',['user'=>$this->employeeData]);
    }
}
