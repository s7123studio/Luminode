<?php

// 定义命名空间，表示该类属于App\Services
namespace App\Services;

// 引入Luminode\Contracts\Mailer接口，用于邮件发送
use Luminode\Contracts\Mailer;
// 引入Luminode\Mail\Mailable类，用于创建邮件对象
use Luminode\Mail\Mailable;
// 引入Luminode\Queue\Dispatchable接口，用于队列调度
use Luminode\Queue\Dispatchable;

// MailService类实现Mailer接口，提供邮件发送功能
class MailService implements Mailer
{
    // 发送邮件的方法
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