<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    protected $typeSend;

    protected $url;

    /**
     * Create a new job instance.
     */
    public function __construct($user,$typeSend,$url)
    {
        $this->user = $user;
        $this->typeSend = $typeSend;
        $this->url = $url;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->user;
        if ($this->typeSend === 'verifyAccount') {
            $urlVerifyAccount = $this->url;
            Mail::send('verifyEmail',compact('user','urlVerifyAccount'),function ($message) use($user){
                $message->subject(@trans('message.verifyEmail'));
                $message->to($user->mail);
            });
        }
        else if ($this->typeSend === 'forgotPassword') {
            $urlResetPassword = $this->url;
            Mail::send('resetPasswordMessage', compact('user','urlResetPassword') , function ($message) use($user) {
                $message->subject(@trans('message.forgotPassword'));
                $message->to($user->mail);
            });
        }
    }
}
