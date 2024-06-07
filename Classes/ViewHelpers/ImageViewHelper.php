<?php

declare(strict_types=1);

namespace Rcdesign\Rcdesign\ViewHelpers;

use InvalidArgumentException;
use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Exception;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Frontend\Imaging\GifBuilder;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use UnexpectedValueException;

final class ImageViewHelper extends AbstractTagBasedViewHelper
{
    /**
     * @var string
     */
    protected $tagName = 'img';

    protected ImageService $imageService;

    public function __construct()
    {
        parent::__construct();
        $this->imageService = GeneralUtility::makeInstance(ImageService::class);
    }

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();
        $this->registerTagAttribute('alt', 'string', 'Specifies an alternate text for an image', false);
        $this->registerTagAttribute('ismap', 'string', 'Specifies an image as a server-side image-map. Rarely used. Look at usemap instead', false);
        $this->registerTagAttribute('longdesc', 'string', 'Specifies the URL to a document that contains a long description of an image', false);
        $this->registerTagAttribute('usemap', 'string', 'Specifies an image as a client-side image-map', false);
        $this->registerTagAttribute('loading', 'string', 'Native lazy-loading for images property. Can be "lazy", "eager" or "auto"', false);
        $this->registerTagAttribute('decoding', 'string', 'Provides an image decoding hint to the browser. Can be "sync", "async" or "auto"', false);

        $this->registerArgument('src', 'string', 'a path to a file, a combined FAL identifier or an uid (int). If $treatIdAsReference is set, the integer is considered the uid of the sys_file_reference record. If you already got a FAL object, consider using the $image parameter instead', false, '');
        $this->registerArgument('treatIdAsReference', 'bool', 'given src argument is a sys_file_reference record', false, false);
        $this->registerArgument('image', 'object', 'a FAL object (\\TYPO3\\CMS\\Core\\Resource\\File or \\TYPO3\\CMS\\Core\\Resource\\FileReference)');
        $this->registerArgument('crop', 'string|bool|array', 'overrule cropping of image (setting to FALSE disables the cropping set in FileReference)');
        $this->registerArgument('cropVariant', 'string', 'select a cropping variant, in case multiple croppings have been specified or stored in FileReference', false, 'default');
        $this->registerArgument('fileExtension', 'string', 'Custom file extension to use');

        $this->registerArgument('width', 'string', 'width of the image. This can be a numeric value representing the fixed width of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.');
        $this->registerArgument('height', 'string', 'height of the image. This can be a numeric value representing the fixed height of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.');
        $this->registerArgument('minWidth', 'int', 'minimum width of the image');
        $this->registerArgument('minHeight', 'int', 'minimum height of the image');
        $this->registerArgument('maxWidth', 'int', 'maximum width of the image');
        $this->registerArgument('maxHeight', 'int', 'maximum height of the image');
        $this->registerArgument('absolute', 'bool', 'Force absolute URL', false, false);
        $this->registerArgument('gifBuilderEffect', 'string', 'e.g. blur=20 | gamma=1.3', false, '');
    }

    /**
     * Resizes a given image (if required) and renders the respective img tag.
     *
     * @see https://docs.typo3.org/typo3cms/TyposcriptReference/ContentObjects/Image/
     * @throws Exception
     */
    public function render(): string
    {
        $src = (string)$this->arguments['src'];
        if (($src === '' && $this->arguments['image'] === null) || ($src !== '' && $this->arguments['image'] !== null)) {
            throw new Exception($this->getExceptionMessage('You must either specify a string src or a File object.'), 1382284106);
        }

        if ((string)$this->arguments['fileExtension'] && !GeneralUtility::inList($GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'], (string)$this->arguments['fileExtension'])) {
            throw new Exception(
                $this->getExceptionMessage(
                    'The extension ' . $this->arguments['fileExtension'] . ' is not specified in $GLOBALS[\'TYPO3_CONF_VARS\'][\'GFX\'][\'imagefile_ext\']'
                        . ' as a valid image file extension and can not be processed.',
                ),
                1618989190
            );
        }

        try {
            $gifBuilderEffect = $this->arguments['gifBuilderEffect'];

            $image = $this->imageService->getImage($src, $this->arguments['image'], (bool)$this->arguments['treatIdAsReference']);
            $cropString = $this->arguments['crop'];
            if ($cropString === null && $image->hasProperty('crop') && $image->getProperty('crop')) {
                $cropString = $image->getProperty('crop');
            }

            // CropVariantCollection needs a string, but this VH could also receive an array
            if (is_array($cropString)) {
                $cropString = json_encode($cropString);
            }

            $cropVariantCollection = CropVariantCollection::create((string)$cropString);
            $cropVariant = $this->arguments['cropVariant'] ?: 'default';
            $cropArea = $cropVariantCollection->getCropArea($cropVariant);
            $processingInstructions = [
                'width' => $this->arguments['width'],
                'height' => $this->arguments['height'],
                'minWidth' => $this->arguments['minWidth'],
                'minHeight' => $this->arguments['minHeight'],
                'maxWidth' => $this->arguments['maxWidth'],
                'maxHeight' => $this->arguments['maxHeight'],
                'crop' => $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($image),
            ];
            if (!empty($this->arguments['fileExtension'] ?? '')) {
                $processingInstructions['fileExtension'] = $this->arguments['fileExtension'];
            }
            $processedImage = $this->imageService->applyProcessingInstructions($image, $processingInstructions);

            if ($gifBuilderEffect) {
                $conf = [
                    1 => 'IMAGE',
                    '1.' => [
                        'file' => $processedImage->getForLocalProcessing(false),
                    ],
                    10 => 'TEXT',
                    '10.' => [
                        'offset' => '20,30',
                        #'align' => 'left',
                        'fontFile' => 'EXT:rcdesign/Resources/Public/Fonts/roboto/Roboto-Bold.ttf',
                        'text' => '©IFBBAustria.at',
                        'fontSize' => '14',
                        'fontColor' => '#ffffff',
                        'antiAlias' => '1',
                    ],
                    20 => 'EFFECT',
                    '20.' => [
                        'value' => $gifBuilderEffect
                    ],
                ];
                $conf['XY'] = '[1.w],[1.h]';
                /** @var GifBuilder $gifCreator */
                $gifCreator = GeneralUtility::makeInstance(GifBuilder::class);
                $gifCreator->start($conf, []);
                $imageUri = $gifCreator->gifBuild()->getPublicUrl();
            } else {
                $imageUri = $this->imageService->getImageUri($processedImage, $this->arguments['absolute']);
            }

            if (!$this->tag->hasAttribute('data-focus-area')) {
                $focusArea = $cropVariantCollection->getFocusArea($cropVariant);
                if (!$focusArea->isEmpty()) {
                    $this->tag->addAttribute('data-focus-area', $focusArea->makeAbsoluteBasedOnFile($image));
                }
            }
            $this->tag->addAttribute('src', $imageUri);
            $this->tag->addAttribute('width', $processedImage->getProperty('width'));
            $this->tag->addAttribute('height', $processedImage->getProperty('height'));

            if (is_string($this->arguments['alt'] ?? false) && $this->arguments['alt'] === '') {
                // In case the "alt" attribute is explicitly set to an empty string, respect
                // this to allow excluding it from screen readers, improving accessibility.
                $this->tag->addAttribute('alt', '');
            } elseif (empty($this->arguments['alt'])) {
                // The alt-attribute is mandatory to have valid html-code, therefore use "alternative" property or empty
                $this->tag->addAttribute('alt', $image->hasProperty('alternative') ? $image->getProperty('alternative') : '');
            }
            // Add title-attribute from property if not already set and the property is not an empty string
            $title = (string)($image->hasProperty('title') ? $image->getProperty('title') : '');
            if (empty($this->arguments['title']) && $title !== '') {
                $this->tag->addAttribute('title', $title);
            }
        } catch (ResourceDoesNotExistException $e) {
            // thrown if file does not exist
            throw new Exception($this->getExceptionMessage($e->getMessage()), 1509741911, $e);
        } catch (\UnexpectedValueException $e) {
            // thrown if a file has been replaced with a folder
            throw new Exception($this->getExceptionMessage($e->getMessage()), 1509741912, $e);
        } catch (\InvalidArgumentException $e) {
            // thrown if file storage does not exist
            throw new Exception($this->getExceptionMessage($e->getMessage()), 1509741914, $e);
        }
        return $this->tag->render();
    }

    protected function getExceptionMessage(string $detailedMessage): string
    {
        /** @var RenderingContext $renderingContext */
        $renderingContext = $this->renderingContext;
        $request = $renderingContext->getRequest();
        if ($request instanceof RequestInterface) {
            $currentContentObject = $request->getAttribute('currentContentObject');
            if ($currentContentObject instanceof ContentObjectRenderer) {
                return sprintf('Unable to render image tag in "%s": %s', $currentContentObject->currentRecord, $detailedMessage);
            }
        }
        return "Unable to render image tag: $detailedMessage";
    }
}
