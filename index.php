<?php
session_start();

function dump($data)
{
    highlight_string("<?php\n " . var_export($data, true) . '?>');
    echo '<script>document.getElementsByTagName("code")[0].getElementsByTagName("span")[1].remove() ;document.getElementsByTagName("code")[0].getElementsByTagName("span")[document.getElementsByTagName("code")[0].getElementsByTagName("span").length - 1].remove() ; </script>';
}

function dd($data)
{
    dump($data);
    die();
}

use Coderatio\PaystackMirror\Actions\Charges\CheckPendingCharge;
use Coderatio\PaystackMirror\Actions\Transactions\ListTransactions;
use Coderatio\PaystackMirror\Exceptions\PaystackMirrorException;
use Coderatio\PaystackMirror\PaystackMirror;
use Coderatio\PaystackMirror\Services\ParamsBuilder;

require 'vendor/autoload.php';

$valuebeamKey = 'sk_test_995ba53eb1c211b36a1d24c209ffc3ae85d9075e';
$cloudinosKey = 'sk_test_adcfc4431e1d04b89109629793022cf8aa66c784';

$ref = '338635ee18f8b0f626818a216642c9a4';
$authCode = 'AUTH_xspx0c4586';
$authUrl = 'https://checkout.paystack.com/wmfjt8b6pn329dj';

$data = [
    'phone' => '2348131600400',
    'verification_type' => 'truecaller',
    'callback_url' => 'http://paystack-mirror.test/phone.php'
];

$action = new CheckPendingCharge('hs2l567y1h6eeqh');
//7507095 p-PLN_rfnmk041cfs3xhd AUTH_yjq7mv0850 sub-token ir23da098nlwnqn
// PageID-85637, pSlug:m92o9x2pa- CUS_rpdaqx6539brtg0
$key2 = '';
$param = new ParamsBuilder();
$param->status = 'success';

try {
   echo short_naira_to_kobo('1.5m');

} catch (PaystackMirrorException $e) {

}


