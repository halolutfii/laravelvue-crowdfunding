<?php

namespace App\Listeners;

use App\Events\GenerateOTPCodeEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;
use App\Mail\GenerateOTPMail;

class SendMailOTPCodeListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\GenerateOTPCodeEvent  $event
     * @return void
     */
    public function handle(GenerateOTPCodeEvent $event)
    {
        Mail::to($event->user)->send(new GenerateOTPMail($event->user));
    }
}
