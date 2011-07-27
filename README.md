# php-datatables

This is a PHP library which wraps around the DataTables (http://www.datatables.net) plugin for jQuery.

This library provides a object-oriented interface to configure and build javascript data tables. The goal
of this library is to provide an easily extendable framework that allows DataTables to be easily incorporated
into any existing application or web framework. The library provides the means to build DataTables that can 
render data from any source including databases, web services, csv files, etc...

Currently, php-datatables implements a majority of the features and options within DataTables. The remaining
ones will be added over time.

## Overview

The basic steps to using php-datatables are:

  1. Set configuration options on a config object.
  2. Implement a method to load an array of entity objects for your table to render.
  3. Implement methods to format the data for your columns.

### Demo

Look at the files within the demo/ directory to see a basic example of php-datatables in action.

- ajax.php     
   
    A file showing how a json response is rendered for a DataTables ajax request
    
- Browser.php     
    
    An entity object that is used by DemoDataTable to render the data
    
- browsers.csv    
    
    A csv file containing the sample data for DemoDataTable
    
- DemoDataTable   

    An example DataTable_DataTable implementation which loads data from a csv file
    
- index.php      

    A file showing how the initial html/js for a DataTable is rendered


## Class Overview

### DataTable_DataTable

This class is the heart of the library. You simply extend this class for each DataTable that you will need
in your application. The purpose of this class is to obtain the data for the table and to format the results
that will be output.

Here are the basic requirements.

1. Pass in a DataTable_Config object (see below).

	   Your DataTable will need a config object in order define the columns and various options. You can either
	   pass this object into the constructor or define it within your constructor and pass it on to the parent
	   class.
	
2. Implement the loadData() method

	   This method is where you pull the data you want displayed and return it within a DataTable_DataResult object.
	   You are provided with a DataTable_Request object which allows you to pull the pagination information that
	   is passed in through AJAX requests.   
	
3. Implement the getTableId() method

	   Simply return a unique id for this table instance. This id is used as the html id attribute in the rendered
	   html table.
	
4. Implement getter methods to format colummn values

	   In each DataTable_Column object (see below) you must define a getter method name so that the column knows
	   where to obtain the value for each entity object. By default, DataTable_DataTable will call that getter
	   method on the entity object. However, you can implement the getter method within your DataTable class
	   in order to format the column values however you want.
	
	   The only requirement is that your method should expect to receive an entity object as it's only parameter.
	
	   Example configuration of getter method for a column:
	
	           // configure column
	           $column = new DataTable_Column();
	           $column->setName('fullName')
	                  ->setTitle('Full Name')
	                  ->setGetMethod('getFullName');
               
       Example implementation of getter method defined above:
               
			   /**
				* Format a column value that combines multiple entity properties
				*/
				protected function getFullName(User $user)
				{
				  return $user->getFirstName() . ' ' . $user->getLastName();
				}

5. Implement javascript callback functions

	   If your datatable needs to implement the various DataTable callback methods (http://datatables.net/usage/callbacks)
	   simply implement the various methods found within DataTable_DataTable.
	
			   protected function getRowCallbackFunction()
			   { 
			     return "
					function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
						/* Bold the grade for all 'A' grade browsers */
						if ( aData[4] == 'A' )
						{
							$('td:eq(4)', nRow).html( '<b>A</b>' );
						}
						return nRow;
					}
				 ";
			   }


### DataTable_Config

This class defines all the configuration options for a DataTable. A DataTable_DataTable object expects to receive
a DataTable_Config object. This object set's all the various options that get passed to the javascript table. This
object also holds a collection of all the DataTable_Column definitions.

Most of the time you will probably want to instantiate the config object in your __construct and pass it on to the parent
class. However, you can create the config from outside and pass it in to your DataTable_DataTable class. This is useful 
if you want to load the configuration from somewhere else such as a database, etc.

### DataTable_Column

This class defines all the options for an individual column within the DataTable. Each column must at the very least
have a name, title, and getter method name. You can configure whether columns are sortable, searchable, etc.

1. setTitle()

    This method sets the title that will be rendered in the column's <th> tag

2. setName()

    This method sets a unique name that the column can be referenced by.

3. setGetMethod()

    This method lets DataTable_DataTable know where it should obtain the value for the current column. First, it
    will check to see if this method exists within your DataTable_DataTable implementing class. Otherwise, it
    will call the getter method on the entity object. 

4. setSortKey()

	A key that loadData() can reference to know what to sort against.

Example:

	    $column = new DataTable_Column();
	    $column->setName("browser")
	           ->setTitle("Browser")
	           ->setGetMethod("getBrowser")
	           ->setSortKey("b.browser")
	           ->setIsSortable(true);

### DataTable_Request

An object of this class needs to be passed into the DataTable_DataTable->renderJson() method. This eventually gets
passed into your loadData() implementation. This object simply stores the parameters that are passed from DataTables
within AJAX requests and allow you to access them for pagination, sorting, and searching.

By default, this class provides a hydration method to fill the object with the parameters from a $_GET, $_POST, or
$_REQUEST array.

		$request = new DataTable_Request();
		$request->fromPhpRequest($_REQUEST);

You can extend this class to hydrate the parameters from some other framework specific request object if you need to.

### DataTable_DataResult

This is the type of object that is expected to be returned from your DataTable_DataTable->loadData() implementation.
You just need to pass in the array of your entities and a count of the total number of results for pagination (total
records for all pages).


## Using your DataTable

Display table (index.php):

		// render the initial html/js
		$table = new MyDataTable();
		$table->setAjaxDataUrl('ajax.php');
		echo $table->render();
		
Render AJAX response (ajax.php):

		// instatiate new DataTable
		$table = new MyDataTable();
		
		// convert DataTable AJAX parameters in request to a DataTable_Request
		$request = new DataTable_Request();
		$request->fromPhpRequest($_REQUEST);
		
		// render the JSON data string
		echo $table->renderJson($request);
	
## Non-AJAX DataTable

If you have a smaller data set that you want to render in a DataTable and thus
don't need AJAX for your pagination, sorting you can easily switch to non-ajax mode.

You just need to make sure that serverSideEnabled is set to false on your DataTable_Config object.

This will result in your loadData() method getting called when DataTable_Datatable->render() is called. The
loadData() method will receive a DataTable_Request object which has the sorting set for whatever
the default sort column is within your config. You may also want to set the staticMaxLength on the
config object to let your loadData method know how to limit your results.

  Example:

        // disable ajax
        $config->setIsServerSideEnabled(false);
    
        // let your loadData() method know to limit to 200 results
        $config->setStaticMaxLength(200);
	
## Multi-Column Sorting

If you need to sort against multiple columns, you can easily get the sorting information
of all of the columns from the DataTable_Request object.

  Example:
  
        public function loadData(DataTable_Request $request)
        {
          foreach($request->getSortColumns() as $sortColIndex => $sortDir){
            $sortKey = $this->getColumns()->get($sortColIndex)->getSortKey();
            
            // do something with $sortKey and $sortDir
          }
        }
