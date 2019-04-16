#CHARGES
```php
Coderatio\PaystackMirror\Actions\Charges\...
```

| Action        | Description           | Request type  |
| ------------- |:-------------:| -----:|
| `TokenizePaymentInstrument`      | Send an array of objects with authorization codes and amount in kobo so paystack can process transactions as a batch. | `POST` |
| `InitializeCharge`      | Send card details or bank details or authorization code to start a charge. Simple guide to charging cards directly       |   `POST` |
| `SubmitPinToCharge` | Submit a pin to charge from      |    `POST` |
| `SubmitOtpToCharge` | Submit OTP to complete a charge     |    `POST` |
| `SubmitPhoneToCharge` | Submit Phone when requested      |    `POST` |
| `SubmitBirthdayToCharge` | Submit Birthday when requested      |    `POST` |
| `CheckPendingCharge` | When you get `pending` as a charge status, wait 30 seconds or more, then make a check to see if its status has changed. Don't call too early as you may get a lot more pending than you should.      |    `GET` |

[Read more here...](https://developers.paystack.co/v1.0/reference#charge)
___