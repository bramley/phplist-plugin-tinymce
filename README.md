# TinyMCE Plugin #

## Description ##
This plugin provides TinyMCE 4 for editing messages and templates within phplist. 

It also integrates the elFinder file manager to provide file upload and selection.
## Compatibility ###

TinyMCE and elFinder are compatible with all the major browsers, see the TinyMCE site <http://www.tinymce.com/index.php>
and the elFinder site <https://github.com/Studio-42/elFinder/>

## Installation ##

### Set the plugin directory ###
The default plugin directory is `plugins` within the admin directory.

You can use a directory outside of the web root by changing the definition of `PLUGIN_ROOTDIR` in config.php.
The benefit of this is that plugins will not be affected when you upgrade phplist.

### Install through phplist ###
Install on the Plugins page (menu Config > Plugins) using the package URL `https://github.com/bramley/phplist-plugin-tinymce/archive/master.zip`.

### Install manually ###
Download the plugin zip file from <https://github.com/bramley/phplist-plugin-tinymce/archive/master.zip>

Expand the zip file, then copy the contents of the plugins directory to your phplist plugins directory.
This should contain

* the file TinyMCEPlugin.php
* the directory TinyMCEPlugin

### Enable the plugin ###
Click the small orange icon to enable the plugin. Note that only one editor should be enabled, otherwise phplist will choose the first
that it finds.

### Location of the TinyMCE and elFinder directories ###
The TinyMCE and elFinder directories must be within the web root. 

If you have the default plugin location then the plugin will use the correct paths automatically.

But if your plugin directory is outside of the web root then you must move or copy the `tinymce` and `elfinder` directories from
the plugin's directory to somewhere within the web root.  
Then use the Settings page (menu Config > Settings) to specify the path to each directory.
In the TinyMCE settings section enter

* the path to TinyMCE
* the path to elFinder 

Similarly, if you already use TinyMCE on your web site then you can use that version by specifying the path on the Settings page.
## Configuration ##
The width and height of the editor window can be specified on the Settings page.

The UPLOADIMAGES\_DIR value in config.php must be set to the location of a directory where elFinder can store uploaded images.
The directory must be writable by the web server. Note that the value is relative to the web root and must not contain a leading '/'.

If the UPLOADIMAGES\_DIR value in config.php is set to `false` then elFinder will be disabled and image uploading will not be possible.

## Custom configuration ##
Other settings for the editor are entered directly on the Settings page.
A default toolbar and plugin configuration has been copied from the <a href="http://www.tinymce.com/tryit/basic.php" target="_blank">Basic Example</a> page. 

See <http://www.tinymce.com/wiki.php/Configuration> for how to specify configuration settings.

## Upgrade from phplist 2.10.x with FCKEditor ##

In phplist 2.10 the FCKIMAGES_DIR value in config.php defines the directory into which images will be uploaded.
The value is relative to the phplist root directory.

In phplist 3.x a different value, UPLOADIMAGES\_DIR, is used to define the directory. This value is relative to the web root,
not to the phplist root directory. To continue using the same upload directory you must set UPLOADIMAGES\_DIR correctly.
So, for example, if the existing image upload directory is /lists/uploadimages then the FCKIMAGES\_DIR would be `uploadimages` but the 
value for UPLOADIMAGES\_DIR would be `lists/uploadimages`.

## Upgrading TinyMCE ##

The plugin includes TinyMCE 4.0.10 but will not automatically upgrade to a new release of TinyMCE.
You can download a later release of TinyMCE from <http://www.tinymce.com/download/download.php>. Then install on your web site and specify the path to the directory on the Settings page. Do not overwrite the version of TinyMCE in the plugin as that would then be lost if you upgrade the plugin later.

## Donation ##

This plugin is free but if you install and find it useful then a donation to support further development is greatly appreciated.

[![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=W5GLX53WDM7T4)

## Version history ##

    version     Description
    2013-11-05  Initial version for phplist 3
