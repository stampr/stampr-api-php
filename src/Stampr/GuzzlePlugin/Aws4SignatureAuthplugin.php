<?php

use Guzzle\Common\Event;
use Guzzle\Common\Version;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * AWS Signature Version 4, for stampr.  Portions pulled from: https://github.com/aws/aws-sdk-php/blob/master/src/Aws/Common/Signature/SignatureV4.php
 */
class Aws4SignatureAuthplugin implements EventSubscriberInterface
{
    private $key;
    private $secret;
    private $region;
    private $service;
    public $contentType = 'application/json';
    public $algorithm = 'sha256';
    public $signedHeaders = array('content-type', 'host', 'date');

    /**
     * @param string $key       HTTP basic auth username
     * @param string $secret    Password
     */
    public function __construct($key, $secret, $region, $service)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->region = $region;
        $this->service = $service;
    }

    public static function getSubscribedEvents()
    {
        return array('client.create_request' => array('onRequestCreate', 255));
    }

    /**
     * Add basic auth
     *
     * @param Event $event
     */
    public function onRequestCreate(Event $event)
    {
        $request = $event['request'];

        sort($this->signedHeaders);

        $timestamp = time();
        $longDate = gmdate('Ymd\THis\Z', $timestamp);
        $shortDate = substr($longDate, 0, 8);

        if (in_array('content-type', $this->signedHeaders) && !$request->hasHeader('content-type')) {
            $request->setHeader('content-type', $this->contentType);
        }

        $request->removeHeader('authorization');
        $request->setHeader('date', gmdate('D, d M Y H:i:s \G\M\T', $timestamp));

        $scope = "{$shortDate}/{$this->region}/{$this->service}/aws4_request";
        $credential = $this->key . '/' . $scope;       
        $canonical = $this->buildCanonicalString($request);

        $stringToSign = $this->buildStringToSign($longDate, $scope, $canonical);
        $signingKey = $this->buildSigningKey($shortDate);

        // string $algo , string $data , string $key [, bool $raw_output = false ]
        $signature = hash_hmac($this->algorithm, $stringToSign, $signingKey);
        $authHeaderValue = $this->buildAuthHeader($signature, $credential);

        $request->addHeader(
            'authorization',
            $authHeaderValue
        );
    }

    protected function buildAuthHeader($signature, $credential)
    {
        $signedHeaders = implode(';', $this->signedHeaders);
        // Authorization: AWS4-HMAC-SHA256 Credential=AKIDEXAMPLE/20110909/us-east-1/iam/aws4_request, SignedHeaders=content-type;host;x-amz-date, Signature=ced6826de92d2bdeed8f846f0bf508e8559e98e4b0199114b84c54174deb456c
        return $this->buildServiceLabel() . " Credential={$credential}, SignedHeaders={$signedHeaders}, Signature={$signature}";
    }

    protected function buildServiceLabel() 
    {
        return 'AWS4-HMAC-' . strtoupper($this->algorithm);
    }

    protected function buildStringToSign($longDate, $scope, $canonical) 
    {
        $StringToSign  =
            $this->buildServiceLabel() . "\n" .
            $longDate . "\n" .
            $scope . "\n" .
            hash($this->algorithm, $canonical);

        return $StringToSign;
    }

    protected function buildCanonicalString($request) 
    {
        $CanonicalHeaders = '';
        for ($i=0; $i < count($this->signedHeaders); $i++) 
        {
          $headerKey = $this->signedHeaders[$i];
          $v = $request->getHeader($headerKey);
          $CanonicalHeaders .= strtolower($headerKey) . ':' . trim($v) . "\n";
        }

        $signedHeaders = implode(';', $this->signedHeaders);

        $body = method_exists($request, 'getBody') ? $request->getBody() : ''; // GET doesn't _get_ a body. har har.

        $CanonicalRequest =
            $request->getMethod() . "\n" .
            $request->getPath() . "\n" .
            $request->getQuery(true) . "\n" .
            $CanonicalHeaders . "\n" .
            $signedHeaders . "\n" .
            hash($this->algorithm, $body || '');

        return $CanonicalRequest;
    }

    protected function buildSigningKey($shortDate) 
    {
        $dateKey = hash_hmac($this->algorithm, $shortDate, 'AWS4' . $this->secret,  true);
        $regionKey = hash_hmac($this->algorithm, $this->region, $dateKey, true);
        $serviceKey = hash_hmac($this->algorithm, $this->service, $regionKey, true);
        $signingKey = hash_hmac($this->algorithm, 'aws4_request', $serviceKey, true);

        return $signingKey;
    }
}
