<?php

namespace App\Listeners;

use App\ErrorLog;
use App\Mail\NewUser;
use App\Mail\NewWallet;
use App\Mail\ResourcesMail;
use App\Mail\StoreIntegrationMail;
use App\Mail\TopShopifyProuctMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailsAfterRegistration
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;

//        try{
//            Mail::to($user->email)->send(new NewUser($user));
//            Mail::to($user->email)->send(new NewWallet($user));
//            Mail::to($user->email)->send(new TopShopifyProuctMail($user));
//            Mail::to($user->email)->send(new StoreIntegrationMail());
//            Mail::to($user->email)->send(new ResourcesMail());
//        }
//        catch (\Exception $e){
//            $log = new ErrorLog();
//            $log->message = $e->getMessage();
//            $log->save();
//        }

        // Sync To Omnisend Members Contact List
        $curl = curl_init();

        $data = [
            'createdAt' => $user->created_at,
            'firstName' => $user->name,
            'sendWelcomeMessage' => true,
            'tags' => [
                0 => 'Our Members',
            ],
            'identifiers' => [
                0 => [
                    'type' => 'email',
                    'id' => $user->email,
                    'channels' => [
                        'email' => [
                            'status' => 'subscribed',
                            'statusDate' => $user->created_at,
                        ]
                    ]
                ]
            ]
        ];


        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.omnisend.com/v3/contacts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
                "x-api-key: ". env('OMNISEND_API_KEY')
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }

    }
}
