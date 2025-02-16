<?php

namespace App\Jobs;

use Luminode\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendQueuedMailable implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Mailable $mailable) {}

    public function handle(MailService $mailer)
    {
        $mailer->send($this->mailable);
    }
}