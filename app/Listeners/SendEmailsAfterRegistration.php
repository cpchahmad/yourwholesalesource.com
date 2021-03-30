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
            'tags' => [
                0 => 'Our Memebers',
            ],
            'identifiers' => [
                0 => [
                    'type' => 'email',
                    'id' => 'austin.powers@example.com',
                    'channels' => [
                        'email' => [
                            'status' => 'subscribed',
                            'statusDate' => '2016-02-29T10:07:28Z',
                        ],
                    ],
                ],
                1 => [
                    'type' => 'phone',
                    'id' => '+443031237300',
                    'channels' => [
                        'sms' => [
                            'status' => 'nonSubscribed',
                            'statusDate' => '2016-02-29T10:07:28Z',
                        ],
                    ],
                ],
            ],
            'country' => 'United Kingdom',
            'countryCode' => 'GB',
            'state' => '',
            'city' => 'London',
            'address' => 'Westminster',
            'postalCode' => 'SW1A 1AA',
            'gender' => 'f',
            'birthdate' => '1997-05-02',
            'customProperties' => [
                'age' => 33,
                'hair_color' => 'brown',
                'married' => true,
                'marriageDate' => '2018-07-07',
                'loyaltyPoints' => 125.8,
            ],
        ];

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.omnisend.com/v3/contacts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"createdAt\":\"2016-05-02T09:19:19Z\",\"firstName\":\"Vanessa\",\"lastName\":\"Kensington\",\"tags\":[\"designer\",\"leader\",\"source: shopify\"],\"identifiers\":[{\"type\":\"email\",\"id\":\"austin.powers@example.com\",\"channels\":{\"email\":{\"status\":\"subscribed\",\"statusDate\":\"2016-02-29T10:07:28Z\"}}},{\"type\":\"phone\",\"id\":\"+443031237300\",\"channels\":{\"sms\":{\"status\":\"nonSubscribed\",\"statusDate\":\"2016-02-29T10:07:28Z\"}}}],\"country\":\"United Kingdom\",\"countryCode\":\"GB\",\"state\":\"\",\"city\":\"London\",\"address\":\"Westminster\",\"postalCode\":\"SW1A 1AA\",\"gender\":\"f\",\"birthdate\":\"1997-05-02\",\"customProperties\":{\"age\":33,\"hair_color\":\"brown\",\"married\":true,\"marriageDate\":\"2018-07-07\",\"loyaltyPoints\":125.8}}",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
                "x-api-key: 606318754c7fa4545433398e-On0aNj7aqEIeOjWkFv3uUrvP6SNRlw6vWmtL6pdbsghhzFPZjq"
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
