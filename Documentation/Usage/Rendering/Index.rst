.. _rendering:

==================
Frontend Rendering
==================

After variants are provided using TypoScript,
the frontend rendering needs to be configured.

This extension provides a partial, that can be
used for rendering image as picture tags.

How are variants chosen
=======================

Configured variants can be provided by using the extensions ViewHelpers.
When no variant is given, the extension tries to get the current variant
from the TYPO3 register stack. It is looking for the key `IMAGE_VARIANT_KEY`.
When it is defined, its value is used as a variant key.
When no valid key is found, the extension will use the key `default`.

Example rendering
=================

The extension provides a partial that can be used for frontend rendering.

First, the extensions partial path needs to be added for rendering.
For **fluid_tyled_content** this can be done like this

.. code-block:: typoscript

    lib.contentElement.partialRootPaths.1663513618 = EXT:responsive_images/Resources/Private/Partials

After this path is added, the partial can be used in Fluid:

.. code-block:: html

    <f:render partial="PictureTag" arguments="{
        image: files.0,
        imageMaxWidth: 1920,
        imgClass: 'image-1',
        imageDefaultCropVariant: 'desktop',
        cropVariants: {
            mobile: '(max-width: 700px)',
            desktop: '(min-width: 701px)'
        }
    }" />

See the partials code for an explanation what variable
can be provides.


Using the ViewHelpers
=====================

This extension provides some ViewHelpers:

IfAnimatedGifViewHelper
-----------------------

This ViewHelper is a conditional ViewHelper that expects an image object.
The ViewHelper will evaluate if this image is an animated gif.

The ViewHelper can be used to prohibit processing of animated gifs
in order to keep their animations.

ImageViewHelper
---------------

This ViewHelper extends the default Fluid ImageViewHelper.
It uses the currently active variant or a specific variant key can be provided.

LoadRegisterViewHelper
----------------------

This ViewHelper can be used to set the currently used variant for rendering images.
Every Fluid code (including partials, sections and als TypoScript libs)
that renders an image will use the given key (if key is not overwrite by ViewHelper argument)

SourceViewHelper
----------------

This ViewHelper renders a source tag for usage in a picture tag.
The arguments `cropVariantKey` and `media` can be provided to archive
different image cropping for different image sizes.
