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

    function __construct()
    {
        $this->elEnabled = defined('UPLOADIMAGES_DIR') && UPLOADIMAGES_DIR !== false;
        $this->coderoot = dirname(__FILE__) . self::CODE_DIR;
        $this->version = (is_file($f = $this->coderoot . self::VERSION_FILE))
            ? file_get_contents($f)
            : '';
        $tinyMCEPath = substr(PLUGIN_ROOTDIR, 0, 1) == '/' ? PLUGIN_ROOTDIR : $GLOBALS['pageroot'] . '/admin/' . PLUGIN_ROOTDIR;
        $tinyMCEPath .= self::CODE_DIR . 'tinymce';
        $elPath = substr(PLUGIN_ROOTDIR, 0, 1) == '/' ? PLUGIN_ROOTDIR : $GLOBALS['pageroot'] . '/admin/' . PLUGIN_ROOTDIR;
        $elPath .= self::CODE_DIR . 'elfinder';

        $this->settings = array(
            'tinymce_path' => array (
              'value' => $tinyMCEPath,
              'description' => 'path to TinyMCE',
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

    function adminmenu()
    {
        return array();
    }
  
    function editor($fieldname, $content)
    {
        $fieldname = htmlspecialchars($fieldname);
        $content = htmlspecialchars($content);
        $tinyMCEPath = rtrim(getConfig('tinymce_path'), '/');
        $config = getConfig('tinymce_config');

        if ($config) {
            $config = ",\n" . trim(trim($config), ',');
        } else {
            $config = '';
        }

        $width = getConfig('tinymce_width');
        $height = getConfig('tinymce_height');
        $html = <<<END
<script src="$tinyMCEPath/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: "textarea.editable",
    file_browser_callback : elFinderBrowser,
    relative_urls: false,
    width : $width,
    height : $height
    $config
});
function elFinderBrowser (field_name, url, type, win) {
  tinymce.activeEditor.windowManager.open({
    file: './?pi=TinyMCEPlugin&page=elfinder_phplist',
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
<textarea class="editable" name="$fieldname">$content</textarea>
END;
        return $html;
    }
}
