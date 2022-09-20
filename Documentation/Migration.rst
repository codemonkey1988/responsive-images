.. _migration:

=============
Migration
=============

2.x to 3.x
==========

Global changes:
---------------

* The extension is only compatible with **TYPO3 v10.4 or later**
* The extension requires **PHP 7.4**

Grayscale ViewHelper option
---------------------------

Replace ViewHelper argument `grayscale` with `greyscale`.

Supported mime types
--------------------

The global extension setting for `supportedMimeTypes` are removed.
Supported mime types can be configured using TypoScript for each
variant with:

.. code-block:: typoscript

   plugin.tx_responsiveimages.settings.configuration.default.mimeTypes = image/jpeg,image/gif,image/png

Using environment variables
---------------------------

The support for environment variables have been removed.
To keep the same behaviour, the environment variables
can be set in TypoScript constants.

.. code-block:: typoscript

   plugin.tx_responsiveimages.settings.enabled := getEnv(RESPONSIVE_IMAGES_ENABLED)
   plugin.tx_responsiveimages.settings.processing := getEnv(RESPONSIVE_IMAGES_PROCESSING)

Migration of ConfigurationUtility:
----------------------------------

The class `\Codemonkey1988\ResponsiveImages\Utility\ConfigurationUtility`
has been moved to `\Codemonkey1988\ResponsiveImages\Service\ConfigurationService`

The methods `isEnabled` and `isProcessingEnabled` are not
longer static method. New way to using it:

.. code-block:: php

   $configurationService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
      \Codemonkey1988\ResponsiveImages\Service\ConfigurationService::class
   );
   $configurationService->isEnabled();
   $configurationService->isProcessingEnabled();

Overwritten PHP classes
-----------------------

The extension got a major refactoring. Please have a look at the new structure.
