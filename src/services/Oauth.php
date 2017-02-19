<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace dukt\facebook\services;

use Craft;
use craft\helpers\UrlHelper;
use League\OAuth2\Client\Provider\Facebook;
use yii\base\Component;
use dukt\facebook\Plugin as FacebookPlugin;
use League\OAuth2\Client\Token\AccessToken;

class Oauth extends Component
{
    // Properties
    // =========================================================================

    private $token;

	// Public Methods
	// =========================================================================

    /**
     * Returns a Twitter provider (server) object.
     *
     * @return Facebook
     */
    public function getOauthProvider()
    {
        $options = Craft::$app->config->get('oauthProviderOptions', 'facebook');

        if(!isset($options['graphApiVersion']))
        {
            $options['graphApiVersion'] = Craft::$app->config->get('apiVersion', 'facebook');
        }

        if(!isset($options['redirectUri']))
        {
            $options['redirectUri'] = UrlHelper::actionUrl('facebook/oauth/callback');
        }

        return new Facebook($options);
    }

    /**
     * Save Token
     *
     * @param AccessToken $token
     */
    public function saveToken(AccessToken $token)
    {
        // Save token and token secret in the plugin's settings

        $plugin = Craft::$app->plugins->getPlugin('facebook');

        $settings = $plugin->getSettings();

        $token = [
            'accessToken' => $token->getToken(),
            'expires' => $token->getExpires(),
            'refreshToken' => $token->getRefreshToken(),
            'resourceOwnerId' => $token->getResourceOwnerId(),
            'values' => $token->getValues(),
        ];

        $settings->token = $token;

        Craft::$app->plugins->savePluginSettings($plugin, $settings->getAttributes());
    }

    /**
     * Get OAuth Token
     *
     * @return AccessToken|null
     */
    public function getToken()
    {
        if($this->token)
        {
            return $this->token;
        }
        else
        {
            $plugin = Craft::$app->plugins->getPlugin('facebook');
            $settings = $plugin->getSettings();

            if($settings->token) {

                $token = new AccessToken([
                    'access_token' => (isset($settings->token['accessToken']) ? $settings->token['accessToken'] : null),
                    'expires' => (isset($settings->token['expires']) ? $settings->token['expires'] : null),
                    'refresh_token' => (isset($settings->token['refreshToken']) ? $settings->token['refreshToken'] : null),
                    'resource_owner_id' => (isset($settings->token['resourceOwnerId']) ? $settings->token['resourceOwnerId'] : null),
                    'values' => (isset($settings->token['values']) ? $settings->token['values'] : null),
                ]);

                if($token->getExpires() && $token->hasExpired())
                {
                    $provider = $this->getOauthProvider();
                    $grant = new \League\OAuth2\Client\Grant\RefreshToken();
                    $newToken = $provider->getAccessToken($grant, ['refresh_token' => $token->getRefreshToken()]);

                    $token = new AccessToken([
                        'access_token' => $newToken->getToken(),
                        'expires' => $newToken->getExpires(),
                        'refresh_token' => $settings->token['refreshToken'],
                        'resource_owner_id' => $newToken->getResourceOwnerId(),
                        'values' => $newToken->getValues(),
                    ]);

                    $this->saveToken($token);
                }

                return $token;
            }
        }
    }

    /**
     * Delete Token
     *
     * @return bool
     */
    public function deleteToken()
    {
        $plugin = Craft::$app->plugins->getPlugin('facebook');

        $settings = $plugin->getSettings();
        $settings->token = null;
        Craft::$app->plugins->savePluginSettings($plugin, $settings->getAttributes());

        return true;
    }
}
