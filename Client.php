<?php

/*
 * This file is part of powerdns client.
 *
 * (c) 2018 cwd.at GmbH <office@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Cwd\PowerDNSClient;

use GuzzleHttp\Psr7\Request;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use GuzzleHttp\Client as GuzzleClient;

class Client
{
    private $basePath = 'api/v1';
    private $serverId = 'localhost';
    private $apiKey;

    /** @var HttpClient */
    private $client;

    /** @var Serializer */
    private $serializer;

    public function __construct($apiHost, $apiKey, ?GuzzleClient $client = null)
    {
        if (null === $client) {
            $this->client = HttpClientDiscovery::find();
        }
        $this->apiKey = $apiKey;
        $this->apiUri = sprintf('%s/%s', $apiHost, $this->basePath);

        $normalizer = new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter(), null, new ReflectionExtractor());
        $this->serializer = new Serializer([new DateTimeNormalizer(), $normalizer], ['json' => new JsonEncoder()]);
    }

    /**
     * @param string|null     $payload
     * @param int|string|null $id
     * @param string          $endpoint
     * @param string|null     $hydrationClass
     * @param bool            $isList
     * @param string          $method
     *
     * @throws \Http\Client\Exception
     * @throws \LogicException
     *
     * @return mixed
     */
    public function call($payload = null, $uri, $hydrationClass = null, $isList = false, $method = 'GET')
    {
        $uri = sprintf('%s/%s', $this->apiUri, $uri);
        $uri = rtrim($uri, '/');

        dump($uri);


        $request = new Request($method, $uri, [
            'X-API-Key' => $this->apiKey,
            'Content-Type' => 'application/json',
        ], $payload);

        $response = $this->client->sendRequest($request);
        $responseBody = $response->getBody()->getContents();
        $responseData = json_decode($responseBody);

        dump($responseData);

        if ($response->getStatusCode() >= 300) {
            $message = isset($responseData->message) ?? 'Unknown';
            throw new \Exception(sprintf('Error on request %s: %s', $response->getStatusCode(), $message));
        }

        if (null !== $hydrationClass && class_exists($hydrationClass)) {
            return $this->denormalizeObject($hydrationClass, $responseData, $isList);
        } elseif (null !== $hydrationClass && !class_exists($hydrationClass)) {
            throw new \Exception(sprintf('HydrationClass (%s) does not exist', $hydrationClass));
        }

        return $responseData;
    }


    public function denormalizeObject($hydrationClass, $dataObject, $isList = false)
    {
        if (!$isList) {
            $dataObject = [$dataObject];
        }

        foreach ($dataObject as $data) {
            $result[] = $this->serializer->denormalize($data, $hydrationClass, null, [
                ObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
            ]);
        }

        if ($isList) {
            return $result;
        }

        return current($result);
    }

    protected function getClient(): HttpClient
    {
        return $this->client;
    }

    /**
     * @return Serializer
     */
    public function getSerializer(): Serializer
    {
        return $this->serializer;
    }
}
