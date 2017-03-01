<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace dukt\facebook\base;

use Craft;
use dukt\facebook\Plugin as Facebook;

/**
 * PluginTrait implements the common methods and properties for plugin classes.
 *
 * @property \dukt\facebook\services\Api            $api            The api service
 * @property \dukt\facebook\services\Cache          $cache          The cache service
 * @property \dukt\facebook\services\Oauth          $oauth          The oauth service
 * @property \dukt\facebook\services\Reports        $reports        The reports service
 *
 * @author Dukt <support@dukt.net>
 * @since  2.0
 */
trait PluginTrait
{
    /**
     * Returns the api service.
     *
     * @return \dukt\facebook\services\Api The api service
     */
    public function getApi()
    {
        /** @var Facebook $this */
        return $this->get('api');
    }

    /**
     * Returns the cache service.
     *
     * @return \dukt\facebook\services\Cache The cache service
     */
    public function getCache()
    {
        /** @var Facebook $this */
        return $this->get('cache');
    }

    /**
     * Returns the oauth service.
     *
     * @return \dukt\facebook\services\Oauth The oauth service
     */
    public function getOauth()
    {
        /** @var Facebook $this */
        return $this->get('oauth');
    }

    /**
     * Returns the reports service.
     *
     * @return \dukt\facebook\services\Reports The reports service
     */
    public function getReports()
    {
        /** @var Facebook $this */
        return $this->get('reports');
    }

    /**
     * Returns `true` if client ID, client secret and token are defined.
     *
     * @return bool
     */
    public function isConfigured()
    {
        $oauthClientId = $this->getOauth()->getClientId();
        $oauthClientSecret = $this->getOauth()->getClientSecret();

        if(!empty($oauthClientId) && !empty($oauthClientSecret))
        {
            $token = Facebook::$plugin->getOauth()->getToken();

            if($token)
            {
                return true;
            }
        }

        return false;
    }
}
