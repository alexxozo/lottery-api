<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class LoserNotification extends Notification
{
    use Queueable;

    private $lotteryDraw;
    private $luckyNumbersString;

    /**
     * Create a new notification instance.
     *
     * @param $lotteryDraw
     */
    public function __construct($lotteryDraw)
    {
        $this->lotteryDraw = $lotteryDraw;
        $winningNumbers = $this->lotteryDraw->numbers->pluck('number')->toArray();
        $this->luckyNumbersString = implode(",", $winningNumbers);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Sorry, luck was not with you today for lottery ' . $this->lotteryDraw->lottery->name . '!!')
            ->line('Winning numbers: [' . $this->luckyNumbersString . ']')
            ->line('The amount of money you could have won is: ' . $this->lotteryDraw->lottery->prize . ' :(')
            ->line('Thank you for using our lottery app! Good luck next time!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'name' => 'No win this time!',
            'message' => 'You just lost the big prize for lottery ' . $this->lotteryDraw->lottery->name . '!!\n' .
                'Winning numbers: [' . $this->luckyNumbersString . ']' .
                '\nThe amount of ' . $this->lotteryDraw->lottery->prize . ' won\'t be added to your account!\n' .
                'Thank you for using our lottery app! Good luck next time!'
        ];
    }
}
