<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contactMessage;
    public $replyContent;

    /**
     * Create a new message instance.
     *
     * @param ContactMessage $contactMessage
     * @param string $replyContent
     */
    public function __construct(ContactMessage $contactMessage, string $replyContent)
    {
        $this->contactMessage = $contactMessage;
        $this->replyContent = $replyContent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Réponse à votre message de contact')
                    ->view('emails.admin_reply')
                    ->with([
                        'contactMessage' => $this->contactMessage,
                        'replyContent' => $this->replyContent,
                    ]);
    }
}
