<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReimbursementSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $reimbursement;
    public $user;

    public function __construct($reimbursement, $user)
    {
        $this->reimbursement = $reimbursement;
        $this->user = $user;
    }

    public function build() 
    {
        return $this->subject('Pengajuan Reimbursement Baru')
                    ->markdown('emails.reimbursements.submitted');
    }
}
