<?php

use Coderatio\PaystackMirror\PaystackMirror;


if (!function_exists('paystack_mirror'))
{
    function paystack_mirror() {
        return new PaystackMirror();
    }
}

if (!function_exists('naira_to_kobo'))
{
    function naira_to_kobo($nairaAmount) {
        return PaystackMirror::nairaToKobo($nairaAmount);
    }
}

if (!function_exists('kobo_to_naira'))
{
    function kobo_to_naira($koboAmount) {
        return PaystackMirror::koboToNaira($koboAmount);
    }
}

if (!function_exists('short_naira_to_kobo'))
{
    function short_naira_to_kobo($shortNairaAmount) {
        return PaystackMirror::shortNairaToKobo($shortNairaAmount);
    }
}

if (!function_exists('paystack_mirror_dump'))
{
    function paystack_mirror_dump($data){
        highlight_string("<?php\n " . var_export($data, true) . '?>');
        echo '<script>document.getElementsByTagName("code")[0].getElementsByTagName("span")[1].remove() ;document.getElementsByTagName("code")[0].getElementsByTagName("span")[document.getElementsByTagName("code")[0].getElementsByTagName("span").length - 1].remove() ; </script>';
    }
}

if (!function_exists('paystack_mirror_dd'))
{
    function paystack_mirror_dd($data) {
        paystack_mirror_dump($data);
        die();
    }
}