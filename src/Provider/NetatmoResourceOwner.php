<?php
namespace Rugaard\OAuth2\Client\Netatmo\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

/**
 * Class NetatmoResourceOwner
 *
 * @package Rugaard\OAuth2\Client\Netatmo\Provider
 */
class NetatmoResourceOwner implements ResourceOwnerInterface
{
    // System unit types.
    const UNIT_METRIC = 0;
    const UNIT_IMPERIAL = 1;

    // Wind unit types.
    const UNIT_WIND_KPH = 0;
    const UNIT_WIND_MPH = 1;
    const UNIT_WIND_MS  = 2;
    const UNIT_WIND_BEAUFORT = 3;
    const UNIT_WIND_KNOT = 4;

    // Pressure unit types.
    const UNIT_PRESSURE_MBAR = 0;
    const UNIT_PRESSURE_INHG = 1;
    const UNIT_PRESSURE_MMHG = 2;

    // "Feel like" algorithm type.
    const FEEL_LIKE_ALGORITHM_HUMIDEX = 0;
    const FEEL_LIKE_ALGORITHM_HEAT_INDEX = 1;

    /**
     * User details.
     *
     * @var array
     */
    protected $user = [];

    /**
     * NetatmoResourceOwner constructor.
     *
     * @param array $user
     */
    public function __construct(array $user)
    {
        $this->user = $user;
    }

    /**
     * Get user's ID.
     *
     * @return string
     */
    public function getId()
    {
        return $this->user['id'];
    }

    /**
     * Get user's e-mail.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->user['email'];
    }

    /**
     * Get user's locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->user['locale'];
    }

    /**
     * Get user's language.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->user['language'];
    }

    /**
     * Get user's system unit type.
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->user['unit_system'];
    }

    /**
     * Get user's system unit type as a readable string.
     *
     * @return string
     */
    public function getUnitReadable()
    {
        switch ($this->getUnit())
        {
            case self::UNIT_METRIC:
                return 'metric';
            case self::UNIT_IMPERIAL:
                return 'imperial';
            default:
                return 'Unknown';
        }
    }

    /**
     * Get user's wind unit type.
     *
     * @return string
     */
    public function getWindUnit()
    {
        return $this->user['unit_wind'];
    }

    /**
     * Get user's wind unit type as a readable string.
     *
     * @return string
     */
    public function getWindUnitReadable()
    {
        switch ($this->getWindUnit())
        {
            case self::UNIT_WIND_KPH:
                return 'km/h';
            case self::UNIT_WIND_MPH:
                return 'mph';
            case self::UNIT_WIND_MS:
                return 'm/s';
            case self::UNIT_WIND_BEAUFORT:
                return 'bft';
            case self::UNIT_WIND_KNOT:
                return 'kts';
            default:
                return 'Unknown';
        }
    }

    /**
     * Get user's pressure unit type.
     *
     * @return string
     */
    public function getPressureUnit()
    {
        return $this->user['unit_pressure'];
    }

    /**
     * Get user's pressure unit type as a readable string.
     *
     * @return string
     */
    public function getPressureUnitReadable()
    {
        switch ($this->getPressureUnit())
        {
            case self::UNIT_PRESSURE_MBAR:
                return 'mbar';
            case self::UNIT_PRESSURE_INHG:
                return 'inHg';
            case self::UNIT_PRESSURE_MMHG:
                return 'mmHg';
            default:
                return 'Unknown';
        }
    }

    /**
     * Get user's "feel like" algorithm type.
     *
     * @return string
     */
    public function getFeelLikeAlgorithm()
    {
        return $this->user['feel_like_algorithm'];
    }

    /**
     * Get user's pressure unit type as a readable string.
     *
     * @return string
     */
    public function getFeelLikeAlgorithmReadable()
    {
        switch ($this->getFeelLikeAlgorithm())
        {
            case self::FEEL_LIKE_ALGORITHM_HUMIDEX:
                return 'Humidex';
            case self::FEEL_LIKE_ALGORITHM_HEAT_INDEX:
                return 'Heat index';
            default:
                return 'Unknown';
        }
    }

    /**
     * Return user's raw data.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->user;
    }
}