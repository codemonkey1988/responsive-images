# Responsive images for TYPO3 CMS
This extension adds responsive images support for fluid templates using the MediaViewHelper.

## Information
This extension is based on the fact, that the integrator forces image sizes that
the content editor may not change. An integrator can specify image configurations
that are used in fluid templates.

This extension will also respect cropping variants and will handle
different jpeg qualities for each image.

## Installation

1. `composer require codemonkey1988/responsive-images`
2. Activate extension in extension manager
3. Add TypoScript template *Responsive images settings*
4. Optional: Add TypoScript template *Responsive images default configuration (optional)* or *Responsive images bootstrap configuration (optional)*

## Configuration

See full documentation at https://responsive-images.readthedocs.io
