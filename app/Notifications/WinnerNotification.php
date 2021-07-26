<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WinnerNotification extends Notification
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
            ->line('You just one the big prize for lottery ' . $this->lotteryDraw->lottery->name . '!!')
            ->line('Winning numbers: [' . $this->luckyNumbersString . ']')
            ->line('The amount of ' . $this->lotteryDraw->lottery->prize . ' has been added to your account!')
            ->line('Thank you for using our lottery app!');
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
            'name' => 'You won!',
            'message' => 'You just one the big prize for lottery ' . $this->lotteryDraw->lottery->name . '!!\n' .
                'Winning numbers: [' . $this->luckyNumbersString . ']' .
                '\nThe amount of ' . $this->lotteryDraw->lottery->prize . ' has been added to your account!\n' .
                'Thank you for using our lottery app!'
        ];
    }
}
