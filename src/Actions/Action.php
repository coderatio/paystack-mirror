<?php
namespace Coderatio\PaystackMirror\Actions;


use Coderatio\PaystackMirror\Services\CurlService;
use Coderatio\PaystackMirror\Services\ParamsBuilder;

abstract class Action
{
    /**
     * The end-point for this action
     * @var string
     */
    protected $url = 'https://api.paystack.co/';

    /**
     * The http request type for this action
     * @var string
     */
    protected $requestType = 'post';

    /**
     * The query or body params fot this action
     * @var array
     */
    protected $data = [];

    /**
     * Action constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->data = $data;
        }

        if ($data instanceof ParamsBuilder) {
            $this->data = $this->data->get();
        }
    }

    /**
     * Set a new end-point for this action
     *
     * @param $url
     * @return $this
     */
    public function setUrl($url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Set the query or body params to be sent to paystack on this action
     *
     * @param iterable $data
     * @return $this
     */
    public function setData(iterable $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Set the type of http request used for this action
     *
     * @param string $type
     * @return $this
     */
    public function setRequestType(string $type): self
    {
        $this->requestType = $type;

        return $this;
    }

    /**
     * Get the type of http request used on this action
     *
     * @return string
     */
    public function getRequestType(): string
    {
        return $this->requestType;
    }

    /**
     * Get the action query or body params
     *
     * @return false|string
     */
    public function getData()
    {
        return json_encode($this->data);
    }

    /**
     * Get the action endpoint
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Append to query params
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function set($key, $value): self
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Prepare action execution.
     *
     * @param CurlService $curlService
     * @return void
     */
    abstract public function handle(CurlService $curlService) : void;
}
