<?php

namespace App\Mail;

use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Http\Request;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    protected $request; 
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->request->session()->get('mail_content');
        return $this->view('/emails/purchase' , compact('data'))
                    ->subject('お買い上げありがとうございます');
    }
}
