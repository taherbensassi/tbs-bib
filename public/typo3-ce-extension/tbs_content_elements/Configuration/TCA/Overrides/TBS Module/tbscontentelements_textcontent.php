<?php

/** Text-Content-Element  **/
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    array(
        'Text Content Element',
        'tbscontentelements_textcontent',
        'tbscontentelements_textcontent'
    ),
    'CType',
    'tbs_content_elements'
);

// Configure the default backend fields for the content element
$GLOBALS['TCA']['tt_content']['types']['tbscontentelements_textcontent'] = array(
    'showitem' => '
      --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
         --palette--;;general,
         header;LLL:EXT:tbs_content_elements/Resources/Private/Language/locallang.xlf:tt_content.header,
         bodytext;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:bodytext_formlabel,
   ',
    'columnsOverrides' => [
        'bodytext' => [
            'config' => [
                'type' => 'text',
                'enableRichtext' => true
            ]
        ],
    ]
);