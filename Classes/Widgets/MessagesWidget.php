<?php

namespace Lemming\MailchimpWidgets\Widgets;

use MailchimpTransactional\ApiClient;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

class MessagesWidget implements WidgetInterface
{
    public function __construct(
        private readonly WidgetConfigurationInterface $configuration,
        private StandaloneView $view,
        private ApiClient $apiClient
    ) {
    }

    public function renderWidgetContent(): string
    {
        $this->view->setTemplatePathAndFilename('EXT:mailchimp_widgets/Resources/Private/Templates/Messages.html');
        $this->view->assignMultiple([
            'items' => $this->getItems(),
            'configuration' => $this->configuration,
        ]);
        return $this->view->render();
    }

    protected function getItems(): array
    {
        $result = $this->apiClient->messages->search(['limit' => 1000]);
        if ($result instanceof \Throwable) {
            throw $result;
        }
        return $result;
    }
}
