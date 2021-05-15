<?php

// Register for hook to show preview of tt_content element of CType="tbscontentelements_textcontent" in page module
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem']['tbscontentelements_textcontent'] =
    \Tbs\TbsContentElements\Hooks\PageLayoutView\NewContentElementPreviewRenderer::class;

/************************************************************************
 * CONTENT ELEMENT ICONS
 ************************************************************************/
if (TYPO3_MODE === 'BE') {
    $icons = [
        'tbscontentelements_textcontent' => 'tbs_contentelements_icon.svg',
    ];

}



/***************
 * Backend
 ***************/
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:tbs_content_elements/Configuration/TsConfig/All.tsconfig">');
