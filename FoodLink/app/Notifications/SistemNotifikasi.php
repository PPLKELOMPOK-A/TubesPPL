<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SistemNotifikasi extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $category;
    protected $details; // <-- Wajib ada variabel ini

    // Pastikan di dalam kurung ini ada 4 parameter, dan $details diberi default array kosong []
    public function __construct($title, $message, $category, $details = [])
    {
        $this->title = $title;
        $this->message = $message;
        $this->category = $category;
        $this->details = $details; // <-- Menyimpan data array dari controller
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        // Menyusun format yang akan masuk ke tabel 'notifications' kolom 'data'
        return [
            'title' => $this->title,
            'message' => $this->message,
            'category' => strtolower($this->category),
            'details' => $this->details // <-- Memasukkan detail ke database
        ];
    }
}