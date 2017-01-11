<?php

namespace App\Http\Traits;

trait EmailTrait {
    protected $user;
    protected $message;

    public function sendEmail($user, $message) {
      $job = new SendEmail($user, $message);
      $this->dispatch($job);

      return true;
    }
}
