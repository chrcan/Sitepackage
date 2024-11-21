<?php

declare(strict_types=1);

/*
 * (c) 2025 rc design visual concepts (rc-design.at)
 * _________________________________________________
 * The TYPO3 project - inspiring people to share!
 * _________________________________________________
 */

namespace Rcdesign\Rcdesign\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * Phone link view helper.
 * Generates a phone link.
 *
 * = Examples
 *
 * <code title="basic phone link">
 * <f:link.phone phone="+49 123 456 7890" />
 * </code>
 * <output>
 * <a href="tel:+49 123 456 7890">+49 123 456 7890</a>
 * </output>
 *
 * <code title="Phone link with custom linktext">
 * <f:link.phone phone="+49 123 456 7890">some custom content</f:link.phone>
 * </code>
 * <output>
 * <a href="tel:+49 123 456 7890">some custom content</a>
 * </output>
 */
class PhoneViewHelper extends AbstractTagBasedViewHelper
{
    /**
     * @var string
     */
    protected $tagName = 'a';

    /**
     * Arguments initialization.
     */
    /*
   public function initializeArguments()
   {
      parent::initializeArguments();
      $this->registerUniversalTagAttributes();
      $this->registerTagAttribute('name', 'string', 'Specifies the name of an anchor');
      $this->registerTagAttribute('rel', 'string', 'Specifies the relationship between the current document and the linked document');
      $this->registerTagAttribute('rev', 'string', 'Specifies the relationship between the linked document and the current document');
      $this->registerTagAttribute('target', 'string', 'Specifies where to open the linked document');
      $this->registerArgument('phone', 'string', 'Telefonnummer', true);
   }
   */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();
        $this->registerTagAttribute('alt', 'string', 'Alternative Text for the image');
        $this->registerArgument('phone', 'string', 'Telefonnummer', true);
    }

    /**
     * @param string $phone the phone number to be turned into a link
     *
     * @return string Rendered phone link
     */
    public function render()
    {
        $linkHref = 'tel:' . $this->arguments['phone'];
        $linkText = $this->arguments['phone'];

        $tagContent = $this->renderChildren();
        if ($tagContent !== null) {
            $linkText = $tagContent;
        }
        $this->tag->setContent($linkText);
        $this->tag->addAttribute('href', $linkHref);
        $this->tag->forceClosingTag(true);
        return $this->tag->render();
    }
}
