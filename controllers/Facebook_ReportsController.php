<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace Craft;

class Facebook_ReportsController extends BaseController
{
	// Public Methods
	// =========================================================================

	/**
	 * Returns the insights report
	 *
	 * @return null
	 */
	public function actionGetInsightsReport()
    {
        try
        {
            $report = craft()->facebook_reports->getInsightsReport();
            $this->returnJson($report);
        }
        catch(\Guzzle\Http\Exception\ClientErrorResponseException $e)
        {
            $errorMsg = $e->getMessage();

            $response = $e->getResponse();
            $data = json_decode($response->getBody(), true);

            if(!empty($data['error']['message']))
            {
                $errorMsg = $data['error']['message'];
            }

            $this->returnErrorJson($errorMsg);
        }
        catch(\Exception $e)
        {
            if(method_exists($e, 'getErrors'))
            {
                $errors = $e->getErrors();

                if(isset($errors[0]['message']))
                {
                    $this->returnErrorJson(Craft::t($errors[0]['message']));
                }
            }

            $this->returnErrorJson($e->getMessage());
        }
    }
}