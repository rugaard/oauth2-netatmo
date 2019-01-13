<?php
namespace Rugaard\OAuth2\Client\Netatmo\Tests;

use GuzzleHttp\ClientInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\QueryBuilderTrait;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Rugaard\OAuth2\Client\Netatmo\Provider\Netatmo;
use Rugaard\OAuth2\Client\Netatmo\Provider\NetatmoResourceOwner;

/**
 * Class NetatmoTest
 *
 * @package Rugaard\OAuth2\Client\Netatmo\Tests
 */
class NetatmoTest extends TestCase
{
    use QueryBuilderTrait;

    /**
     * Netatmo OAuth2 client.
     *
     * @var \Rugaard\OAuth2\Client\Netatmo\Provider\Netatmo
     */
    protected $provider;

    /**
     * Set up Netatmo test case.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->provider = new Netatmo([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'none',
        ]);
    }

    /**
     * Test authorization URL.
     */
    public function testGetAuthorizationUrl()
    {
        // Generate authorization URL.
        $url = $this->provider->getAuthorizationUrl();

        // Parse authorization URL:
        $uri = parse_url($url);

        // Run assertion(s).
        $this->assertEquals('api.netatmo.net', $uri['host']);
        $this->assertEquals('/oauth2/authorize', $uri['path']);
    }

    /**
     * Test authorization URL's query.
     *
     * @return void
     */
    public function testAuthorizationUrlQuery()
    {
        // Generate authorization URL.
        $url = $this->provider->getAuthorizationUrl();

        // Parse authorization URL.
        $uri = parse_url($url);

        // Split authorization URL's query into an array.
        parse_str($uri['query'], $query);

        // Run assertion(s).
        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
        $this->assertArrayHasKey('state', $query);
        $this->assertArrayHasKey('scope', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertNotNull($this->provider->getState());
    }

    /**
     * Test scope handling.
     *
     * @return void
     */
    public function testScopes()
    {
        // Get provider's scope separator.
        $scopeSeparator = $this->provider->getScopeSeparator();

        // Mock scope options.
        $options = [
            'scope' => ['mock_read_scope', 'mock_write_scope']
        ];

        // Generate authorization URL with mocked scope options.
        $url = $this->provider->getAuthorizationUrl($options);

        // Build encoded query string with mocked scope options.
        $encodedScope = $this->buildQueryString([
            'scope' => implode($scopeSeparator, $options['scope'])
        ]);

        // Run assertion(s).
        $this->assertContains($encodedScope, $url);
    }

    /**
     * Test get base access token URL.
     *
     * @return void
     */
    public function testGetBaseAccessTokenUrl()
    {
        // Generate base access token URL.
        $url = $this->provider->getBaseAccessTokenUrl([]);

        // Parse base access token URL.
        $uri = parse_url($url);

        // Run assertion(s).
        $this->assertEquals('api.netatmo.net', $uri['host']);
        $this->assertEquals('/oauth2/token', $uri['path']);
    }

    /**
     * Test get access token.
     *
     * @return void
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function testGetAccessToken()
    {
        // Mock response for mocked HTTP client.
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $response->shouldReceive('getBody')->andReturn('{"scope":["mock_scope"],"access_token":"mock_access_token","refresh_token":"mock_refresh_token","expires_in":10800}');

        // Mock HTTP client.
        $client = Mockery::mock(ClientInterface::class);
        $client->shouldReceive('send')->times(1)->andReturn($response);

        // Set HTTP client of provider.
        $this->provider->setHttpClient($client);

        // Generate access token.
        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);

        // Run assertion(s).
        $this->assertInstanceOf(AccessToken::class, $token);
        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertEquals('mock_refresh_token', $token->getRefreshToken());
        $this->assertEquals(time() + 10800, $token->getExpires());
        $this->assertNull($token->getResourceOwnerId());
    }

    /**
     * Test get resource owner details URL.
     *
     * @return void
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function testGetResourceOwnerDetailsUrl()
    {
        // Mock token response for mocked HTTP client.
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $response->shouldReceive('getBody')->andReturn('{"scope":["mock_scope"],"access_token":"mock_access_token","refresh_token":"mock_refresh_token","expires_in":10800}');

        // Mock HTTP client.
        $client = Mockery::mock(ClientInterface::class);
        $client->shouldReceive('send')->times(1)->andReturn($response);

        // Set HTTP client of provider.
        $this->provider->setHttpClient($client);

        // Generate access token.
        /* @var $token \League\OAuth2\Client\Token\AccessToken */
        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);

        // Get resource owner details URL.
        $url = $this->provider->getResourceOwnerDetailsUrl($token);

        // Parse base access token URL.
        $uri = parse_url($url);

        // Run assertion(s).
        $this->assertEquals('api.netatmo.com', $uri['host']);
        $this->assertEquals('/api/homesdata', $uri['path']);
        $this->assertEquals('access_token=mock_access_token', $uri['query']);
    }

    /**
     * Test create resource owner.
     *
     * @return void
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function testCreateResourceOwner()
    {
        // Mock token response for mocked HTTP client.
        $tokenResponse = Mockery::mock(ResponseInterface::class);
        $tokenResponse->shouldReceive('getStatusCode')->andReturn(200);
        $tokenResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $tokenResponse->shouldReceive('getBody')->andReturn('{"scope":["mock_scope"],"access_token":"mock_access_token","refresh_token":"mock_refresh_token","expires_in":10800}');

        // Mock user response for mocked HTTP client.
        $userResponse = Mockery::mock(ResponseInterface::class);
        $userResponse->shouldReceive('getStatusCode')->andReturn(200);
        $userResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $userResponse->shouldReceive('getBody')->andReturn('{"body":{"homes":[],"user":{"email":"mocked_email@example.com","language":"en-US","locale":"en-DK","feel_like_algorithm":0,"unit_pressure":0,"unit_system":0,"unit_wind":1,"id":"mocked_access_token"}}}');

        // Mock HTTP client.
        $client = Mockery::mock(ClientInterface::class);
        $client->shouldReceive('send')->times(2)->andReturn($tokenResponse, $userResponse);

        // Set HTTP client of provider.
        $this->provider->setHttpClient($client);

        // Generate access token.
        /* @var $token \League\OAuth2\Client\Token\AccessToken */
        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);

        // Get user data.
        $user = $this->provider->getResourceOwner($token);

        // Run assertion(s).
        $this->assertInstanceOf(NetatmoResourceOwner::class, $user);
    }

    /**
     * Test exception is thrown when request returns an error.
     *
     * @return void
     * @throws IdentityProviderException
     */
    public function testExceptionThrownWhenRequestReturnsError()
    {
        // Mock token response for mocked HTTP client.
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->andReturn(401);
        $response->shouldReceive('getReasonPhrase')->andReturn('Unauthorized');
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $response->shouldReceive('getBody')->andReturn('{"error":{"code":2,"message":"Invalid access token"}}');

        // Mock HTTP client.
        $client = Mockery::mock(ClientInterface::class);
        $client->shouldReceive('send')->times(1)->andReturn($response);

        // Set HTTP client of provider.
        $this->provider->setHttpClient($client);

        // We're expecting a thrown exception.
        $this->expectException(IdentityProviderException::class);

        $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
    }

    /**
     * Tear down test case after all tests are done.
     *
     * @return void
     */
    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }
}