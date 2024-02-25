<?php

return [
    'mailchimpWidgets' => [
        'title' => 'Mailchimp',
        'description' => 'LLL:EXT:mailchimp_widgets/Resources/Private/Language/locallang.xlf:dashboard.mailchimp_widgets.description',
        'iconIdentifier' => 'actions-envelope',
        'defaultWidgets' => ['mailchimpRejects', 'mailchimpMessages'],
        'showInWizard' => true,
    ],
];