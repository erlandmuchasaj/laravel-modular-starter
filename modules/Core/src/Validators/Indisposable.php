<?php

namespace Modules\Core\Validators;

use GuzzleHttp\Client;
use GuzzleHttp\Utils;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use stdClass;

class Indisposable
{

    /**
     * Indisposable service.
     * @var string
     */
    protected mixed $service;

    /**
     * base service url.
     * @var string
     */
    protected string $baseUrl;

    /**
     * The Client object used for requests
     * @var Client $client
     */
    protected Client $client;

    /**
     * __construct
     *
     */
    public function __construct(){

        $this->service = config('app.indisposable_service');

        $services = config('app.indisposable');

        $this->baseUrl = rtrim($services[$this->service]['domain'], '/\\') . '/';

        $this->initializeClient();
    }

    /**
     * Validates whether an email address does not originate from a disposable email service.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @param Validator $validator
     * @return bool
     * @throws GuzzleException
     */
    public function validate(string $attribute, mixed $value, array $parameters,Validator  $validator): bool
    {
        return $this->isRealEmail($value);
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
            'verify'   => false,
            'headers'  => [
                'Content-Type' => 'application/json; charset=utf-8',
                'Accept' => 'application/json',
            ],
        ];

        $this->client = new Client($options);
        return $this->client;
    }

    /**
     * Initialize the GuzzleHttp/Client instance
     * @param string $email
     * @return bool
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

        return !$this->isDisposable($responseBody);
    }


    /**
     * Initialize the GuzzleHttp/Client instance
     * @param mixed $response
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
     * @param mixed $data
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
     * @param mixed $data
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
