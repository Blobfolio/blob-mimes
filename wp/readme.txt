=== Lord of the Files: Enhanced Upload Security ===
Contributors: blobfolio
Donate link: https://blobfolio.com/donate.html
Tags: file validation, MIME types, media types, security, uploads, Media Library, SVG, file sanitizing, file detection
Requires at least: 4.7.1
Tested up to: 4.8
Stable tag: trunk
License: WTFPL
License URI: http://www.wtfpl.net/

This plugin expands file-related security around the upload process.

== Description ==

WordPress relies mostly on name-based validation when deciding whether or not to allow a particular file, leaving the door open for various kinds of attacks.

Lord of the Files adds to this content-based validation and sanitizing, making sure that files are what they say they are and safe for inclusion on your site.

The main features include:

 * Robust *real* filetype detection;
 * Full MIME alias mapping;
 * SVG sanitization (if SVG uploads have been whitelisted);
 * File upload debugger;
 * Fixes issues related to [#40175](https://core.trac.wordpress.org/ticket/40175) that have been present since WordPress `4.7.1`.

== Requirements ==

 * WordPress 4.7.1 or later.
 * PHP 5.4 or later.
 * DOMDocument extension is optional, but will improve SVG sanitizing.

Please note: it is **not safe** to run WordPress atop a version of PHP that has reached its [End of Life](http://php.net/supported-versions.php). As of right now, that means your server should only be running **PHP 5.6 or newer**.

Future releases of this plugin might, out of necessity, drop support for old, unmaintained versions of PHP. To ensure you continue to receive plugin updates, <strike>buf</strike> bug fixes, and new features, just make sure PHP is kept up-to-date. :)

== Frequently Asked Questions ==

= Does this require any theme or config changes? =

Nope! The main magic is all automatic.

There are, however, plenty of under-the-hood goodies developers can hook into to modify the default behaviors. Visit the [Github](https://github.com/Blobfolio/blob-mimes/tree/master/wp) page for more detailed reference.

= This has mostly helped but I am still having trouble with one file... =

While this plugin extends MIME alias handling more than 20-fold, we are still busy tracking down unusual edge cases. Please go to `Tools > Debug File Validation` and post that output in a new support ticket for this plugin.

= Does this plugin enable SVG support? =

No. This plugin does not modify your site's upload whitelist (see e.g. [upload_mimes](https://codex.wordpress.org/Plugin_API/Filter_Reference/upload_mimes) for that). However if SVGs have been enabled for your site, this will sanitize them at the upload stage to make sure they do not contain any dangerous exploits.

There are a number of SVG-related filters that can be used to modify the sanitization behavior. Checkout the [Github](https://github.com/Blobfolio/blob-mimes/tree/master/wp) documentation for more information.

== Screenshots ==

1. Results from the File Validation Debug tool, available to administrators under the `Tools` menu.

== Installation ==

Nothing fancy!  You can use the built-in installer on the Plugins page or extract and upload the `blob-mimes` folder to your plugins directory via FTP.

To install this plugin as [Must-Use](https://codex.wordpress.org/Must_Use_Plugins), download, extract, and upload the `blob-mimes` folder to your mu-plugins directory via FTP. Please note: MU Plugins are removed from the usual update-checking process, so you will need to handle future updates manually.

== Changelog ==

= 0.5.5 =
* [Improved] Lighter-weight SVG type detection.
* [Fixed] Suppress some filesystem warnings.

= 0.5.4 =
* [Change] Remove build from plugin version to match WP's new standard.
* [Misc] Update MIME database.

= 0.5.3-2 =
* [Improved] Must-Use compatibility.

= 0.5.3-0 =
* [Misc] Update MIME database.

= 0.5.2 =
* [Misc] Spanish translation.

= 0.5.1 =
* [Misc] Update MIME database to improve `XLSM` detection.
* [Improved] Cleaned file upload debugger.

= 0.5.0 =
* [New] SVG sanitizing support.
* [Change] Updated MIME database.
* [Change] Disentangle this plugin from the proposed patch #39963; that enhancement is a WONTFIX.

= 0.1.3 =
* [New] Upload debugging tool to help provide additional information about why a file is failing.

= 0.1.2 =
* [Improved] Rebuild database to catch additional occurrences of `application/CDFV2-xxx` 
* [New] Integrate update support.

== Upgrade Notice ==

= 0.5.5 =
Minor performance and UX improvements.

= 0.5.4 =
The MIME database has been updated.

= 0.5.3-2 =
Improved Must-Use compatibility.

= 0.5.3-0 =
The MIME database has been updated.

= 0.5.2 =
The plugin is now available in Spanish.

= 0.5.1 =
The MIME database has been updated to improve `XLSM` detection and the file upload debug tool has been cleaned up.

= 0.5.0 =
This plugin is now independent of ticket #39963 (unfortunately in WONTFIX limbo), and so will refocus itself to provide broader upload-related security enhancements. Enjoy!

= 0.1.3 =
An upload debugging tool has been added to the `Tools` menu to provide more specific information about a file.

= 0.1.2 =
The database has been updated to catch additional occurrences of `application/CDFV2-xxx`.

