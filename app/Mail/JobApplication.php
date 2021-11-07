<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use function Psy\debug;

class JobApplication extends Mailable
{
    use Queueable, SerializesModels;

    private string $content;
    private ?string $attachment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $fromEmail, string $fromName, string $subject, string $content, string $attachment = null)
    {
        $this->from($fromEmail, $fromName);
        $this->subject($subject);
        $this->content = $content;
        $this->attachment = $attachment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = $this->view('emails.job-application', ['content' => $this->content]);
        if (!$this->attachment) {
            return $view;
        }

        return $view->attach($this->attachment, [
            'as' => 'portfolio.pdf',
            'mime' => 'application/pdf',
        ]);
    }
}
