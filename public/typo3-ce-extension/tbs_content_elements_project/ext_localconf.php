<?php
/************************************************************************
 * CONTENT ELEMENT ICONS
 ************************************************************************/
if (TYPO3_MODE === 'BE') {
    $icons = [

    ];
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    foreach ($icons as $identifier => $path) {
        $iconRegistry->registerIcon(
            $identifier,
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:tbs_content_elements/Resources/Public/Icons/ContentElements/'.$path]
        );
    }
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem']['tbs_content_elements'] =
    Tbs\TbsContentElements\Hooks\PageLayoutView\PageLayoutViewDrawItem::class;

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:tbs_content_elements/Configuration/TsConfig/BackendPreview.tsconfig">'
);


/***************
 * Backend
 ***************/
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:tbs_content_elements/Configuration/TsConfig/All.tsconfig">'
);

