.. include:: /Includes.rst.txt
.. _changelog:

=========
Changelog
=========

3.1.0
~~~~~

* FEATURE: Add compatibility for TYPO3 12.0
* FEATURE: Allow setting the target file extension in SourceViewHelper
* TASK: Add info about specifying the file extension in SourceViewHelper
* TASK: Add changelog to documentation

3.0.1
~~~~~

* TASK: Add loading="lazy" attribute to PictureTag Partial
* BUGFIX: Add documentation link to readme
* BUGFIX: Exclude .ddev folder in composer package

3.0.0
~~~~~

.. warning::
   !! This release breaks existing configurations from version 2 !!

The extension was completely rewritten from scratch and the rendering of the frontend was updated.
This extension now uses the attributes ``srcset`` and ``sizes`` on image and source tags to provide responsive images.

**List of important changes**

* Drops TYPO3 v8 and v9 compatibility
* Drops quality and greyscale options for image rendering
* Drop processing and enabled TypoScript settings
* Adds TYPO3 v11 compatibility
* Drop support for v2 configuration - use variants for v3
* Improved documentation

Please have a look at :ref:`upgrade` for further instructions.
