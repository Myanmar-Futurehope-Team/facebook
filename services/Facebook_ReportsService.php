<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace Craft;

class Facebook_ReportsService extends BaseApplicationComponent
{
	// Public Methods
	// =========================================================================

	public function getInsightsReport()
    {
        $pluginSettings = craft()->plugins->getPlugin('facebook')->getSettings();

        $facebookInsightsObjectId = $pluginSettings['facebookInsightsObjectId'];

        $report = craft()->facebook_cache->get(['insightsReport', $facebookInsightsObjectId]);

        if(!$report)
        {
            $token = craft()->facebook_oauth->getToken();

            if($token)
            {
                // Object

                $object = craft()->facebook_api->get('/'.$facebookInsightsObjectId, ['metadata' => 1, 'fields' => 'name']);;
                $objectType = $object['metadata']['type'];
                $message = false;

                $counts = [];

                switch ($objectType)
                {
                    case 'page':

                        // Insights for objects of type `page`

                        $supportedObject = true;

                        $insights = craft()->facebook_api->get('/'.$facebookInsightsObjectId.'/insights', array(
                            'metric' => 'page_fans,page_impressions_unique',
                            'since' => date('Y-m-d', strtotime('-6 day')),
                            'until' => date('Y-m-d', strtotime('+1 day')),
                        ));

                        foreach($insights['data'] as $insight)
                        {
                            switch ($insight['name'])
                            {
                                case 'page_fans':

                                    $weekTotalStart = $insight['values'][0]['value'];
                                    $weekTotalEnd = end($insight['values'])['value'];

                                    $weekTotal = $weekTotalEnd - $weekTotalStart;

                                    $counts[] = [
                                        'label' => 'Total likes',
                                        'value' => end($insight['values'])['value']
                                    ];
                                    $counts[] = [
                                        'label' => 'Likes this week',
                                        'value' => $weekTotal
                                    ];
                                    break;

                                case 'page_impressions_unique':

                                    switch($insight['period'])
                                    {
                                        case 'week':

                                            $counts[] = [
                                                'label' => 'Total Reach this week',
                                                'value' => end($insight['values'])['value']
                                            ];

                                            break;

                                        case 'days_28':

                                            $counts[] = [
                                                'label' => 'Total Reach this month',
                                                'value' => end($insight['values'])['value']
                                            ];

                                            break;
                                    }

                                    break;
                            }
                        }

                        break;

                    default:
                        $supportedObject = false;
                        $message = 'Insights only supports pages, please choose a different Facebook Page ID in <a href="'.UrlHelper::getUrl('facebook/settings').'">Facebook’s settings</a>.';
                        FacebookPlugin::log("Insights not available for object type `".$objectType."`, only pages are supported.", LogLevel::Error);

                }

                $report = [
                    'supportedObject' => $supportedObject,
                    'message' => $message,
                    'object' => $object,
                    'objectType' => $objectType,
                    'counts' => $counts,
                ];

                craft()->facebook_cache->set(['insightsReport', $facebookInsightsObjectId], $report);
            }
            else
            {
                throw new Exception("Not authenticated");
            }
        }

        return $report;
    }
}