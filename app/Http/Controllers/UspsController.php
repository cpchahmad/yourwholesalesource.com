<?php
namespace App\Http\Controllers;
use App\RetailerOrder;
use App\ShippingQuantity;
use Usps;
use Illuminate\Http\Request;
class UspsController extends Controller
{
    public function validate_address($postal_code, $province_code, $city, $address, $appartment = null)
    {
        $user_id = env('USPS_USER_ID');
        try {
            $request_doc_template = <<<EOT
            <?xml version="1.0" ?>
            <AddressValidateRequest USERID="{$user_id}">
            <Revision>1</Revision>
            <Address ID="0">
            <Address1>{$address}</Address1>
            <Address2/>
            <City>{$city}</City>
            <State>{$province_code}</State>
            <Zip5>{$postal_code}</Zip5>
            <Zip4/>
            </Address>
            </AddressValidateRequest>
            EOT;
            //prepare xml doc for query string;
            $doc_string = preg_replace('/[\t\n]/', '', $request_doc_template);
            $doc_string = urlencode($doc_string);
            $url = "https://secure.shippingapis.com/ShippingAPI.dll?API=Verify&XML=" . $doc_string;
            //echo $url.'\n\n';
            $response = file_get_contents($url);
            $xml = simplexml_load_string($response) or die("Cannot create Object");
            return $xml->Address;
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
            return null;
        }
    }


    public function shipping_rates($cartItem = null, $postal_code = null, $city = null, $address = null, $company = null)
    {
        $order = RetailerOrder::latest()->first();

        $user_id = env('USPS_USER_ID');
        try {
            $origin_zip = env('USPS_ORIGIN_ZIP');

            $request_doc_template = <<<EOT
                <?xml version="1.0" ?>
                <RateV4Request USERID="{$user_id}">
                <Revision>1</Revision>
            EOT;

            foreach ($order->line_items as $item ) {
                $request_doc_template.=<<<EOT
                            <Package ID="{$item->id}">
                            <Service>PRIORITY</Service>
                            <ZipOrigination>{$origin_zip}</ZipOrigination>
                            <ZipDestination>{$order->postal_code}</ZipDestination>
                            <Pounds>{($item->weight * $item->quantity)}</Pounds>
                            <Ounces>{$item->ounce_weight}</Ounces>
                            <Container></Container>
                            <Machinable>FALSE</Machinable>
                            </Package>
EOT;
            }
            $request_doc_template.=<<<EOT
</RateV4Request>
EOT;
            //prepare xml doc for query string;
            //dd($request_doc_template);
            $doc_string = preg_replace('/[\t\n]/', '', $request_doc_template);
            $doc_string = urlencode($doc_string);
            $url = "https://secure.shippingapis.com/ShippingAPI.dll?API=RateV4&XML=" . $doc_string;
            //echo $url.'\n\n';
            $response = file_get_contents($url);
            $xml = simplexml_load_string($response) or die("Cannot create Object");
            return $xml;
        } catch (\Exception $e) {
            dd($e);
            flash($e->getMessage())->error();
            return null;
        }
    }
}
