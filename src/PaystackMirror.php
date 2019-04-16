<?php


namespace Coderatio\PaystackMirror;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Exceptions\PaystackMirrorException;
use Coderatio\PaystackMirror\Services\CurlHttpResponseService;
use Coderatio\PaystackMirror\Services\ParamsBuilder;
use Coderatio\PaystackMirror\Services\RequestHandlerService;
use DateTime;
use Exception;

class PaystackMirror
{
    /**
     * Holds the response from an action
     * @var $response
     */
    protected static $response;

    /**
     * Holds Paystack secret key
     * @var $secretKey
     */
    protected static $secretKey;

    /**
     * Holds details for multiple Paystack account mirroring
     * @var array $accountsDetails
     */
    protected static $accountsDetails = [];

    /**
     * PaystackMirror constructor.
     * @param null|string $secretKey
     */
    public function __construct($secretKey = null)
    {
        if ($secretKey !== null) {
            static::$secretKey = $secretKey;
        }
    }

    /**
     * Start mirrowing. Paystack account secret key must be provided.
     *
     * @param $secretKey
     * @return PaystackMirror
     */
    public static function boot($secretKey): PaystackMirror
    {
        static::$secretKey = $secretKey;

        return new self();
    }

    /**
     * Mirror any paystack api action. Paystack account secret key
     * and action to mirror must be provided.
     *
     * @param $secretKey
     * @param mixed $action
     * @param array $data
     * @return PaystackMirror
     * @throws PaystackMirrorException
     */
    public static function run($secretKey, $action, $data = []): PaystackMirror
    {
        static::$secretKey = $secretKey;

        static::abortIfNotValidAction($action);

        static::$response = RequestHandlerService::handle(
            $secretKey, static::parsedAction($action, $data)
        );

        return new self();
    }

    /**
     * Run Paystack Mirror on multiple accounts
     *
     * @param array $accountsDetails
     * @param $action
     * @param array $parentData
     * @return array
     */
    public static function runMultipleAccounts(array $accountsDetails, $action, $parentData = []): array
    {
        if ($accountsDetails instanceof ParamsBuilder) {
            $accountsDetails = $accountsDetails->get();
        }

        return array_map(function ($accountDetails) use ($action, $parentData) {
            if ($accountDetails instanceof ParamsBuilder) {
                $accountDetails = $accountDetails->get();
            }

            if (isset($accountDetails['key']) || isset($accountDetails['secret'])) {

                $data = $accountDetails['data'] ?? [];

                if (!empty($data) && $data instanceof ParamsBuilder) {
                    $data = $data->get();
                }

                $key = $accountDetails['key'] ?? $accountDetails['secret'] ?? '';

                if (!empty($parentData)) {
                    if ($parentData instanceof ParamsBuilder) {
                        $parentData = $parentData->get();
                    }

                    $data = $parentData;
                }

                $request = static::run($key, static::parsedAction($action, $data), $data);

                return (object)[
                    'account' => $key,
                    'response' => $request->getResponse()
                ];
            }

            return (object)[];

        }, $accountsDetails);
    }

    /**
     * Set Paystack secret key
     *
     * @param $secretKey
     * @return PaystackMirror
     */
    public static function setKey($secretKey): PaystackMirror
    {
        static::$secretKey = $secretKey;

        return new self();
    }

    /**
     * Set multiple Paystack accounts secret keys
     *
     * @param $accountsDetails
     * @return PaystackMirror
     */
    public static function setAccounts($accountsDetails): PaystackMirror
    {
        static::$accountsDetails = $accountsDetails;

        return new self();
    }

    /**
     * This function works as the run method but takes
     * only an action to mirror.
     *
     * @param mixed $action
     * @param array $data
     * @return PaystackMirror
     * @throws PaystackMirrorException
     */
    public function mirror($action, $data = []): PaystackMirror
    {
        return static::run(static::$secretKey, $action, $data);
    }

    /**
     * Mirror Paystack mirror on multiple accounts.
     *
     * @param $action
     * @param array $data
     * @return array
     */
    public function mirrorMultipleAccountsOn($action, $data = []): array
    {
        return static::runMultipleAccounts(static::$accountsDetails, $action, $data);
    }

    /**
     * This function returns the response from an action
     *
     * @return CurlHttpResponseService
     */
    public function getResponse() : CurlHttpResponseService
    {
        return static::$response;
    }

    /**
     * Converts Nigerian Naira to Kobo Coin
     *
     * @param mixed $nairaAmount
     * @return int
     */
    public static function nairaToKobo($nairaAmount): int
    {
        return static::getCleanedAmount($nairaAmount) * 100;

    }

    /**
     * Converts Nigerian Kobo Coin to Naira
     *
     * @param mixed $koboAmount
     * @return int
     */
    public static function koboToNaira($koboAmount): int
    {
        return static::getCleanedAmount($koboAmount) / 100;
    }

    /**
     * Generates a random reference.
     *
     * @return string|null
     */
    public static function generateReference(): ?string
    {
        return mb_strtolower('PM_' . crypt(str_shuffle('PAYSTACK_MIRROR_REF'), 'rf'));
    }

    /**
     * Creates a simple ISO 8601 dates.
     *
     * @param string $date
     * @return string
     * @throws Exception
     */
    public static function createISO8601Date($date = ''): string
    {
        if (empty($date)) {
            $date = date('Y-m-d h:i:s');
        }

        return (new DateTime($date))->format(DateTime::ATOM);
    }

    /**
     * @param $shortNaira
     * @return float|int
     * @throws PaystackMirrorException
     */
    public static function shortNairaToKobo($shortNaira)
    {
        $moneyNotations = [
            'k' => 100000,
            'm' => 100000000,
            'b' => 100000000000,
            't' => 100000000000000,
        ];

        foreach($moneyNotations as $shortNotation => $conversionRate) {
            $shortNaira = str_replace([',', '/', '|', ':', ';', ' '], '', trim($shortNaira));
            $explodedData = explode($shortNotation, mb_strtolower($shortNaira));

            if (isset($explodedData[1])) {
                return ($conversionRate * $explodedData[0]);
            }
        }

        throw new PaystackMirrorException('Invalid format provided: You should enter something like 1K or 1M or 1B or 1T.');
    }

    /**
     * Convert action to object if $action::class used and
     * pass in data provided.
     *
     * @param $action
     * @param $data
     * @return Action $action
     */
    protected static function parsedAction($action, $data): Action
    {
        if ($data instanceof ParamsBuilder) {
            $data = $data->get();
        }

        if (is_string($action)) {
            $action = new $action($data);
        }

        if (!empty($data)) {
            new $action($data);
        }

        return $action;
    }

    /**
     * Stop execution if the action is not valid
     *
     * @param $action
     * @return PaystackMirror
     * @throws PaystackMirrorException
     */
    protected static function abortIfNotValidAction($action): PaystackMirror
    {
        if (!(new $action() ?? !is_string($action)) instanceof Action) {
            $actionClassName = get_class($action);

            throw new PaystackMirrorException(
                "[{$actionClassName}], must be an instance of  " . Action::class
            );
        }

        return new self();
    }

    private static function getCleanedAmount($amount)
    {
        return str_replace([',', '.', '/', '|', ':', ';', ' '], '', trim($amount));
    }
}
