<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace Craft;

use dukt\facebook\Plugin as Facebook;

class FacebookVariable
{
    // Public Methods
    // =========================================================================

    public function api()
    {
        return Facebook::$plugin->facebook_api;
    }
}