<?php
namespace App\Http\Controllers;
use App\RetailerOrder;
use App\ShippingQuantity;
use Usps;
use Illuminate\Http\Request;
class UspsController extends Controller
{
    public function getShippingInfo($order) {
        $address = $this->validate_address($order);

        if(!(isset($address->SimpleXMLElement->Error))) {
            dd(23, $address);
            $shipping = $this->shipping_rates($order);

            if($shipping != null)
                return $shipping;
            else
                return null;

        }
        else {
            dd(564);
        }
    }

    public function validate_address($order)
    {
        $shipping_address = json_decode($order->shipping_address);
        $user_id = env('USPS_USER_ID');
        try {
            $request_doc_template = <<<EOT
            <?xml version="1.0" ?>
            <AddressValidateRequest USERID="{$user_id}">
            <Revision>1</Revision>
            <Address ID="0">
            <Address1>{$shipping_address->address1}</Address1>
            <Address2/>
            <City>{$shipping_address->city}</City>
            <State>{$shipping_address->province_code}</State>
            <Zip5>{$shipping_address->zip}</Zip5>
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
            return null;
        }
    }


    public function shipping_rates($order)
    {

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
                    <Pounds>{$item->weight}</Pounds>
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

//            dd($xml);
            return $xml;
        } catch (\Exception $e) {
//            dd($e);
            return null;
        }
    }
}
