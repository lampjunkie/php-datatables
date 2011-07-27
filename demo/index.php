<?php

/**
 * This file is part of the DataTable demo package
 * 
 * (c) Marc Roulias <marc@lampjunkie.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * This example demonstrates how to use a DataTable for displaying
 * data pulled from a server through an ajax request.
 * 
 * Be sure to download the DataTables jQuery plugin from http://http://datatables.net/download/
 * and extract it within this folder
 * 
 * Look at ajax.php to see how the data is pulled for the table. By default it is configured
 * to load the data from a CSV file using the CsvBrowserService. This can be switched to use
 * the DbBrowserService which will load the data from a MySQL database. This demonstrates
 * how your DataTable doesn't need to be tied to any specific database implementation, etc.
 * Thus, you can use your application's existing ORM, services, etc. to deal with the data 
 * and your DataTable doesn't need to care about where the data came from as long as it's
 * provided an array of entity objects.
 */

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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
	"http://www.w3.org/TR/html4/strict.dtd">

<html>

<head>
	
	<title>php-datatables Demo</title>

	<style type="text/css" title="currentStyle">
		@import "DataTables-1.8.1/media/css/demo_table.css";
	</style>
	
	<script type="text/javascript" language="javascript" src="DataTables-1.8.1/media/js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="DataTables-1.8.1/media/js/jquery.dataTables.js"></script>

</head>

<body>

	<!-- render the initial DataTable HTML and JS -->
	<?php echo $table->render(); ?>

</body>
</html>