<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>CodeIgniter Pagination</title>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/styles.css" />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/prototype.js"></script>
    <script type="text/javascript">
    function showAjaxLoading(){
    	document.getElementById("ajax_loader").style.display = "block";
    }
    
    function hideAjaxLoading(){
    	window.setTimeout('document.getElementById("ajax_loader").style.display = "none";', 800);
    }
    
    function loadBrowse(page){
    	var url = '<?=base_url()?>pagination_test/page_ajax_browse/';
    	var ajax = new Ajax.Updater('browse', url,{ 
    		onLoading: showAjaxLoading,
    		onLoaded: hideAjaxLoading
    	});
    }
    
    Event.observe(window, 'load', loadBrowse);
    </script>
</head>
<body>
<div id="ajax_loader"><img src="<?=base_url()?>assets/images/ajax-loader.gif" /> Loading...</div>
<p><strong>Demos:</strong></p>
<div><a href="<?=base_url()?>pagination_test/">Query String</a> | <a href="<?=base_url()?>pagination_test/page_directory/">Directory Structure</a> | <a href="<?=base_url()?>pagination_test/page_ajax/">Ajax</a></div>

<div id="browse"></div>
</body>
</html>