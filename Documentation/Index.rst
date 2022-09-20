
.. _start:

=============================================================
Responsive Images
=============================================================

Extension for TYPO3 that adds the option to define centralized
configurations for picture tags and render them using Fluid.

Configurations are given by using TypoScript. The rendering is
done using the TYPO3 f:media ViewHelper.





About this extension
====================

This extension is based on the fact, that the integrator forces image sizes that
the content editor may not change. An integrator can specify image configurations
that are used in fluid templates.

This extension will also respect cropping variants (introduced in TYPO3 v8) and will handle
different jpeg qualities for each image.

.. toctree::
   :hidden:

   Installation
   Configuration
   Migration
