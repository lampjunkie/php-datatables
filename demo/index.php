<?php

// register the DataTable autoloader
include('../src/DataTable/Autoloader.php');
spl_autoload_register(array('DataTable_Autoloader', 'autoload'));

// include the Demo DataTable class
include('DemoDataTable.php');


// instantiate the DataTable
$table = new DemoDataTable();

// set the url to the ajax script
$table->setAjaxDataUrl('ajax.php');



?>
<html>

<head>
	
	<title>php-datatables Demo</title>

	<style type="text/css" title="currentStyle">
		@import "DataTables-1.7.6/media/css/demo_table.css";
	</style>
	<script type="text/javascript" language="javascript" src="DataTables-1.7.6/media/js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="DataTables-1.7.6/media/js/jquery.dataTables.js"></script>

</head>

<body>

	<?php echo $table->render(); ?>

</body>
</html>