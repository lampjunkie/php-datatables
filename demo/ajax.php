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
 * This file shows how the AJAX data for a DataTable is rendered.
 * 
 * By default this file is configured to load the data from a CSV file using the CsvBrowserService. 
 * This can be switched to use the DbBrowserService which will load the data from a MySQL database.
 *  
 * This demonstrates how your DataTable doesn't need to be tied to any specific database implementation, etc.
 * Thus, you can use your application's existing ORM, services, etc. to deal with the data 
 * and your DataTable doesn't need to care about where the data came from as long as it's
 * provided an array of entity objects.
 */

// register the DataTable autoloader
include('../src/DataTable/Autoloader.php');
spl_autoload_register(array('DataTable_Autoloader', 'autoload'));

// include the Demo DataTable class
include_once('DemoDataTable.php');

// define the Browser Service to use for this demo
// either 'db' or 'csv'
define('SERVICE_TO_USE', 'csv');

// build a Browser Service object based on the type that was selected
if(SERVICE_TO_USE == 'db'){
  
  include_once('DbBrowserService.php');
  $browserService = new DbBrowserService('localhost', 'browsers', 'root', '');
  
} elseif(SERVICE_TO_USE == 'csv'){
  
  include_once('CsvBrowserService.php');
  $browserService = new CsvBrowserService('data/browsers.csv');
}

// instatiate new DataTable
$table = new DemoDataTable();

// set selected Browser Service to the demo DataTable
$table->setBrowserService($browserService);

// convert DataTable AJAX parameters in request to a DataTable_Request
$request = new DataTable_Request();
$request->fromPhpRequest($_REQUEST);

// render the JSON data string
echo $table->renderJson($request);
