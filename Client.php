<?php

/*
 * This file is part of the CwdPowerDNS Client
 *
 * (c) 2018 cwd.at GmbH <office@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Cwd\PowerDNSClient;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use GuzzleHttp\Psr7\Request;
use Http\Discovery\HttpClientDiscovery;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use GuzzleHttp\Client as GuzzleClient;

class Client
{
    private $basePath = 'api/v1';
    private $apiKey;

    private $apiUri;

    /** @var GuzzleClient */
    private $client;

    /** @var Serializer */
    private $serializer;

    public function __construct($apiHost, $apiKey, ?GuzzleClient $client = null, Reader $annotationReader)
    {
        $this->apiKey = $apiKey;
        $this->apiUri = sprintf('%s/%s', $apiHost, $this->basePath);
        if (null === $client) {
            //$this->client  = new GuzzleClient(['base_uri' => $this->apiUri]);
            $this->client = HttpClientDiscovery::find();
        }

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader($annotationReader));
        $discriminator = new ClassDiscriminatorFromClassMetadata($classMetadataFactory);

        $normalizer = new ObjectNormalizer($classMetadataFactory, new CamelCaseToSnakeCaseNameConverter(), new PropertyAccessor(), new ReflectionExtractor(), $discriminator);
        $this->serializer = new Serializer([new DateTimeNormalizer(), new ArrayDenormalizer(), $normalizer], ['json' => new JsonEncoder()]);
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
    public function call($payload = null, $uri, $hydrationClass = null, $isList = false, $method = 'GET', array $queryParams = [])
    {
        $uri = rtrim(sprintf('%s/%s', $this->apiUri, $uri), '/');

        if (count($queryParams) > 0) {
            $uri .= '?'.http_build_query($queryParams);
        }

        $request = new Request($method, $uri, [
            'X-API-Key' => $this->apiKey,
            'Content-Type' => 'application/json',
        ], $payload);

        $response = $this->client->sendRequest($request);

        $responseBody = $response->getBody()->getContents();
        $responseData = json_decode($responseBody);

        if ($response->getStatusCode() >= 300 && isset($responseData->error)) {
            throw new \LogicException(sprintf('Error on %s request %s: %s', $method, $uri, $responseData->error));
        }
        if ($response->getStatusCode() >= 300) {
            $message = isset($responseData->message) ?? 'Unknown';
            throw new \Exception(sprintf('Error on request %s: %s', $response->getStatusCode(), $message));
        }

        if (null !== $hydrationClass && class_exists($hydrationClass)) {
            return $this->denormalizeObject($hydrationClass, $responseData, $isList);
        }
        if (null !== $hydrationClass && !class_exists($hydrationClass)) {
            throw new \Exception(sprintf('HydrationClass (%s) does not exist', $hydrationClass));
        }

        return $responseData;
    }

    public function denormalizeObject($hydrationClass, $dataObject, $isList = false)
    {
        if (!$isList) {
            $dataObject = [$dataObject];
        }

        $result = [];

        foreach ($dataObject as $data) {
            $result[] = $this->serializer->denormalize($data, $hydrationClass, null, [
                ObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => false,
            ]);
        }

        if ($isList) {
            return $result;
        }

        return current($result);
    }

    /**
     * @return Serializer
     */
    public function getSerializer(): Serializer
    {
        return $this->serializer;
    }
}
