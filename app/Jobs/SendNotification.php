<?php

namespace App\Jobs;

use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $data = $this->data;
            foreach ($data['history_rent_book'] as &$item) {
                $rentDate = Carbon::createFromFormat('Y-m-d', $item['rent_date']);
                $expirationDate = Carbon::createFromFormat('Y-m-d', $item['expiration_date']);
                $numberDayRent = $expirationDate->diffInDays($rentDate);
                foreach ($item['detail_history_rent_book'] as &$value) {
                    $value['price'] =$numberDayRent*$value['quantity']*$value['book']['price_rent'];
                }
            }
            Mail::send('messageNotificationReturnBook', compact('data') , function ($message) use($data) {
                $message->subject('Bạn có lịch trả sách');
                $message->to($data['mail']);
            });
        }catch (\Exception $e) {
            Log::error($e);
        }
    }
}
