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

        try{
            Mail::to($user->email)->send(new NewUser($user));
            Mail::to($user->email)->send(new NewWallet($user));
            Mail::to($user->email)->send(new TopShopifyProuctMail($user));
            Mail::to($user->email)->send(new StoreIntegrationMail());
            Mail::to($user->email)->send(new ResourcesMail());
        }
        catch (\Exception $e){
            $log = new ErrorLog();
            $log->message = $e->getMessage();
            $log->save();
        }

        // Sync To SendGrid WefullFill Members Contact List
        $contacts = [];
        array_push($contacts, [
            'email' => $user->email,
            'first_name' => $user->name,
        ]);
        $contacts_payload = [
            'list_ids' => ["33d743f3-a906-4512-83cd-001f7ba5ab33"],
            'contacts' => $contacts
        ];
        $payload = json_encode($contacts_payload);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/contacts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer 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",
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
    }
}
