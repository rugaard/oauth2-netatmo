<?php
namespace Rugaard\OAuth2\Client\Netatmo\Tests;

use PHPUnit\Framework\TestCase;
use Rugaard\OAuth2\Client\Netatmo\Provider\NetatmoResourceOwner;

/**
 * Class NetatmoResourceOwnerTest
 *
 * @package Rugaard\OAuth2\Client\Netatmo\Tests
 */
class NetatmoResourceOwnerTest extends TestCase
{
    /**
     * Netatmo Resource Owner.
     *
     * @var \Rugaard\OAuth2\Client\Netatmo\Provider\NetatmoResourceOwner
     */
    protected $user;

    /**
     * Set up Netatmo resource owner test case.
     *
     * @return void
     */
    public function setUp()
    {
        $this->user = new NetatmoResourceOwner(
            $this->getUserData()
        );
    }

    /**
     * Test get ID.
     *
     * @return void
     */
    public function testGetId()
    {
        $this->assertEquals('mocked_user_id', $this->user->getId());
    }

    /**
     * Test get e-mail.
     *
     * @return void
     */
    public function testGetEmail()
    {
        $this->assertEquals('mocked_email@example.com', $this->user->getEmail());
    }

    /**
     * Test get language.
     *
     * @return void
     */
    public function testGetLanguage()
    {
        $this->assertEquals('en-US', $this->user->getLanguage());
    }

    /**
     * Test get locale.
     *
     * @return void
     */
    public function testGetLocale()
    {
        $this->assertEquals('en-DK', $this->user->getLocale());
    }

    /**
     * Test get "feel like" algorithm type.
     *
     * @return void
     */
    public function testGetFeelLikeAlgorithm()
    {
        $this->assertEquals(0, $this->user->getFeelLikeAlgorithm());
    }

    /**
     * Test get "feel like" algorithm type as a readable string.
     *
     * @return void
     */
    public function testGetFeelLikeAlgorithmReadable()
    {
        // Test "humidex" option (default).
        $this->assertEquals('Humidex', $this->user->getFeelLikeAlgorithmReadable());

        // Test "heat index" option.
        $heatIndex = new NetatmoResourceOwner(array_merge($this->getUserData(), ['feel_like_algorithm' => 1]));
        $this->assertEquals('Heat index', $heatIndex->getFeelLikeAlgorithmReadable());

        // Test "unknown" option.
        $unknown = new NetatmoResourceOwner(array_merge($this->getUserData(), ['feel_like_algorithm' => 2]));
        $this->assertEquals('Unknown', $unknown->getFeelLikeAlgorithmReadable());
    }

    /**
     * Test get system unit type.
     *
     * @return void
     */
    public function testGetUnit()
    {
        $this->assertEquals(0, $this->user->getUnit());
    }

    /**
     * Test get system unit type as a readable string.
     *
     * @return void
     */
    public function testGetUnitReadable()
    {
        // Test "metric" option (default).
        $this->assertEquals('metric', $this->user->getUnitReadable());

        // Test "imperial" option.
        $imperial = new NetatmoResourceOwner(array_merge($this->getUserData(), ['unit_system' => 1]));
        $this->assertEquals('imperial', $imperial->getUnitReadable());

        // Test "unknown" option.
        $unknown = new NetatmoResourceOwner(array_merge($this->getUserData(), ['unit_system' => 2]));
        $this->assertEquals('Unknown', $unknown->getUnitReadable());
    }

    /**
     * Test get pressure unit type.
     *
     * @return void
     */
    public function testGetPressureUnit()
    {
        $this->assertEquals(0, $this->user->getPressureUnit());
    }

    /**
     * Test get pressure unit type as a readable string.
     *
     * @return void
     */
    public function testGetPressureUnitReadable()
    {
        // Test "mbar" option (default).
        $this->assertEquals('mbar', $this->user->getPressureUnitReadable());

        // Test "inHg" option.
        $inHg = new NetatmoResourceOwner(array_merge($this->getUserData(), ['unit_pressure' => 1]));
        $this->assertEquals('inHg', $inHg->getPressureUnitReadable());

        // Test "mmHg" option.
        $mmHg = new NetatmoResourceOwner(array_merge($this->getUserData(), ['unit_pressure' => 2]));
        $this->assertEquals('mmHg', $mmHg->getPressureUnitReadable());

        // Test "unknown" option.
        $unknown = new NetatmoResourceOwner(array_merge($this->getUserData(), ['unit_pressure' => 3]));
        $this->assertEquals('Unknown', $unknown->getPressureUnitReadable());
    }

    /**
     * Test get wind unit type.
     *
     * @return void
     */
    public function testGetWindUnit()
    {
        $this->assertEquals(2, $this->user->getWindUnit());
    }

    /**
     * Test get wind unit type as a readable string.
     *
     * @return void
     */
    public function testGetWindUnitReadable()
    {
        // Test "m/s" option (default).
        $this->assertEquals('m/s', $this->user->getWindUnitReadable());

        // Test "km/h" option.
        $kmh = new NetatmoResourceOwner(array_merge($this->getUserData(), ['unit_wind' => 0]));
        $this->assertEquals('km/h', $kmh->getWindUnitReadable());

        // Test "mph" option.
        $mph = new NetatmoResourceOwner(array_merge($this->getUserData(), ['unit_wind' => 1]));
        $this->assertEquals('mph', $mph->getWindUnitReadable());

        // Test "bft" option.
        $bft = new NetatmoResourceOwner(array_merge($this->getUserData(), ['unit_wind' => 3]));
        $this->assertEquals('bft', $bft->getWindUnitReadable());

        // Test "kts" option.
        $kts = new NetatmoResourceOwner(array_merge($this->getUserData(), ['unit_wind' => 4]));
        $this->assertEquals('kts', $kts->getWindUnitReadable());

        // Test "unknown" option.
        $unknown = new NetatmoResourceOwner(array_merge($this->getUserData(), ['unit_wind' => 5]));
        $this->assertEquals('Unknown', $unknown->getWindUnitReadable());
    }

    /**
     * Test to array.
     *
     * @return void
     */
    public function testToArray()
    {
        $data = $this->user->toArray();

        $this->assertArrayHasKey('id', $data);
        $this->assertEquals('mocked_user_id', $data['id']);

        $this->assertArrayHasKey('email', $data);
        $this->assertEquals('mocked_email@example.com', $data['email']);

        $this->assertArrayHasKey('language', $data);
        $this->assertEquals('en-US', $data['language']);

        $this->assertArrayHasKey('locale', $data);
        $this->assertEquals('en-DK', $data['locale']);

        $this->assertArrayHasKey('feel_like_algorithm', $data);
        $this->assertEquals(0, $data['feel_like_algorithm']);

        $this->assertArrayHasKey('unit_system', $data);
        $this->assertEquals(0, $data['unit_system']);

        $this->assertArrayHasKey('unit_pressure', $data);
        $this->assertEquals(0, $data['unit_pressure']);

        $this->assertArrayHasKey('unit_wind', $data);
        $this->assertEquals(2, $data['unit_wind']);
    }

    private function getUserData()
    {
        return [
            'id' => 'mocked_user_id',
            'email' => 'mocked_email@example.com',
            'language' => 'en-US',
            'locale' => 'en-DK',
            'feel_like_algorithm' => 0,
            'unit_pressure' => 0,
            'unit_system' => 0,
            'unit_wind' => 2,
        ];
    }
}