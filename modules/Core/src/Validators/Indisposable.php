<?php

namespace Modules\Core\Validators;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Utils;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use stdClass;

class Indisposable
{
    /**
     * Indisposable service name.
     *
     * @var string
     */
    protected string $service;

    /**
     * Indisposable service enable status.
     *
     * @var bool
     */
    protected bool $enabled;

    /**
     * base service url.
     *
     * @var string
     */
    protected string $baseUrl;

    /**
     * service Api Key.
     *
     * @var string
     */
    protected string $apiKey;

    /**
     * The Client object used for requests
     *
     * @var Client
     */
    protected Client $client;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->service = config('services.indisposable.default');

        $this->enabled = config('services.indisposable.enabled');

        $connections = config('services.indisposable.connections');

        $this->baseUrl = rtrim($connections[$this->service]['domain'], '/\\').'/';

        $this->apiKey = $connections[$this->service]['key'] ?? '';

        $this->initializeClient();
    }

    /**
     * Validates whether an email address does not originate from a disposable email service.
     * The response is saved to prevent unnecessary checks.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @param Validator $validator
     * @return bool
     * @throws GuzzleException
     */
    public function validate(string $attribute, mixed $value, array $parameters, Validator $validator): bool
    {
        if ($this->enabled) {
            $cacheKey = $this->service.'_email_validator_'.$value;

            return cache()->remember($cacheKey, now()->addMinutes(10), function () use ($value) {
                return $this->isRealEmail($value);
            });
        }

        return true;
    }

    /**
     * Initialize the GuzzleHttp/Client instance
     *
     * @return Client $client
     */
    protected function initializeClient(): Client
    {
        if (isset($this->client)) {
            return $this->client;
        }

        $options = [
            'base_uri' => $this->baseUrl,
            'verify' => false,
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8',
                'Accept' => 'application/json',
                'X-Api-Key' => $this->service === 'block-temporary-email' ? $this->apiKey : '',
            ],
        ];

        $this->client = new Client($options);

        return $this->client;
    }

    /**
     * Initialize the GuzzleHttp/Client instance
     *
     * @param  string  $email
     * @return bool
     *
     * @throws GuzzleException
     */
    protected function isRealEmail(string $email): bool
    {
        if (false === $email = filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $domain = Str::after($email, '@');

        try {
            $response = $this->client->get($domain);
        } catch (RequestException $e) {
            // because unknown values we do not need to process
            return true;
        }

        try {
            $responseBody = Utils::jsonDecode($response->getBody()->getContents());
        } catch (Exception $e) {
            return true;
        }

        return ! $this->isDisposable($responseBody);
    }

    /**
     * Initialize the GuzzleHttp/Client instance
     *
     * @param  mixed  $response
     * @return bool
     */
    protected function isDisposable(mixed $response): bool
    {
        if ($this->service === 'open.kickbox') {
            return (bool) $response->disposable;
        } elseif ($this->service === 'validator.pizza') {
            // return (bool) $response['disposable'];

            $res = $this->formatResponseVP($response);
            // Return true if the email address' domain has a valid MX entry in DNS
            return match (true) {
                $res->status == 400, $res->disposable == true, $res->mx == false => true,
                default => false,
            };
        } elseif ($this->service === 'block-temporary-email') {
            // return (bool) $response['temporary'];
            $res = $this->formatResponseBTE($response);

            return match (true) {
                $res->status == 400, $res->disposable == true, $res->mx == false => true,
                default => false,
            };
        }

        return false;
    }

    /**
     * Query the API Validator Pica.
     *
     * @param  mixed  $data
     * @return stdClass API response data
     */
    private function formatResponseVP(mixed $data): stdClass
    {
        $object = new stdClass();
        $object->status = optional($data)->status ?? 400;
        $object->mx = optional($data)->mx ?? false;
        $object->disposable = optional($data)->disposable ?? false;

        return $object;
    }

    /**
     * Query the API Block Temporary Emails.
     *
     * @param  mixed  $data
     * @return stdClass API response data
     */
    private function formatResponseBTE(mixed $data): stdClass
    {
        $object = new stdClass();
        $object->status = optional($data)->status ?? 400;
        $object->mx = optional($data)->dns ?? false;
        $object->disposable = optional($data)->temporary ?? false;

        return $object;
    }
}
