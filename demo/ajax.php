<?php

/**
 * This file is part of the DataTable demo package
 * 
 * (c) Marc Roulias <marc@lampjunkie.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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