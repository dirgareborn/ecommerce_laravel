<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class AutoCompleteOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:auto-complete';
    


    /**
     * The console command description.
     *
     * @var string
     */
  protected $description = 'Otomatis ubah status approved menjadi completed jika tanggal lewat';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        $updated = Order::where('order_status', 'approved')
            ->whereDate('end_date', '<', $today)
            ->update(['order_status' => 'completed']);

        $this->info("Updated {$updated} orders to completed.");
    }
}
