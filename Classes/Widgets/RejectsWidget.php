<?php

namespace Lemming\MailchimpWidgets\Widgets;

use MailchimpTransactional\ApiClient;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

class RejectsWidget implements WidgetInterface
{
    public function __construct(
        private readonly WidgetConfigurationInterface $configuration,
        private StandaloneView $view,
        private UriBuilder $uriBuilder,
        private ApiClient $apiClient,
        private string $table,
        private string $field
    ) {
    }

    public function renderWidgetContent(): string
    {
        $this->view->setTemplatePathAndFilename('EXT:mailchimp_widgets/Resources/Private/Templates/Rejects.html');
        $items = $this->getItems();
        if (!empty($this->table) && !empty($this->field)) {
            $items = $this->addEditUri($items);
        }
        $this->view->assignMultiple([
            'items' => $items,
            'configuration' => $this->configuration,
        ]);
        return $this->view->render();
    }

    protected function getItems(): array
    {
        $result = $this->apiClient->rejects->list();
        if ($result instanceof \Throwable) {
            throw $result;
        }
        return $result;
    }

    protected function addEditUri($items): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($this->table);
        $queryBuilder->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class));

        $returnUrl = $this->uriBuilder->buildUriFromRoute('dashboard')->__toString();
        foreach ($items as $item) {
            $recordUid = $queryBuilder
                ->select('uid')
                ->from($this->table)
                ->where(
                    $queryBuilder->expr()->eq($this->field, $queryBuilder->createNamedParameter($item->email))
                )->executeQuery()
                ->fetchOne();

            if ($recordUid) {
                $uriParameters = ['edit' => [$this->table => [$recordUid => 'edit']], 'returnUrl' => $returnUrl];
                $item->editUri = $this->uriBuilder->buildUriFromRoute('record_edit', $uriParameters);
            }
        }
        return $items;
    }
}
