<?php

namespace App\Jobs;

use App\User;
use App\Jobs\Job;
use Illuminate\Support\Facades\View;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $message)
    {
        $this->user = $user;
        $this->message = $message;
        $this->smtpAddress = env('MAIL_HOST');
        $this->port = env('MAIL_PORT');
        $this->encryption = env('MAIL_ENCRYPTION');
        $this->Email = env('MAIL_USERNAME');
        $this->Password = env('MAIL_PASSWORD');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $transport = \Swift_SmtpTransport::newInstance($this->smtpAddress, $this->port, $this->encryption)
      ->setUsername($this->Email)
      ->setPassword($this->Password);
      $mailer = \Swift_Mailer::newInstance($transport);

      $view = View::make('emails.default', [
          'message' => $this->message,
      ]);

      $html = $view->render();

      $message = \Swift_Message::newInstance('Test')
     ->setFrom([$this->Email => env('MAIL_DEFAULT_NAME')])
     ->setTo([$this->user => $this->user])
     ->setBody($html, 'text/html');

      if($mailer->send($message)){
          return "Email to: ".$this->user." was succesfully sent!";
      }

      return "Something went wrong! Check your email settings";
    }
}
