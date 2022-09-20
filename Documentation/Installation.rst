.. _installation:

============
Installation
============

This extension can be installed by composer:

.. code-block:: shell

   composer require codemonkey1988/responsive-images

To enable this extension, you need to add the provided
TypoScript template `Responsive images settings`.

This extension provides additional templates that contains
example configurations.

* `Responsive images default configuration (optional)` Example generic configuration
* `Responsive images bootstrap configuration (optional)` Example Bootstrap configuration

Template setup
==============

To tell the extension, what configuration should be used for
rendering, there need to be a register entry with the key
`IMAGE_VARIANT_KEY`. The value should be one of the available
variants.

The key can be set via TypoScript:

.. code-block:: typoscript

   10 = LOAD_REGISTER
   10.IMAGE_VARIANT_KEY = TEXT
   10.IMAGE_VARIANT_KEY.value = col-6

There is also a ViewHelper to settings the register variable:

.. code-block:: html

   <html data-namespace-typo3-fluid="true"
      xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers"
      xmlns:r="http://typo3.org/ns/Codemonkey1988/ResponsiveImages/ViewHelpers">
         <r:loadRegister value="coal6" key="IMAGE_VARIANT_KEY">
            <f:media file="{file}" />
         </r:loadRegister>
   </html>
