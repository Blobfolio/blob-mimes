=== Lord of the Files: Enhanced Upload Security ===
Contributors: blobfolio
Donate link: https://blobfolio.com/donate.html
Tags: mime, SVG, file validation, security plugin, wordpress security, malware, exploit, security, sanitizing, sanitization, file detection, upload security, secure, file uploads, infection, block hackers, protection
Requires at least: 4.7.1
Tested up to: 5.1
Requires PHP: 7.1
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
 * Admin warnings if plugin contributors have changed since you last updated [#42255](https://core.trac.wordpress.org/ticket/42255);

== Requirements ==

 * WordPress 4.7.1 or later.
 * PHP 7.1 or later.
 * DOMDocument extension is optional, but will improve SVG sanitizing.

Please note: it is **not safe** to run WordPress atop a version of PHP that has reached its [End of Life](http://php.net/supported-versions.php). Future releases of this plugin might, out of necessity, drop support for old, unmaintained versions of PHP. To ensure you continue to receive plugin updates, bug fixes, and new features, just make sure PHP is kept up-to-date. :)

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
2. The Updates and Plugins screens display a warning if a local copy of a plugin has different contributors listed than an available update.

== Installation ==

Nothing fancy!  You can use the built-in installer on the Plugins page or extract and upload the `blob-mimes` folder to your plugins directory via FTP.

To install this plugin as [Must-Use](https://codex.wordpress.org/Must_Use_Plugins), download, extract, and upload the `blob-mimes` folder to your mu-plugins directory via FTP. Please note: MU Plugins are removed from the usual update-checking process, so you will need to handle future updates manually.

== Privacy Policy ==

This plugin does not make use of or collect any "Personal Data".

== Changelog ==

= 0.8.9 =
* [Improved] Better XML/JSON type matching.
* [Misc] Update MIME database.

= 0.8.8 =
* [Misc] Update MIME database.

= 0.8.7 =
* [Misc] Update MIME database.

= 0.8.6 =
* [Misc] Update MIME database.
* [Misc] Minor performance improvements.

= 0.8.5 =
* [Misc] Update MIME database.

== Upgrade Notice ==

= 0.8.9 =
The MIME database has been updated, and generic XML/JSON matching has been improved.

= 0.8.8 =
The MIME database has been updated.

= 0.8.7 =
The MIME database has been updated.

= 0.8.6 =
The MIME database has been updated.

= 0.8.5 =
The MIME database has been updated.
