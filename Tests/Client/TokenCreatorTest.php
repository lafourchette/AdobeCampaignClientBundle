<?php


namespace LaFourchette\AdobeCampaignClientBundle\Tests\SoapClient;

use LaFourchette\AdobeCampaignClientBundle\Client\TokenCreator;
use Prophecy\Argument;

class TokenCreatorTest extends \PHPUnit_Framework_TestCase
{
    private $payload = <<<EOT
<?xml version='1.0'?>
<SOAP-ENV:Envelope xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:ns='urn:xtk:session' xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/'>
  <SOAP-ENV:Body>
    <LogonResponse xmlns='urn:xtk:session' SOAP-ENV:encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'>
      <pstrSessionToken xsi:type='xsd:string'>sessionToken</pstrSessionToken>
      <pSessionInfo xsi:type='ns:Element' SOAP-ENV:encodingStyle='http://xml.apache.org/xml-soap/literalxml'>
        <sessionInfo>
          <serverInfo advisedClientBuildNumber="8601" allowSQL="false" buildNumber="8601" databaseId="u4E6D5CED75416602" defaultNameSpace="laf" instanceName="LAFprod" majNumber="6" minClientBuildNumber="8100" minNumber="0" minNumberTechnical="0" securityTimeOut="86400" serverDate="2015-09-30 13:36:39.959Z" servicePack="1" sessionTimeOut="86400"/>
          <userInfo datakitInDatabase="true" homeDir="" instanceLocale="en-US" locale="en-US" loginCS="Olivier Laurendeau (olaurendeau@lafourchette.com)" loginId="396678" noConsoleCnx="false" orgUnitId="0" theme="" timezone="Europe/Paris">
            <login-group id="396677"/>
            <login-right right="admin"/>
            <installed-package name="_01_pkg_init" namespace="laf"/>
            <installed-package name="PKG_final_fld" namespace="laf"/>
            <installed-package name="PKG_laf_final_all_blocks" namespace="laf"/>
            <installed-package name="PKG_laf_final_img" namespace="laf"/>
            <installed-package name="PKG_laf_final_typo_lists" namespace="laf"/>
            <installed-package name="PKG_opg_laf" namespace="laf"/>
            <installed-package name="PKG_final_onboarding_dm" namespace="laf"/>
            <installed-package name="PKG_fld" namespace="laf"/>
            <installed-package name="PKG_laf_Templates_Final" namespace="laf"/>
            <installed-package name="PKG_final_onboarding_dm_fr" namespace="laf"/>
            <installed-package name="PKG11" namespace="laf"/>
            <installed-package name="PKG12" namespace="laf"/>
            <installed-package name="PKG_final_onboarding_dm_prospects" namespace="laf"/>
            <installed-package name="PKG_rights" namespace="laf"/>
            <installed-package name="PKG_ope_leo" namespace="laf"/>
            <installed-package name="PKG_MD" namespace="laf"/>
            <installed-package name="PKG_content" namespace="laf"/>
            <installed-package name="PKG_final_onboarding" namespace="laf"/>
            <installed-package name="core" namespace="xtk"/>
            <installed-package name="folder" namespace="nms"/>
            <installed-package name="systemStrings" namespace="nms"/>
            <installed-package name="ruleset" namespace="nms"/>
            <installed-package name="country" namespace="nms"/>
            <installed-package name="report" namespace="nms"/>
            <installed-package name="coreInteraction" namespace="nms"/>
            <installed-package name="core" namespace="nms"/>
            <installed-package name="campaign" namespace="nms"/>
            <installed-package name="mrm" namespace="nms"/>
            <installed-package name="simulation" namespace="nms"/>
            <installed-package name="interaction" namespace="nms"/>
            <installed-package name="social" namespace="nms"/>
            <installed-package name="mobile" namespace="nms"/>
            <installed-package name="content" namespace="ncm"/>
            <installed-package name="survey" namespace="nms"/>
            <installed-package name="response" namespace="nms"/>
            <installed-package name="campaignOptimization" namespace="nms"/>
            <installed-package name="deliverability" namespace="nms"/>
            <installed-package name="midEmitter" namespace="nms"/>
            <installed-package name="PKG25" namespace="laf"/>
            <installed-package name="paper" namespace="nms"/>
            <installed-package name="federatedDataAccess" namespace="nms"/>
            <installed-package name="PKG_final_schema_form" namespace="laf"/>
            <installed-package name="PKG_final_onboarding_ope" namespace="laf"/>
            <installed-package name="PKG_PT_BR" namespace="laf"/>
            <installed-package name="mobileApp" namespace="nms"/>
            <installed-package name="PKG_PR_PR" namespace="laf"/>
            <installed-package name="PKG_mobile" namespace="laf"/>
            <installed-package name="PK_LAFbroadLogRcp" namespace="laf"/>
            <installed-package name="PKG_PT_BR_BP" namespace="laf"/>
            <installed-package name="PKG_PT_BR_CH" namespace="laf"/>
            <installed-package name="PKG27" namespace="laf"/>
            <installed-package name="PK_RestaurantLocalizedText" namespace="laf"/>
            <installed-package name="PKG_Reservation" namespace="laf"/>
            <installed-package name="PKG_AnalyticsProduct" namespace="laf"/>
            <installed-package name="PKG29" namespace="laf"/>
            <installed-package name="PKG_AnalyticsVistor_201508" namespace="laf"/>
            <installed-package name="PKG_Push_Userkey2" namespace="laf"/>
            <installed-package name="NewData_201509" namespace="laf"/>
            <installed-package name="PKG_Sauv_parr_110915" namespace="laf"/>
            <installed-package name="CreationTableAlfoLSA" namespace="laf"/>
          </userInfo>
        </sessionInfo>
      </pSessionInfo>
      <pstrSecurityToken xsi:type='xsd:string'>securityToken</pstrSecurityToken>
    </LogonResponse>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOT;

    private $clientInstantiator;

    private $creator;

    public function setUp()
    {
        $this->clientInstantiator = $this->prophesize('LaFourchette\AdobeCampaignClientBundle\Client\ClientInstantiator');

        $this->creator = new TokenCreator(
            $this->clientInstantiator->reveal(),
            array(
                'base_uri' => 'http://foo.com',
                'login' => 'joe',
                'password' => 'ultra-secure'
            )
        );
    }

    public function testTokenCreationFailBecauseOfClientException()
    {
        $this->setExpectedException('LaFourchette\AdobeCampaignClientBundle\Exception\TokenCreationException');

        $client = $this
            ->getMockBuilder('\SoapClient')
            ->disableOriginalConstructor()
            ->setMethods(array('__doRequest'))
            ->getMock();

        $this->clientInstantiator->instantiateBasicClient(null, array(
            'location' => 'http://foo.com/nl/jsp/soaprouter.jsp',
            'uri' => 'http://foo.com',
            'trace' => 1
        ))->willReturn($client);

        $client
            ->expects($this->any())
            ->method('__doRequest')
            ->will($this->throwException(new \Exception()));

        $this->creator->create();
    }

    public function testTokenCreationFailBecauseOfResponseEmpty()
    {
        $this->setExpectedException('LaFourchette\AdobeCampaignClientBundle\Exception\TokenCreationException');

        $client = $this
            ->getMockBuilder('\SoapClient')
            ->disableOriginalConstructor()
            ->setMethods(array('__doRequest'))
            ->getMock();

        $this->clientInstantiator->instantiateBasicClient(null, array(
            'location' => 'http://foo.com/nl/jsp/soaprouter.jsp',
            'uri' => 'http://foo.com',
            'trace' => 1
        ))->willReturn($client);

        $client
            ->expects($this->any())
            ->method('__doRequest')
            ->will($this->returnValue(null));

        $this->creator->create();
    }


    public function testTokenCreation()
    {
        $client = $this
            ->getMockBuilder('\SoapClient')
            ->disableOriginalConstructor()
            ->setMethods(array('__doRequest'))
            ->getMock();

        $this->clientInstantiator->instantiateBasicClient(null, array(
            'location' => 'http://foo.com/nl/jsp/soaprouter.jsp',
            'uri' => 'http://foo.com',
            'trace' => 1
        ))->willReturn($client);

        $client
            ->expects($this->any())
            ->method('__doRequest')
            ->will($this->returnValue($this->payload));

        $token = $this->creator->create();

        $this->assertSame('http://foo.com', $token->getBaseUri());
        $this->assertSame('securityToken', $token->getSecurity());
        $this->assertSame('sessionToken', $token->getSession());
    }


}
