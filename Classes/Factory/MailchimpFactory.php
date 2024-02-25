<?php

namespace Lemming\MailchimpWidgets\Factory;

use MailchimpTransactional\ApiClient;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MailchimpFactory
{
    public static function getApiClient(): ApiClient
    {
        $apiKey = GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('mailchimp_widgets', 'apiKey');
        $client = new ApiClient();
        $client->setApiKey($apiKey);
        return $client;
    }
}
