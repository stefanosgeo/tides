<?php


namespace App\Mail;

use App\Models\Clip;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VideoUploaded extends Mailable
{

    use Queueable, SerializesModels;


    /**
     * Create a new message instance.
     *
     * @param  Clip  $clip
     */
    public function __construct(protected Clip $clip)
    {
    }

    /**
     * Sends an email message if a video is uploaded successfully.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->subject('Cool your video is now online')
            ->markdown('email.clips.video_is_uploaded', [
                'clip' => $this->clip,
            ]);
    }
}
