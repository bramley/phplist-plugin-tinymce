<?php 
ob_clean();
$elPath = rtrim(getConfig('elfinder_path'), '/') . '/';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>elFinder 2.0</title>

		<!-- jQuery and jQuery UI (REQUIRED) -->
		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/smoothness/jquery-ui.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>

		<!-- elFinder CSS (REQUIRED) -->
		<link rel="stylesheet" type="text/css" href="<?php echo $elPath; ?>css/elfinder.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $elPath; ?>css/theme.css">

		<!-- elFinder JS (REQUIRED) -->
		<script src="<?php echo $elPath; ?>js/elfinder.min.js"></script>

		<!-- elFinder translation (OPTIONAL) -->

		<!-- elFinder initialization (REQUIRED) -->
        <script type="text/javascript">
          var FileBrowserDialogue = {
            init: function() {
              // Here goes your code for setting your custom things onLoad.
            },
            mySubmit: function (URL) {
              // pass selected file path to TinyMCE
              top.tinymce.activeEditor.windowManager.getParams().setUrl(URL);

              // close popup window
              top.tinymce.activeEditor.windowManager.close();
            }
          }
        </script>
        <script type="text/javascript">
          $().ready(function() {
            var elf = $('#elfinder').elfinder({
              // set your elFinder options here
              url: './?pi=TinyMCEPlugin&page=connector_phplist',
              getFileCallback: function(file) {
                FileBrowserDialogue.mySubmit(file.url);
              }
            }).elfinder('instance');      
          });
        </script>
	</head>
	<body>

		<!-- Element where elFinder will be created (REQUIRED) -->
		<div id="elfinder"></div>

	</body>
</html>
<?php
exit();
