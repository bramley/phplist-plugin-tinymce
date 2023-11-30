<?php
ob_end_clean();
$elFilePath = $_SERVER['DOCUMENT_ROOT'] . rtrim(getConfig('elfinder_path'), '/') . '/php/';

include_once $elFilePath . 'elFinderConnector.class.php';
include_once $elFilePath . 'elFinder.class.php';
include_once $elFilePath . 'elFinderVolumeDriver.class.php';
include_once $elFilePath . 'elFinderVolumeLocalFileSystem.class.php';
// Required for MySQL storage connector
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeMySQL.class.php';
// Required for FTP connector support
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeFTP.class.php';


/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from '.' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool|null
 **/
function access($attr, $path, $data, $volume) {
    return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
        ? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
        :  null;                                    // else elFinder decide it itself
}
$uploadDir = '/' . trim(UPLOADIMAGES_DIR, '/') . '/';
$uploadPath = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;

if (defined('IMAGE_DIR_PER_ADMIN')) {
    if (IMAGE_DIR_PER_ADMIN === 'all' || !$_SESSION['logindetails']['superuser']) {
        $subDir = $_SESSION['logindetails']['id'];
        $uploadDir .= "$subDir/";
        $uploadPath .= "$subDir/";

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath);
        }
    }
}

// Documentation for connector options:
// https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options
$opts = array(
    'debug' => true,
    'roots' => array(
        array(
            'driver'        => 'LocalFileSystem',
            'path'          => $uploadPath,
            'URL'           => $uploadDir,
            'accessControl' => 'access'
        )
    )
);

// run elFinder
$connector = new elFinderConnector(new elFinder($opts), true);
$connector->run();
