<?php

namespace App\Jobs;

use App\Campaign;
use App\Mail\NewsEmail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewsEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $campaign;
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        $users_temp = User::role('non-shopify-users')
//        ->whereNotIn('email', ['admin@wefullfill.com', 'super_admin@wefullfill.com'])
//        ->pluck('email')
//        ->toArray();

        $users_temp = User::find(2);


//        foreach ($users_temp as $user) {
            try{
                Mail::to($users_temp->email)->send(new NewsEmail());
                $users_temp->attach($this->campaign->id);
            }
            catch (\Exception $e){
            }
//        }

        $this->campaign->status = 'Completed';
        $this->campaign->save();
    }
}
