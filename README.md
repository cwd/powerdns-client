cwdPowerDNS - Client
====================

[![pipeline status](https://gitlab.cwd.at/cwd/powerdns-client/badges/develop/pipeline.svg)](https://gitlab.cwd.at/cwd/powerdns-client/commits/develop) 
[![coverage report](https://gitlab.cwd.at/cwd/powerdns-client/badges/develop/coverage.svg)](https://gitlab.cwd.at/cwd/powerdns-client/commits/develop) 
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cwd/powerdns-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cwd/powerdns-client/?branch=master)


Simple Usage:
-------------
```
$client = PowerDNSClientFactory::createClient('http://mydns.host.tld', 'YOUR_APIKEY_FROM_POWERDNS_CONFIG', 'localhost');

$servers = $client->servers()->all();

// Get all Zones:
$zones = $client->zones()->all();

// Create a new Zone:
$zone = (new Zone())
    ->setName('example.com.')
    ->setKind(Zone::KIND_MASTER)
    ->addRrset(
        (new Zone\RRSet())->setName('www.example.com.')
                          ->setType('A')
                          ->setTtl(3600)
                          ->addRecord(
                              (new Zone\Record())->setContent('127.0.0.1')
                                                 ->setDisabled(false)
                          )
                          ->addComment(
                              (new Zone\Comment())->setContent('Test Test')
                                  ->setAccount('Max Mustermann')
                          )
    )
    ->addRrset((new Zone\RRSet())->setName('delete.example.com.')
        ->setType('A')
        ->setTtl(3600)
        ->addRecord(
            (new Zone\Record())->setContent('127.0.0.1')
                ->setDisabled(false)
        )
        ->addComment((new Zone\Comment())->setContent('test')->setAccount('Maxi'))
    )
;

$zone = $client->zones()->create($zone);
```

Symfony Integration:
---------------------

In bundles.php:
```
return [
    // ....
    Cwd\PowerDNSClient\CwdPowerDNSClient::class => ['all' => true],
];
```

add Basic Config:
```
cwd_power_dns_client:
  uri: 'http://mydns.host.tld'
  api_key: 'YOUR_APIKEY_FROM_POWERDNS_CONFIG'
  default_server: 'localhost'
```

Usage:
```
// From the Container
$client = $ontainer->get(PowerDNSClient::class);

// or via autowiring
public function __construct(PowerDNSClient $client) 
{
   // ....
}
```
