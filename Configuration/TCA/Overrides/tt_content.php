<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/*
 * Copyright (C) 2023 rc design visual concepts (rc-design.at)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * The TYPO3 project - inspiring people to share!
 */

defined('TYPO3') or die();

// Eigenes CType erstellen

// Adds the content element to the "Type" dropdown
ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        // title
        'label' => 'Erstes Custom Element',
        // plugin signature: extkey_identifier
        'value' => 'rcdesign9_erstescustomelement',
        // icon identifier
        'icon' => 'content-webhook',
        // make one group
        // 'group' => 'common',
        // description
        'description' => 'LLL:EXT:rcdesign/Resources/Private/Language/Backend.xlf:rcdesign9_erstescustomelement_description',
    ],
    'textmedia',
    'after'
);

// Adds the content element icon to TCA typeicon_classes
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['rcdesign9_erstescustomelement'] = 'content-webhook';

// Configure the default backend fields for the content element
$GLOBALS['TCA']['tt_content']['types']['rcdesign9_erstescustomelement'] = [
    'showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            header;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_formlabel,
            row_items,
            header_link,
            bodytext;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:bodytext_formlabel,
            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.media,
            assets,
            --palette--;;imagelinks,

            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
        ',

    'columnsOverrides' => [
        'bodytext' => [
            'config' => [
                'enableRichtext' => true,
                'richtextConfiguration' => 'BootstrapRTE',
            ],
        ],
    ],
];

ExtensionManagementUtility::addTCAcolumns(
    'tt_content',
    [
        'row_items' => [
            'exclude' => 0,
            'label' => 'Cards Menge',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 1, 'value' => 'row-cols-1 row-cols-md-1'],
                    ['label' => 2, 'value' => 'row-cols-1 row-cols-md-2'],
                    ['label' => 3, 'value' => 'row-cols-1 row-cols-md-3'],
                    ['label' => 4, 'value' => 'row-cols-1 row-cols-md-3'],
                ],
                'size' => 1,
                'maxitems' => 1,
            ],
        ],
    ],
);
// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
//     'tt_content',
//     'row_items',
//     '',
//     'after:header'
// );

// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('rccard_group_item');

/// Neues CE Element Card mit Inline Funktion

ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        // title
        'label' => 'RC Card',
        // plugin signature: extkey_identifier
        'value' => 'rcdesign9_rccard',
        // icon identifier
        'icon' => 'content-target',
        // make one group
        // 'group' => 'common',
        // description
        'description' => 'LLL:EXT:rcdesign/Resources/Private/Language/locallang.xlf:rcdesign9_rccard_description',
    ],
    'rcdesign9_erstescustomelement',
    'after'
);

// Adds the content element icon to TCA typeicon_classes
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['rcdesign9_rccard'] = 'content-target';

// Configure the default backend fields for the content element
$GLOBALS['TCA']['tt_content']['types']['rcdesign9_rccard'] = [
    'showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            rccard_group_item,
            bodytext;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:bodytext_formlabel,
        ',

    'columnsOverrides' => [
        'bodytext' => [
            'config' => [
                'enableRichtext' => true,
                'richtextConfiguration' => 'BootstrapRTE',
            ],
        ],
    ],
];
// Configure element type
$GLOBALS['TCA']['tt_content']['types']['rcdesign9_rccard'] = array_replace_recursive(
    $GLOBALS['TCA']['tt_content']['types']['rcdesign9_rccard'],
    [
        'showitem' => '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
        header;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_formlabel,
            row_items,
            rccard_group_item,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,
            --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
        ',
    ]
);

// Register fields
$GLOBALS['TCA']['tt_content']['columns'] = array_replace_recursive(
    $GLOBALS['TCA']['tt_content']['columns'],
    [
        'rccard_group_item' => [
            'label' => 'LLL:EXT:rcdesign/Resources/Private/Language/Backend.xlf:rccard_group_item',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'rccard_group_item',
                'foreign_field' => 'tt_content',
                'appearance' => [
                    'useSortable' => true,
                    'showSynchronizationLink' => true,
                    'showAllLocalizationLink' => true,
                    'showPossibleLocalizationRecords' => true,
                    'expandSingle' => true,
                    'enabledControls' => [
                        'localize' => true,
                    ],
                ],
                'behaviour' => [
                    'mode' => 'select',
                ],
            ],
        ],
    ]
);

/// Neues CE Element Accordion mit Inline Funktion

ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        // title
        'label' => 'RC Accordion',
        // plugin signature: extkey_identifier
        'value' => 'rcdesign9_rcaccordion',
        // icon identifier
        'icon' => 'module-list',
        // make one group
        // 'group' => 'common',
        // description
        'description' => 'LLL:EXT:rcdesign/Resources/Private/Language/locallang.xlf:rcdesign9_rcaccordion_description',
    ],
    'rcdesign9_rccard',
    'after'
);
// Adds the content element icon to TCA typeicon_classes
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['rcdesign9_rcaccordion'] = 'module-list';
// Configure the default backend fields for the content element
$GLOBALS['TCA']['tt_content']['types']['rcdesign9_rcaccordion'] = [
    'showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            header;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_formlabel,
            rcaccordion_accordion_item,
        ',

    'columnsOverrides' => [
        'bodytext' => [
            'config' => [
                'enableRichtext' => true,
                'richtextConfiguration' => 'BootstrapRTE',
            ],
        ],
    ],
];
// Register fields
$GLOBALS['TCA']['tt_content']['columns'] = array_replace_recursive(
    $GLOBALS['TCA']['tt_content']['columns'],
    [
        'rcaccordion_accordion_item' => [
            'label' => 'LLL:EXT:rcdesign/Resources/Private/Language/Backend.xlf:accordion_item',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'rcaccordion_accordion_item',
                'foreign_field' => 'tt_content',
                'appearance' => [
                    'useSortable' => true,
                    'showSynchronizationLink' => true,
                    'showAllLocalizationLink' => true,
                    'showPossibleLocalizationRecords' => true,
                    'expandSingle' => true,
                    'enabledControls' => [
                        'localize' => true,
                    ],
                ],
                'behaviour' => [
                    'mode' => 'select',
                ],
            ],
        ],
    ]
);

// // Add flexForms for content element configuration
// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
//     '*',
//     'FILE:EXT:rcdesign/Configuration/FlexForms/Accordion.xml',
//     'rcaccordion'
// );
