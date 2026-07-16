<?php

// app/Mail/FormSubmissionMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

//class FormSubmissionMail extends Mailable implements ShouldQueue
class FormSubmissionMail extends Mailable
{
    //use Queueable, SerializesModels;
    use SerializesModels;

    public $formName;
    public $data;

    public function __construct($formName, $data)
    {
        $this->formName = $formName;
        $this->data = $data;
    }

    public function build()
    {
        $fromAddress = filter_var($this->data['email'] ?? null, FILTER_VALIDATE_EMAIL)
            ? $this->data['email']
            : config('mail.from.address');

        $fromName = $this->data['name'] ?? config('mail.from.name');

        return $this->from($fromAddress, $fromName)
            ->subject(config('custom.app_name'))
            ->markdown('emails.form_submission');
    }
}
