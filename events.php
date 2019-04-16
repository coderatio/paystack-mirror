<?php


require_once 'vendor/autoload.php';
$sign = hash_hmac('sha512', $_POST['contents'], 'sk_test_995ba53eb1c211b36a1d24c209ffc3ae85d9075e');
header('HTTP_X_PAYSTACK_SIGNATURE: ' . $sign);
header('X-Paystack-Signature: ' . $sign);
header('Content-Type: application/json');
echo @file_get_contents('php://input');
echo json_encode($_POST);