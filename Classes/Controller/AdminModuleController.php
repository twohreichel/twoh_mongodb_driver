<?php

declare(strict_types=1);

namespace TWOH\TwohMongodbDriver\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TWOH\TwohMongodbDriver\Adapter\MongodbConnectionPoolAdapter;
use TWOH\TwohMongodbDriver\Utility\CoreUtility;
use TYPO3\CMS\Backend\Attribute\Controller;
use TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\Components\Buttons\ButtonInterface;
use TYPO3\CMS\Backend\Template\Components\Buttons\DropDown\DropDownItem;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

#[Controller]
final class AdminModuleController
{
    /**
     * @param ModuleTemplateFactory $moduleTemplateFactory
     * @param IconFactory $iconFactory
     */
    public function __construct(
        protected ModuleTemplateFactory $moduleTemplateFactory,
        protected IconFactory $iconFactory,
        protected MongodbConnectionPoolAdapter $mongodbConnectionPoolAdapter,
        private readonly UriBuilder $uriBuilder,
        private readonly PageRenderer $pageRenderer,
    ) {}

    /**
     * @param ServerRequestInterface $request
     * @throws RouteNotFoundException
     * @return ResponseInterface
     */
    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        $languageService = $this->getLanguageService();

        $moduleTemplate = $this->moduleTemplateFactory->create($request);

        $this->setDocHeader(
            $moduleTemplate,
        );

        $title = $languageService->sL('LLL:EXT:twoh_mongodb_driver/Resources/Private/Language/AdminModule/locallang_mod.xlf:mlang_tabs_tab');

        $routing = $request->getAttribute('routing');
        $route = $routing->getRoute();
        $routeActionIdentifier = CoreUtility::toUpperCamelCase($route->getOptions()['_identifier']);

        if ($routeActionIdentifier === 'AdminMongodb') {
            $moduleTemplate->setTitle(
                $title,
                $languageService->sL('LLL:EXT:twoh_mongodb_driver/Resources/Private/Language/AdminModule/locallang.xlf:module.menu.index'),
            );
            return $this->indexAction(
                $request,
                $moduleTemplate,
                $routeActionIdentifier,
            );
        }

        if ($routeActionIdentifier === 'BrowseCollection') {
            $moduleTemplate->setTitle(
                $title,
                $languageService->sL('LLL:EXT:twoh_mongodb_driver/Resources/Private/Language/AdminModule/locallang.xlf:module.menu.browseCollection'),
            );
            return $this->browseCollectionAction(
                $request,
                $moduleTemplate,
                $routeActionIdentifier,
            );
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @param ModuleTemplate $view
     * @param string $routeActionIdentifier
     * @return ResponseInterface
     */
    public function indexAction(
        ServerRequestInterface $request,
        ModuleTemplate $view,
        string $routeActionIdentifier,
    ): ResponseInterface {
        $languageService = $this->getLanguageService();
        //
        //        $this->pageRenderer->loadJavaScriptModule('@twoh/mongodb-driver/lib/chart.min.js');
        //        $this->pageRenderer->loadJavaScriptModule('@twoh/mongodb-driver/custom/index.view.bar.chart.js');

        $view->assignMultiple(
            [
                'headline' => $languageService->sL('LLL:EXT:twoh_mongodb_driver/Resources/Private/Language/AdminModule/locallang.xlf:module.menu.index'),
                'routeActionIdentifier' => $routeActionIdentifier,
            ],
        );

        return $view->renderResponse('AdminModule/Index');
    }

    /**
     * @param ServerRequestInterface $request
     * @param ModuleTemplate $view
     * @param string $routeActionIdentifier
     * @return ResponseInterface
     */
    public function browseCollectionAction(
        ServerRequestInterface $request,
        ModuleTemplate $view,
        string $routeActionIdentifier,
    ): ResponseInterface {
        $collectionRecordsCount = 0;
        $languageService = $this->getLanguageService();
        $routing = $request->getAttribute('routing');

        //        $this->pageRenderer->loadJavaScriptModule('@twoh/mongodb-driver/lib/chart.min.js');
        //        $this->pageRenderer->loadJavaScriptModule('@twoh/mongodb-driver/custom/browse.collection.view.bar.chart.js');

        if (isset($routing->getArguments()['collectionName'])) {
            $collectionRecordsCount = $this->mongodbConnectionPoolAdapter->getConnectionPool()->countDocuments(
                $routing->getArguments()['collectionName'],
                [],
                [
                    'limit' => null,
                ],
            );
        }

        $view->assignMultiple(
            [
                'headline' => $languageService->sL(
                    'LLL:EXT:twoh_mongodb_driver/Resources/Private/Language/AdminModule/locallang.xlf:module.menu.browseCollection',
                ) . ': <strong>' . strtoupper($routing->getArguments()['collectionName']) . '</strong>',
                'routeActionIdentifier' => $routeActionIdentifier,
                'collectionName' => $routing->getArguments()['collectionName'],
                'collectionRecordsCount' => $collectionRecordsCount,
            ],
        );

        return $view->renderResponse('AdminModule/Index');
    }

    /**
     * @param ModuleTemplate $moduleTemplate
     * @throws RouteNotFoundException
     */
    private function setDocHeader(
        ModuleTemplate $moduleTemplate,
    ): void {
        $buttonBar = $moduleTemplate->getDocHeaderComponent()->getButtonBar();

        $buttonBar->addButton(
            $this->createCollectionDropdown($buttonBar),
            ButtonBar::BUTTON_POSITION_RIGHT,
            2,
        );
    }

    /**
     * @throws RouteNotFoundException
     */
    private function createCollectionDropdown(
        ButtonBar $buttonBar,
    ): ButtonInterface {
        $collectionList = $this->mongodbConnectionPoolAdapter->getConnectionPool()->listCollections();

        $dropDownButton = $buttonBar->makeDropDownButton()
            ->setLabel('Collections')
            ->setShowLabelText(true)
            ->setTitle('Collections')
            ->setIcon($this->iconFactory->getIcon('mongodb-icon'));

        // add index dropdown value
        $dropDownButton->addItem(
            GeneralUtility::makeInstance(DropDownItem::class)
                ->setLabel('index')
                ->setHref((string)$this->uriBuilder->buildUriFromRoute(
                    'admin_mongodb',
                    [],
                ))
                ->setTitle('index' . ' ' . 'collection'),
        );

        foreach ($collectionList as $collectionInfo) {
            $dropDownButton->addItem(
                GeneralUtility::makeInstance(DropDownItem::class)
                    ->setLabel($collectionInfo['name'])
                    ->setHref((string)$this->uriBuilder->buildUriFromRoute(
                        'browse_collection',
                        ['collectionName' => $collectionInfo['name']],
                    ))
                    ->setTitle($collectionInfo['name'] . ' ' . $collectionInfo['type']),
            );
        }

        return $dropDownButton;
    }

    /**
     * @param ModuleTemplate $moduleTemplate
     * @param string $icon
     * @param string $uri
     * @param string $title
     * @param string $label
     */
    private function createLinkButton(
        ModuleTemplate $moduleTemplate,
        string $icon,
        string $uri,
        string $title,
        string $label,
    ): void {
        $buttonBar = $moduleTemplate->getDocHeaderComponent()->getButtonBar();

        $list = $buttonBar->makeLinkButton()
            ->setHref($uri)
            ->setTitle($title)
            ->setShowLabelText($label)
            ->setIcon($this->iconFactory->getIcon($icon, Icon::SIZE_SMALL));

        $buttonBar->addButton($list);
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }

    /**
     * @return BackendUserAuthentication
     */
    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
