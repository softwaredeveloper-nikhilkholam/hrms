<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    protected $subjectLine;
    protected $ccList;

    public function __construct($data, $subjectLine, $ccList = [])
    {
        $this->data = $data;
        $this->subjectLine = $subjectLine;
        $this->ccList = $ccList;
    }

    public function build()
    {
        return $this->view('emails.resignationEmail')
        ->with('data', $this->data)
        ->subject($this->subjectLine)
        ->cc($this->ccList);
    }
}



