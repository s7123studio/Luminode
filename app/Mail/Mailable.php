<?php

namespace App\Mail;

use Luminode\Queue\Dispatchable;
use Luminode\View\ViewFactory;

abstract class Mailable
{
    use Dispatchable;

    protected $to = [];
    protected $subject = '';
    protected $view = '';
    protected $data = [];

    public function to($address, $name = null)
    {
        $this->to[] = compact('address', 'name');
        return $this;
    }

    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function view($view, array $data = [])
    {
        $this->view = $view;
        $this->data = $data;
        return $this;
    }

    public function build(ViewFactory $views)
    {
        return $views->make($this->view, $this->data)
            ->with('subject', $this->subject);
    }
}