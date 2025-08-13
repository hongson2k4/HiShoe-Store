<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $cartItems;
    public $payment;

    public function __construct($order, $cartItems, $payment)
    {
        $this->order = $order;
        $this->cartItems = $cartItems;
        $this->payment = $payment;
    }

    public function build()
    {
        return $this->subject('Đặt hàng thành công tại HiShoe Store')
            ->view('emails.order_success')
            ->with([
                'order' => $this->order,
                'cartItems' => $this->cartItems,
                'payment' => $this->payment,
            ]);
    }
}