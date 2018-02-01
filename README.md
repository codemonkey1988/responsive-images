# Responsive images for TYPO3 CMS
This extension adds responsive images support for fluid_styled_content rendering using the media ViewHelper.

## Information
This extension is based on the fact, that the frontend rendering used some kind of 
CSS grid system (may be provided by CSS frameworks like bootstrap or foundation). 
The columns of this has to specify the width of an image. The image should run 
over 100% of the column width.

To render the image inside columns, there are some extensions that can be used like 
gridelements, fluidcontent or multicolumns.

This extension checks, what column width is used in the frontend to render to image.
Then it generates a picture tag width some versions of that image based on the column
width.

See part usage to know, how this works.

## Usage
 
There are some predefined configs:
- **default** (for images over full content width)
- **half** (for images over half content width)
- **third** (for images over third content width)
- **quarter** (for images over quarter content width)
- **two-thirds** (for images over 2/3 content width)
- **three-quarter** (for images over 3/4 content width)

Before an image is rendered via MediaViewHelper, a registry variable width the key 
**IMAGE_VARIANT_KEY** must be set. The value should be one of the config keys.
This can be done width the ViewHelpers

`\Codemonkey1988\ResponsiveImages\ViewHelpers\LoadRegisterViewHelper`

After the image is rendered, registry stack should be restored. That can be done with

`\Codemonkey1988\ResponsiveImages\ViewHelpers\RestoreRegisterViewHelper`

### Adding new configs
New image variant configs can be added via ext_localconf.php.

Example:

```
/** @var \Codemonkey1988\ResponsiveImages\Resource\Rendering\PictureImageVariant $half */
$half = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Codemonkey1988\ResponsiveImages\Resource\Rendering\PictureImageVariant::class, 'half');
$half->setDefaultWidth($desktopWidth / 2)
     ->addSourceConfig('(max-width: 40em)',
         array(
             '1x' => array('width' => $smartphoneWidth, 'quality' => 65),
             '2x' => array('width' => $smartphoneWidth * 2, 'quality' => 40)
         ),
         '[optional cropping variant key for TYPO3 v8]'
     )
     ->addSourceConfig('(min-width: 64.0625em)',
         array(`
             '1x' => array('width' => $desktopWidth / 2),
             '2x' => array('width' => $desktopWidth * 2 / 2, 'quality' => 80)
         ),
         '[optional cropping variant key for TYPO3 v8]'
     )
     ->addSourceConfig('(min-width: 40.0625em)',
         array(
             '1x' => array('width' => $tabletWidth / 2, 'quality' => 80),
             '2x' => array('width' => $tabletWidth * 2 / 2, 'quality' => 60)
         ),
         '[optional cropping variant key for TYPO3 v8]'
     );
```