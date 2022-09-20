.. include:: /Includes.rst.txt
.. _configuration_reference:

=======================
Configuration Reference
=======================

**Variant Properties**

.. contents::
   :local:
   :depth: 2


.. note::
   This extension provides the example configuration that can be used.

   * `Default Configuration <https://gitlab.com/Codemonkey1988/responsive-images/-/tree/main/Configuration/TypoScript/DefaultConfiguration>`_
   * `Bootstrap Configuration <https://gitlab.com/Codemonkey1988/responsive-images/-/tree/main/Configuration/TypoScript/BootstrapConfiguration>`_


croppingVariantKey
------------------

.. rst-class:: dl-parameters

plugin.tx_responsiveimages.settings.variants.<VariantKey>.croppingVariantKey
   :sep:`|` :aspect:`Data type:` string
   :sep:`|`

   The TYPO3 cropping variant key, that should be used by default.

providedImageSizes
------------------

.. rst-class:: dl-parameters

plugin.tx_responsiveimages.settings.variants.<VariantKey>.providedImageSizes
   :sep:`|` :aspect:`Data type:` array of image rendering options
   :sep:`|`

   Add multiple image rendering configurations.
   This options is used to generate the available images provided in the `srcset` attribute.

providedImageSizes.<key>.width
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

plugin.tx_responsiveimages.settings.variants.<VariantKey>.providedImageSizes.<key>.width
   :aspect:`Data type:` string

   See TYPO3 ImgResource <https://docs.typo3.org/m/typo3/reference-typoscript/11.5/en-us/Functions/Imgresource.html#width>

providedImageSizes.<key>.height
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

plugin.tx_responsiveimages.settings.variants.<VariantKey>.providedImageSizes.<key>.height
   :aspect:`Data type:` string

   See TYPO3 ImgResource <https://docs.typo3.org/m/typo3/reference-typoscript/11.5/en-us/Functions/Imgresource.html#height>

providedImageSizes.<key>.maxWidth
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

plugin.tx_responsiveimages.settings.variants.<VariantKey>.providedImageSizes.<key>.maxWidth
   :aspect:`Data type:` int

   See TYPO3 ImgResource <https://docs.typo3.org/m/typo3/reference-typoscript/11.5/en-us/Functions/Imgresource.html#maxw>

providedImageSizes.<key>.maxHeight
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

plugin.tx_responsiveimages.settings.variants.<VariantKey>.providedImageSizes.<key>.maxHeight
   :aspect:`Data type:` int

   See TYPO3 ImgResource <https://docs.typo3.org/m/typo3/reference-typoscript/11.5/en-us/Functions/Imgresource.html#maxh>

providedImageSizes.<key>.minWidth
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

plugin.tx_responsiveimages.settings.variants.<VariantKey>.providedImageSizes.<key>.minWidth
   :aspect:`Data type:` int

   See TYPO3 ImgResource <https://docs.typo3.org/m/typo3/reference-typoscript/11.5/en-us/Functions/Imgresource.html#minw>

providedImageSizes.<key>.minHeight
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

plugin.tx_responsiveimages.settings.variants.<VariantKey>.providedImageSizes.<key>.minHeight
   :aspect:`Data type:` int

   See TYPO3 ImgResource <https://docs.typo3.org/m/typo3/reference-typoscript/11.5/en-us/Functions/Imgresource.html#minh>


**Example:**

.. code-block:: typoscript

   providedImageSizes {
      10 {
         width = 500c
         height = 300c
      }
      20 {
         maxWidth = 900
      }
   }

sizes
-----

.. rst-class:: dl-parameters

plugin.tx_responsiveimages.settings.variants.<VariantKey>.sizes
   :sep:`|` :aspect:`Data type:` array of image sizes
   :sep:`|`

   Add multiple sizes configurations.
   This options is used to generate the available images provided in the `sizes` attribute.

sizes.<key>.viewportMediaCondition
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

plugin.tx_responsiveimages.settings.variants.<VariantKey>.sizes.<key>.viewportMediaCondition
   :aspect:`Data type:` string (optional)

   The condition at which for the `assumedImageWidth`.
   When none is given, the browsers assumes the `assumedImageWidth` as default when no other matches.

sizes.<key>.assumedImageWidth
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

plugin.tx_responsiveimages.settings.variants.<VariantKey>.sizes.<key>.assumedImageWidth
   :aspect:`Data type:` string

   The image width that is assumed when the `viewportMediaCondition` matches.


**Example:**

.. code-block:: typoscript

   sizes {
      10 {
         viewportMediaCondition = (min-width: 971px)
         assumedImageWidth = 1170px
       }
       20 {
         viewportMediaCondition = (min-width: 751px)
         assumedImageWidth = 970px
       }
       30 {
         viewportMediaCondition = (min-width: 421px)
         assumedImageWidth = 750px
       }
       40 {
         # Last element is the default element and does not need a viewportMediaCondition
         assumedImageWidth = 420px
       }
   }
