<?php

namespace App\Mail;

use App\Models\Club;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClubJoinRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Club $club;
    public ?string $messageText;

    public function __construct(User $user, Club $club, ?string $messageText = null)
    {
        $this->user = $user;
        $this->club = $club;
        $this->messageText = $messageText;
    }

    public function build(): self
    {
        return $this->subject('New club join request')
            ->view('emails.club_join_request')
            ->with([
                'user' => $this->user,
                'club' => $this->club,
                'messageText' => $this->messageText,
            ]);
    }
}
