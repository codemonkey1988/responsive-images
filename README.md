# Responsive images for TYPO3 CMS
This extension adds responsive images support for fluid templates using the MediaViewHelper.

## Information
This extension is based on the fact, that the integrator forces image sizes that 
the content editor may not change. An integrator can specify image configurations
that are used in fluid templates.

This extension will also respect cropping variants (introduced in TYPO3 v8) and will handle
different jpeg qualities for each image.

## Installation

1. `composer require codemonkey1988/responsive-images`
2. Activate extension in extension manager
3. Add TypoScript template *Responsive images settings*
4. Optional: Add TypoScript template *Responsive images default configuration (optional)*


## Configuration

This extension does not work out of the box. 
Before an image is rendered via MediaViewHelper, a registry variable with the key 
**IMAGE_VARIANT_KEY** must be set. The value should be one of the config keys (see below).
This can be done by using the LoadRegisterViewHelper.
The MediaViewHelper should be placed inside this ViewHelper. By defining a key, 
a picture tag will be generated using the config that belongs to that key.

Example:

```
<r:loadRegister key="IMAGE_VARIANT_KEY" value="default">
    <f:media file="{file}" />
</r:loadRegister>
```

This example will create a picture tag like the following one 
(using the default configuration)

```
<picture>
    <source media="(max-width: 40em)" srcset="320x180-q65.jpg 1x, 640x360-q40.jpg 2x" />
    <source media="(min-width: 64.0625em)" srcset="1920x1080-q80.jpg 1x" />
    <source media="(min-width: 40.0625em)" srcset="1024x576-q80.jpg 1x, 2048x1152-q40.jpg 2x" />
    <img src="1920x1080.jpg" alt="Example alternative" width="1920" height="1080" />
</picture>
```

### Predefined configurations

There are some predefined configs, that are available if the corresponding 
TypoScript is included:
- **default** (for images over full content width)
- **half** (for images over half content width)
- **third** (for images over third content width)
- **quarter** (for images over quarter content width)
- **two-thirds** (for images over 2/3 content width)
- **three-quarter** (for images over 3/4 content width)

### Adding new configs

There are two ways of adding responsive image configuration to TYPO3.

#### TypoScript
New image variant configs can be added via TypoScript

Example:

```
plugin.tx_responsiveimages.settings.configuration {
    default {
        defaultWidth = 1920
        defaultHeight =
        sources {
            smartphone {
                media = (max-width: 40em)
                croppingVariantKey = default
                sizes {
                    1x {
                        width = 320
                        height =
                        quality = 65
                    }
                    2x {
                        width = 640
                        height =
                        quality = 40
                    }
                }
            }
            desktop {
                media = (min-width: 64.0625em)
                croppingVariantKey = default
                sizes {
                    1x {
                        width = 1920
                        height =
                        quality = 80
                    }

                }
            }
            tablet {
                media = (min-width: 40.0625em)
                croppingVariantKey = default
                sizes {
                    1x {
                        width = 1024
                        height =
                        quality = 80
                    }
                    2x {
                        width = 2048
                        height =
                        quality = 40
                    }
                }
            }
        }
    }
}
```

#### PHP

You can add image configuration for your ext_localconf.php

Example:
```
/** @var \Codemonkey1988\ResponsiveImages\Resource\Rendering\PictureImageVariant $half */
$half = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Codemonkey1988\ResponsiveImages\Resource\Service\PictureImageVariant::class, 'half');
$half->setDefaultWidth($desktopWidth / 2)
     ->addSourceConfig('(max-width: 40em)',
         [
             '1x' => array('width' => $smartphoneWidth, 'quality' => 65),
             '2x' => array('width' => $smartphoneWidth * 2, 'quality' => 40)
         ],
         '[optional cropping variant key for TYPO3 v8]'
     )
     ->addSourceConfig('(min-width: 64.0625em)',
         [
             '1x' => array('width' => $desktopWidth / 2),
             '2x' => array('width' => $desktopWidth * 2 / 2, 'quality' => 80)
         ],
         '[optional cropping variant key for TYPO3 v8]'
     )
     ->addSourceConfig('(min-width: 40.0625em)',
         [
             '1x' => array('width' => $tabletWidth / 2, 'quality' => 80),
             '2x' => array('width' => $tabletWidth * 2 / 2, 'quality' => 60)
         ],
         '[optional cropping variant key for TYPO3 v8]'
     );
// Add variant to registry.
$registry = PictureVariantsRegistry::getInstance();
$registry->registerImageVariant($half);
```

### Additional settings

#### Disable extension

There are two ways to disable this extension. 

**By TypoScript constants**<br>`plugin.tx_responsiveimages.settings.enabled = 0`

**By environment variable**<br>
`RESPONSIVE_IMAGES_ENABLED=0`

#### Disable image processing

Image processing can be disabled. When done, all images rendered as picture 
tags will not be processed. Instead, the original files are used.
This can be used during development to speed up page loading.
Keep in mind, that also cropping  will not work when processing is disabled. 

**By TypoScript constants**<br>`plugin.tx_responsiveimages.settings.processing = 0`

**By environment variable**<br>
`RESPONSIVE_IMAGES_PROCESSING=0`

### lazySizes configuration

This also does not work out of the box. 
The templates "Responsive images settings" and "Responsive images lazySizes configuration (optional)" must be included.
Before an image is rendered a registry variable with the key **IMAGE_VARIANT_KEY** and the value **lazysizes** must be set.
Additionally extra classes and attributes must be set.  


Example lazySizes:

```
<html xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers"
      xmlns:r="http://typo3.org/ns/Codemonkey1988/ResponsiveImages/ViewHelpers"
      data-namespace-typo3-fluid="true">
<r:loadRegister key="IMAGE_VARIANT_KEY" value="lazysizes">
    <f:media
            file="{file}"
            additionalAttributes="{data-sizes: 'auto'}"
            class="lazyload"
            alt="{file.alternative}"
            title="{file.title}"
    />
</r:loadRegister>
</html>
```

The lazySizes configuration will create a picture tag like the following one:

```
<picture>
    <source
        media="(-webkit-min-device-pixel-ratio: 1.9), (min-resolution: 1.9dppx)"
        data-srcset="image-w2048-q40.jpg w2048,
            image-w1536-q40.jpg 1536w,
            image-w1364-q40.jpg 1364w,
            image-w1024-q40.jpg 1024w,
            image-w682-q40.jpg 682w,
            image-w512-q40.jpg 512w" />
    <source
        data-srcset="image-w1920-q80.jpg 1920w,
            image-w1440-q80.jpg 1440w,
            image-w1280-q80.jpg 1280w,
            image-w1024-q80.jpg 1024w,
            image-w768-q80.jpg 768w,
            image-w682-q80.jpg 682w,
            image-w512-q80.jpg 512w,
            image-w341-q80.jpg 341w,
            image-w256-q80.jpg 256w" />
    <img
        data-sizes="auto"
        class="lazyload"
        src="data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20viewBox%3D'0%200%202048%201152'%2F%3E"
        data-src="image-w256.jpg"
        width="2048"
        height="1152"
        alt="Example alternative" />
</picture>
```
