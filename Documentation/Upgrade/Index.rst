.. include:: /Includes.rst.txt
.. _upgrade:

=====================
Upgrade from v2 to v3
=====================

Version 3 of this extension is fully rewritten and follows a
more modern way of rendering responsive images.
Version 2 and 3 are not compatible with each other.

I'm sorry to say, that the v2 configuration will no longer work
and where is not automated way to update the configuration.

Please have a look at how this extension works
in :ref:`configuration` and :ref:`rendering`.


What should be done
===================

1. Provide a new TypoScript configuration using variants
2. Replace all `f:media` calls with the new :ref:`rendering`
3. Make sure to remove unnecessary TypoScript to keep your installation clean
