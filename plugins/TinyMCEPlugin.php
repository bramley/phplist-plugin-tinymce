<?php
/**
 * TinyMCEPlugin for phplist
 * 
 * This file is a part of TinyMCEPlugin.
 *
 * This plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * @category  phplist
 * @package   TinyMCEPlugin
 * @author    Duncan Cameron
 * @copyright 2013 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */
class TinyMCEPlugin extends phplistPlugin
{
    const VERSION_FILE = 'version.txt';
    const CODE_DIR = '/TinyMCEPlugin/';
    /*
     *  Private variables
     */
    private $elEnabled;

    /*
     *  Inherited variables
     */
    public $name = 'Tiny MCE plugin';
    public $editorProvider = true;
    public $authors = 'Duncan Cameron';
    public $description = 'Provides the TinyMCE editor for editing messages and templates.';
    public $enabled = 1;

    private function elFinderScript($function)
    {
        $elUrl = './?pi=TinyMCEPlugin&page=elfinder_standalone';
        $html = <<<END
<script type='text/javascript'>
$function = function(callback) {
    window.elFinder = {};
    window.elFinder.callBack = function(url) {
        callback(url);
        window.elFinder = null;
    };
    window.open('$elUrl', '', 'width=900,height=450');
};
</script>
END;
        return $html;
    }

    private function editorScript($fieldname, $width, $height, $toolbar)
    {
        $html = '';
        $tinymceUrl = getConfig('tinymce_url');

        if (substr($tinymceUrl, -15) != '/tinymce.min.js') {
            $html .= sprintf(
                '<div class="note error">tinyMCE is not available because the setting for the URL of tinymce.min.js "%s" is incorrect.</div>',
                $tinymceUrl
            );
        }

        if (!is_writeable($dir = $_SERVER['DOCUMENT_ROOT'] . '/' . trim(UPLOADIMAGES_DIR, '/'))) {
            $html .= sprintf(
                '<div class="note error">The image upload directory "%s" does not exist or is not writeable.</div>',
                htmlspecialchars($dir)
            );
        }
        $settings = array();

        $fullTemplate = getConfig('tinymce_fulltemplate');
        $fullMessage = getConfig('tinymce_fullmessage');
        $fullPageSetting = '';

        if (($fieldname == 'template' && $fullTemplate)
            || ($fieldname == 'message' && $fullMessage && !$fullTemplate)) {
            $fullPageSetting = 'settings.plugins.push("fullpage");';
        }

        $config = getConfig('tinymce_config');

        if ($config) {
            $settings[] = trim(trim($config), ',');
        }
        if ($width) {
            $settings[] = "width: $width";
        }

        if ($height) {
            $settings[] = "height: $height";
        }

        if ($toolbar) {
            $toolbar = trim($toolbar, '"');
            $settings[] = "toolbar: \"$toolbar\"";
        }
        $configSettings = implode(",\n", $settings);

        $html .= <<<END
<style>
    body.send .panel .content .mce-tinymce div {margin: 0px}
</style>
<script src="$tinymceUrl"></script>
<script type="text/javascript">
settings = {
    setup: function(ed) {
        ed.addMenuItem('info', {
            'text' : 'Info',
            'context' : 'tools',
            'icon' : 'help',
            'onclick' : function() {
                tinymce.activeEditor.windowManager.alert('TinyMCE ' + tinymce.majorVersion + '.' + tinymce.minorVersion);
            }
        });
    },
    selector: "textarea.editable",
    file_browser_callback: elFinderBrowser,
    relative_urls: false,
    remove_script_host: false,
    $configSettings
};
$fullPageSetting
tinymce.init(settings);
function elFinderBrowser (field_name, url, type, win) {
  tinymce.activeEditor.windowManager.open({
    file: './?pi=TinyMCEPlugin&page=elfinder_tinymce',
    title: 'elFinder',
    width: 900,  
    height: 450,
    resizable: 'yes'
  }, {
    setUrl: function (url) {
      win.document.getElementById(field_name).value = url;
    }
  });
  return false;
}
</script>
END;
        return $html;
    }

    public function __construct()
    {
        $this->elEnabled = defined('UPLOADIMAGES_DIR') && UPLOADIMAGES_DIR !== false;
        $this->coderoot = dirname(__FILE__) . self::CODE_DIR;
        $this->version = (is_file($f = $this->coderoot . self::VERSION_FILE))
            ? file_get_contents($f)
            : '';
        $elPath = substr(PLUGIN_ROOTDIR, 0, 1) == '/' ? PLUGIN_ROOTDIR : $GLOBALS['pageroot'] . '/admin/' . PLUGIN_ROOTDIR;
        $elPath .= self::CODE_DIR . 'elfinder';

        $this->settings = array(
            'tinymce_url' => array (
              'value' => '//cdn.tinymce.com/4/tinymce.min.js',
              'description' => 'URL of tinymce.min.js',
              'type' => 'text',
              'allowempty' => 0,
              'category'=> 'TinyMCE',
            ),
            'tinymce_config' => array (
              'value' => '',
              'description' => 'Custom configuration settings',
              'type' => 'textarea',
              'allowempty' => 1,
              'category'=> 'TinyMCE',
            ),
            'tinymce_width' => array (
              'value' => 600,
              'description' => 'Width in px of TinyMCE window',
              'type' => 'integer',
              'allowempty' => 0,
              'min' => 100,
              'max' => 800,
              'category'=> 'TinyMCE',
            ),
            'tinymce_height' => array (
              'value' => 600,
              'description' => 'Height in px of TinyMCE window',
              'type' => 'integer',
              'allowempty' => 0,
              'min' => 100,
              'max' => 800,
              'category'=> 'TinyMCE',
            ),
            'tinymce_fulltemplate' => array (
              'description' => 'Allow templates to be edited as full HTML pages',
              'type' => 'boolean',
              'value' => true,
              'allowempty' => true,
              'category'=> 'TinyMCE',
            ),
            'tinymce_fullmessage' => array (
              'description' => 'Allow messages to be edited as full HTML pages',
              'type' => 'boolean',
              'value' => false,
              'allowempty' => true,
              'category'=> 'TinyMCE',
            )
        );
        $this->settings['tinymce_config']['value'] = <<<END
toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image",
plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ]
END;

        if ($this->elEnabled) {
            $this->settings += array(
                'elfinder_path' => array (
                  'value' => $elPath,
                  'description' => 'path to elFinder',
                  'type' => 'text',
                  'allowempty' => 0,
                  'category'=> 'TinyMCE',
                )
            );
        }
        parent::__construct();
    }

    public function dependencyCheck()
    {
        global $editorplugin;

        return array(
            'PHP version 5.3.0 or greater' => version_compare(PHP_VERSION, '5.3') > 0,
            'No other editor enabled' => empty($editorplugin) || $editorplugin == 'TinyMCEPlugin',
        );
    }
    public function adminmenu()
    {
        return array();
    }
  
    public function editor($fieldname, $content)
    {
        $width = getConfig('tinymce_width');
        $height = getConfig('tinymce_height');

        return $this->createEditor($fieldname, $content, $width, $height);
    }

    public function createEditor($fieldname, $content, $width = null, $height = null, $toolbar = null)
    {
        $fieldname = htmlspecialchars($fieldname);
        $content = htmlspecialchars($content);
        $html = $this->editorScript($fieldname, $width, $height, $toolbar) . <<<END
<textarea class="editable" name="$fieldname">$content</textarea>
END;

        return $html;
    }

    public function createImageBrowser($function)
    {
        static $firstTime = true;

        if ($firstTime) {
            $firstTime = false;
            $html = $this->elFinderScript($function);
        } else {
            $html = '';
        }

        return $html;
    }
}
