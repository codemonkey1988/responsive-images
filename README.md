# Responsive images for TYPO3 CMS
This extension adds responsive images support for fluid templates.

## Information
This extension is based on the assumption, that the frontend image sizes depends
on current context (e.g. in Bootstrap col-6 an image needs to be smaller that in col-12)
A TYPO3 editor should not be able to choose the image size directly. It always depends
on the rendering context in FE.

## Installation

1. `composer require codemonkey1988/responsive-images`
2. Activate extension in extension manager
3. Optional: Add TypoScript template *Responsive images default configuration (optional)* or *Responsive images bootstrap configuration (optional)*

See full documentation at [https://docs.typo3.org/p/codemonkey1988/responsive-images/main/en-us/](https://docs.typo3.org/p/codemonkey1988/responsive-images/main/en-us/)
