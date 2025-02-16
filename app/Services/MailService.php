<?php

namespace App\Services;

use Luminode\Contracts\Mailer;
use Luminode\Mail\Mailable;
use Luminode\Queue\Dispatchable;

class MailService implements Mailer
{
    public function send(Mailable $mailable)
    {
        // 实际发送逻辑
        $this->dispatchToMailer($mailable);
    }

    public function queue(Mailable $mailable)
    {
        return $this->dispatchToQueue($mailable);
    }

    protected function dispatchToMailer(Mailable $mailable)
    {
        // 使用配置的mailer发送
        $mailer = config('mail.default');
        $this->resolveMailer($mailer)->send($mailable);
    }

    protected function dispatchToQueue(Mailable $mailable)
    {
        if ($mailable instanceof Dispatchable) {
            return $mailable->dispatch();
        }

        return dispatch(new SendQueuedMailable($mailable));
    }
}