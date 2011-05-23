<?php 

// register the DataTable autoloader
include('../src/DataTable/Autoloader.php');
spl_autoload_register(array('DataTable_Autoloader', 'autoload'));

// include the Demo DataTable class
include('DemoDataTable.php');

// instatiate new DataTable
$table = new DemoDataTable();

// convert DataTable AJAX parameters in request to a DataTable_Request
$request = new DataTable_Request();
$request->fromPhpRequest($_REQUEST);

// render the JSON data string
echo $table->renderJson($request);