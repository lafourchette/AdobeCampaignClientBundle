# AdobeCampaignClientBundle
[![Build Status](https://travis-ci.org/lafourchette/AdobeCampaignClientBundle.svg)](https://travis-ci.org/lafourchette/AdobeCampaignClientBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lafourchette/AdobeCampaignClientBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lafourchette/AdobeCampaignClientBundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/lafourchette/AdobeCampaignClientBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/lafourchette/AdobeCampaignClientBundle/?branch=master)

Provide access to adobe campaign API

## Installation
Add AdobeCampaignClientBundle to your composer.json, then update

```json
{
    ...
    "require": {
        "lafourchette/adobe-campaign-client-bundle": "~1.0"
    },
    ...
}
```
Add AdobeCampaignClientBundle to your application kernel

```php
    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new AdobeCampaignClientBundle(),
            // ...
        );
    }
```

Update your configuration

```yml
# app/config/config.yml
adobe_campaign_client:
    base_uri: http://adobe_campaign.uri.com
    login: login
    password: password
    schemas: #A client instance will be created for each schema you need
        query_def:
            name: query_def
            schema: xtk:queryDef
```

## Usage

```php
// Retrieve client service
$client = $container->get('adobe_campaign_client.client.query_def');

// Prepare SOAP envelope
$envelope = <<<EOT
<x:Envelope xmlns:x="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:xtk:queryDef">
  <x:Header/>
  <x:Body>
    <urn:ExecuteQuery>
      <urn:sessiontoken>%s</urn:sessiontoken>
      <entity xsi:type='ns:Element' SOAP-ENV:encodingStyle='http://xml.apache.org/xml-soap/literalxml'>
        <queryDef operation="select" schema="nms:broadLogRcp" xtkschema="xtk:queryDef" lineCount="100">
          <select>
            <node expr="@id"/>
            <node expr="[recipient/@id]"/>
            <node expr="[delivery/@label]"/>
          </select>
          <where>
            <condition expr="[@recipient-id] = %s"/>
          </where>
          <orderBy>
            <node expr="@eventDate" sortDesc="true"/>
          </orderBy>
        </queryDef>
      </entity>
    </urn:ExecuteQuery>
  </x:Body>
</x:Envelope>
EOT;

// Execute SOAP request
$xmlResponse = $client->doCustomSoapRequest(
    sprintf($envelope, $client->getConfiguration()->getSession(), $idCustomer),
    'ExecuteQuery'
);

// Parse results
$campaignList = array();
foreach ($xmlResponse->xpath('/Envelope/Body/ExecuteQueryResponse/pdomOutput/broadLogRcp-collection/broadLogRcp') as $broadLogRcp) {

    $delivery = $broadLogRcp->xpath('delivery');
    $delivery = current($delivery);

    $campaignList[] = array(
        'id' => (int) $broadLogRcp['id'],
        'label' => (string) $delivery['label']
        )
    );
}

return $campaignList;
```

## License

This bundle is under the MIT license.
