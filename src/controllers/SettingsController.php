<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace dukt\facebook\controllers;

use Craft;
use craft\web\Controller;
use dukt\facebook\Plugin as Facebook;

/**
 * Class SettingsController
 *
 * @author Dukt <support@dukt.net>
 * @since  2.0
 */
class SettingsController extends Controller
{
    // Public Methods
    // =========================================================================

    /**
     * Settings
     *
     * @return string
     */
    public function actionIndex()
    {
        $token = Facebook::$plugin->getOauth()->getToken();

        if ($token) {
            try {
                $account = Facebook::$plugin->getCache()->get(['getResourceOwner', $token]);

                if (!$account) {
                    $provider = Facebook::$plugin->getOauth()->getOauthProvider();
                    $account = $provider->getResourceOwner($token);
                    Facebook::$plugin->getCache()->set(['getResourceOwner', $token], $account);
                }
            } catch (\Exception $e) {
                Craft::trace("Couldn't get account\r\n".$e->getMessage().'\r\n'.$e->getTraceAsString(), __METHOD__);

                if (method_exists($e, 'getResponse')) {
                    Craft::trace("GuzzleErrorResponse\r\n".$e->getResponse(), __METHOD__);
                }

                $error = $e->getMessage();
            }
        }

        $plugin = Craft::$app->getPlugins()->getPlugin('facebook');

        return $this->renderTemplate('facebook/settings/index', [
            'account' => (isset($account) ? $account : null),
            'token' => (isset($token) ? $token : null),
            'error' => (isset($error) ? $error : null),
            'settings' => $plugin->getSettings(),
            'redirectUri' => Facebook::$plugin->oauth->getRedirectUri(),
        ]);
    }

    public function actionOauth()
    {
        $plugin = Craft::$app->getPlugins()->getPlugin('facebook');

        $variables = array(
            'provider' => false,
            'account' => false,
            'token' => false,
            'error' => false
        );

        $provider = Facebook::$plugin->getOauth()->getOauthProvider();

        if ($provider)
        {
            $token = Facebook::$plugin->getOauth()->getToken();

            if ($token)
            {
                try
                {
                    $account = Facebook::$plugin->getCache()->get(['getResourceOwner', $token]);

                    if(!$account)
                    {
                        $account = $provider->getResourceOwner($token);
                        Facebook::$plugin->getCache()->set(['getResourceOwner', $token], $account);
                    }

                    if ($account)
                    {
                        $variables['account'] = $account;
                    }
                }
                catch(\Exception $e)
                {
                    Craft::trace("Couldn't get account\r\n".$e->getMessage().'\r\n'.$e->getTraceAsString(), __METHOD__);

                    if(method_exists($e, 'getResponse'))
                    {
                        Craft::trace("GuzzleErrorResponse\r\n".$e->getResponse(), __METHOD__);
                    }

                    $variables['error'] = $e->getMessage();
                }
            }

            $variables['token'] = $token;
            $variables['provider'] = $provider;
        }

        $variables['redirectUri'] = Facebook::$plugin->oauth->getRedirectUri();
        $variables['settings'] = $plugin->getSettings();

        return $this->renderTemplate('facebook/settings/oauth', $variables);
    }

    public function actionSaveOauthSettings()
    {
        $plugin = Craft::$app->getPlugins()->getPlugin('facebook');
        $settings = $plugin->getSettings();

        $postSettings = Craft::$app->getRequest()->getBodyParam('settings');

        $settings['oauthClientId'] = $postSettings['oauthClientId'];
        $settings['oauthClientSecret'] = $postSettings['oauthClientSecret'];

        Craft::$app->getPlugins()->savePluginSettings($plugin, $settings->getAttributes());

        Craft::$app->getSession()->setNotice(Craft::t('app', 'OAuth settings saved.'));

        return $this->redirectToPostedUrl();
    }
}
