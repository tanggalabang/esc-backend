<?php

namespace App\Jobs;

use App\Mail\AddStudentEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProsessSendEmail implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $nis;
  public $name;
  public $email;
  public $password;

  /**
   * Create a job instance.
   */
  public function __construct($nis, $name, $email, $password)
  {
    $this->nis = $nis;
    $this->name = $name;
    $this->email = $email;
    $this->password = $password;
  }
  /**
   * Execute the job.
   */
  public function handle(): void
  {
    Mail::to($this->email)->send(new AddStudentEmail($this->nis, $this->name, $this->email, $this->password));
  }
}
