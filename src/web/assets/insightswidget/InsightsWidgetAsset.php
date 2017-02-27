<?php
namespace dukt\facebook\web\assets\insightswidget;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * Insights widget asset bundle.
 *
 * @author Dukt <support@dukt.net>
 * @since  2.0
 */
class InsightsWidgetAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__.'/dist';

        // define the dependencies
        $this->depends = [
            CpAsset::class,
        ];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            'InsightsWidget.js',
        ];

        $this->css = [
            'insights-widget.css',
        ];

        parent::init();
    }
}