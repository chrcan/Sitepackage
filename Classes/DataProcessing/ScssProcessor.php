<?php

declare(strict_types=1);

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

namespace Rcdesign\Rcdesign\DataProcessing;

use ScssPhp\ScssPhp\Compiler;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class ScssProcessor implements DataProcessorInterface
{
    /**
     * Process data for the content element"My new content element"
     *
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of content Objects
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processsed data
     * @return array the processed data as key/value store
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        if (isset($processorConfiguration['if.']) && !$cObj->checkIf($processorConfiguration['if.'])) {
            return $processedData;
        }

        $sourceFilePath = ExtensionManagementUtility::extPath('rcdesign') . $processorConfiguration['source'];
        $targetFilePath = ExtensionManagementUtility::extPath('rcdesign') . $processorConfiguration['target'];
        $compiler = new Compiler();

        //Compile scss to css
        \file_put_contents($targetFilePath, $compiler->compileString(\file_get_contents($sourceFilePath))->getCss());

        return $processedData;
    }
}
