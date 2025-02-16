<?php

// 定义命名空间，表示这个类属于 App\Jobs 这个模块
namespace App\Jobs;

// 引入 Luminode\Mail\Mailable 类，用于邮件发送
use Luminode\Mail\Mailable;
// 引入 Illuminate\Bus\Queueable trait，用于队列处理
use Illuminate\Bus\Queueable;
// 引入 Illuminate\Contracts\Queue\ShouldQueue 接口，表示这个类应该被队列处理
use Illuminate\Contracts\Queue\ShouldQueue;

// 定义 SendQueuedMailable 类，实现 ShouldQueue 接口，表示这个类是一个可以被队列处理的任务
class SendQueuedMailable implements ShouldQueue
{
    // 使用 Queueable trait，这个 trait 提供了队列相关的功能
    use Queueable;

    // 构造函数，接收一个 Mailable 对象，并将其存储在 $mailable 属性中
    public function __construct(protected Mailable $mailable) {}

    // handle 方法，是队列任务执行时的入口
    public function handle(MailService $mailer)
    {
        // 调用 MailService 的 send 方法，传入 $mailable 对象，发送邮件
        $mailer->send($this->mailable);
    }
}