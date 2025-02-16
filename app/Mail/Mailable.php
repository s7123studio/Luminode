<?php

// 定义命名空间，表示这个类属于App\Mail
namespace App\Mail;

// 引入Luminode\Queue\Dispatchable和Luminode\View\ViewFactory两个类
use Luminode\Queue\Dispatchable;
use Luminode\View\ViewFactory;

// 定义一个抽象类Mailable，用于发送邮件
abstract class Mailable
{
    // 使用Dispatchable trait，这个trait可能包含队列相关的功能
    use Dispatchable;

    // 定义一个受保护的属性$to，用于存储收件人信息，默认为空数组
    protected $to = [];
    // 定义一个受保护的属性$subject，用于存储邮件主题，默认为空字符串
    protected $subject = '';
    // 定义一个受保护的属性$view，用于存储邮件视图模板，默认为空字符串
    protected $view = '';
    // 定义一个受保护的属性$data，用于存储邮件视图模板所需的数据，默认为空数组
    protected $data = [];

    // 定义一个公共方法to，用于添加收件人信息
    public function to($address, $name = null)
    {
        // 将收件人地址和姓名打包成一个数组，并添加到$to属性中
        $this->to[] = compact('address', 'name');
        // 返回当前对象，以便链式调用
        return $this;
    }

    // 定义一个公共方法subject，用于设置邮件主题
    public function subject($subject)
    {
        // 将传入的主题赋值给$subject属性
        $this->subject = $subject;
        // 返回当前对象，以便链式调用
        return $this;
    }

    // 定义一个公共方法view，用于设置邮件视图模板及其数据
    public function view($view, array $data = [])
    {
        // 将传入的视图模板赋值给$view属性
        $this->view = $view;
        // 将传入的数据赋值给$data属性
        $this->data = $data;
        // 返回当前对象，以便链式调用
        return $this;
    }

    // 定义一个公共方法build，用于构建邮件内容
    public function build(ViewFactory $views)
    {
        // 使用ViewFactory的make方法创建视图实例，传入视图模板和数据
        // 然后使用with方法向视图添加主题数据
        return $views->make($this->view, $this->data)
            ->with('subject', $this->subject);
    }
}