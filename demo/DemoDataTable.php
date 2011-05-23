<?php

// include the Browser entity class
include('Browser.php');

class DemoDataTable extends DataTable_DataTable
{
  /**
   * Build the demo configuration for this table
   * 
   * @param DataTable_Config $config
   */
  public function __construct(DataTable_Config $config = null)
  {

    // create first column
    $column1 = new DataTable_Column();
    $column1->setName("renderingEngine")
            ->setLabel("Rendering Engine")
            ->setGetMethod("getRenderingEngine")
            ->setSortKey("renderingEngine")
            ->setIsSortable(true)
            ->setIsDefaultSort(true)
            ->setRenderFunction("
                                  function ( oObj ) {
                      				return oObj.aData[0] +' ----- '+ oObj.aData[3];
                      			}
            ");

    // create second column
    $column2 = new DataTable_Column();
    $column2->setName("browser")
            ->setLabel("Browser")
            ->setGetMethod("getBrowser")
            ->setSortKey("browser")
            ->setIsSortable(true)
            ->setIsSearchable(true);

    // create third column
    $column3 = new DataTable_Column();
    $column3->setName("platform")
            ->setLabel("Platform(s)")
            ->setGetMethod("getPlatform")
            ->setSortKey("platform")
            ->setIsSortable(true)
            ->setIsDefaultSort(false);

    // create third column
    $column4 = new DataTable_Column();
    $column4->setName("engineVersion")
            ->setLabel("Engine Version")
            ->setGetMethod("getEngineVersion")
            ->setSortKey("engineVersion")
            ->setIsSortable(true)
            ->setIsDefaultSort(false);
    
    // create third column
    $column5 = new DataTable_Column();
    $column5->setName("cssGrade")
            ->setLabel("CSS Grade")
            ->setGetMethod("getCssGrade")
            ->setSortKey("cssGrade")
            ->setIsSortable(true)
            ->setIsDefaultSort(false);

    // create third column
    $column6 = new DataTable_Column();
    $column6->setName("actions")
            ->setLabel("Actions")
            ->setGetMethod("getActions");
    
    // create invisible column
    $column7 = new DataTable_Column();
    $column7->setName("invisible")
             ->setLabel("Invisible")
             ->setIsVisible(false)
             ->setGetMethod("getInvisible");
    
    // create config
    $config = new DataTable_Config();
    
    // add columns to collection
    $config->getColumns()->add($column1);
    $config->getColumns()->add($column2);
    $config->getColumns()->add($column3);
    $config->getColumns()->add($column4);
    $config->getColumns()->add($column5);
    $config->getColumns()->add($column6);
    $config->getColumns()->add($column7);
     
    // build the language configuration
    $languageConfig = new DataTable_LanguageConfig();
    $languageConfig->setPaginateFirst("Beginning")
                   ->setPaginateLast("End")
                   ->setSearch("Find it:");

    // add LangugateConfig to the DataTableConfig object
    $config->setLanguageConfig($languageConfig);

    // set data table options
    $config->setClass("display");
    $config->setDisplayLength(10);
    $config->setIsPaginationEnabled(true);
    $config->setIsLengthChangeEnabled(true);
    $config->setIsFilterEnabled(true);
    $config->setIsInfoEnabled(true);

    $config->setIsSortEnabled(true);
    //$config->setScrollY("200px");
    $config->setIsAutoWidthEnabled(true);
    $config->setIsScrollCollapseEnabled(false);
    $config->setPaginationType(DataTable_Config::PAGINATION_TYPE_FULL_NUMBERS);
    $config->setIsJQueryUIEnabled(false);
    $config->setIsServerSideEnabled(true);
    $config->setIsSaveStateEnabled(true);
    $config->setCookiePrefix("my_table_prefix_");
    $config->setStripClasses(array('odd', 'even', 'even'));
    
    // pass DataTableConfig to the parent
    parent::__construct($config);
  }

  /**
   * Load the data for a request
   * 
   * This demo emulates loading data from a database and performing
   * a count of the total results, limiting them, ordering them, and
   * searching if a search term is passed in.
   * 
   * @see DataTable_DataTable::loadData()
   */
  public function loadData(DataTable_Request $request)
  {
    // get fake data set
    $users = $this->loadFakeData('browsers.csv');

    // search against object array if a search term was passed in
    if(!is_null($request->getSearch())){
      $users = $this->search($users, $request->getSearch(), array('renderingEngine', 'browser', 'platform', 'engineVersion', 'cssGrade'));
    }

    // get total length of all results (emulate count(*) query)
    $totalLength = count($users);
    
    // sort results by sort column passed in
    $this->sortObjectArray($this->config->getColumns()->get($request->getSortColumnIndex())->getSortKey(), $users, $request->getSortDirection());

    // limit the results based on parameters passed in
    $this->limit($users, $request->getDisplayStart(), $request->getDisplayLength());
    
    // return the final result set
    return new DataTable_DataResult($users, $totalLength);
  }

  /**
   * (non-PHPdoc)
   * @see DataTable_DataTable::getTableId()
   */
  public function getTableId()
  {
    return 'demoTable';
  }

  /**
   * Build the data for the 'Actions' column
   * 
   * @param Browser $browser
   */
  protected function getActions(Browser $browser)
  {
    $html = "<a href=\"#\" onclick=\"alert('{$browser->getBrowser()}');\">View</a>";
    $html .= ' | ';
    $html .= "<a href=\"#\" onclick=\"confirm('Delete {$browser->getBrowser()}?');\">Delete</a>";
    return $html;
  }
  
  protected function getInvisible(Browser $browser)
  {
    return $browser->getBrowser() . ' --- ' . $browser->getPlatform();
  }

  protected function getRowCallbackFunction()
  {
    return "
            function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

    			/* Bold the grade for all 'A' grade browsers */
    			if ( aData[{$this->getColumnIndexByName('cssGrade')}] == 'A' )
    			{
    				$('td:eq({$this->getColumnIndexByName('cssGrade')})', nRow).html( '<b>A</b>' );
    			}
    			return nRow;
            }
    ";
  }
  
  protected function getInitCompleteFunction2()
  {
    return "
            function() {
              alert( 'DataTables has finished it\'s initialisation.' );
            }
    ";
  }
  
  protected function getDrawCallbackFunction()
  {
    return "
            function() {
              
            }
    ";
  }
  
  protected function getFooterCallbackFunction2()
  {
    return "
            function( nFoot, aasData, iStart, iEnd, aiDisplay ) {
            	console.log(nFoot);
            }
    ";
  }
  
  protected function getHeaderCallbackFunction()
  {
    return "
            function( nHead, aasData, iStart, iEnd, aiDisplay ) {
            	nHead.getElementsByTagName('th')[0].innerHTML = 'Displaying '+(iEnd-iStart)+' records';
            }
    
    ";
  }
  
  protected function getInfoCallbackFunction2()
  {
    return "
            function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
            		return iStart + ' to ' + iEnd;
            }
    ";
  }
  
  protected function getCookieCallbackFunction()
  {
    return "
    		function (sName, oData, sExpires, sPath){ 
    			cookie = sName + '=' + JSON.stringify(oData) + '; expires=' + sExpires +'; path=' + sPath;
    			console.log(cookie);
    			return cookie;
    		}
    ";
  }
  
  /**
   * ==========================================================================
   * Utility Methods for Demo
   * ==========================================================================
   */

  /**
   * Get an array of Users
   * 
   * @return array
   */
  private function loadFakeData($file)
  {
    $browsers = array();
    
    if (($handle = fopen($file, "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $browsers[] = new Browser($data[0], $data[1], $data[2], $data[3], $data[4]);
      }
      fclose($handle);
    }

    return $browsers;
  }

  /**
   * Sort an array of objects by an object property
   * 
   * @param string $field
   * @param array $arr
   * @param string $sorting
   * @param boolean $case_insensitive
   */
  private function sortObjectArray($field, &$arr, $sorting='asc', $case_insensitive=true)
  {
    $field = 'get' . ucfirst($field);
    if(is_array($arr) && (count($arr)>0) && ( ( is_array($arr[0]) && isset($arr[0][$field]) ) || ( is_object($arr[0]) && method_exists($arr[0], $field) ) ) ){
      if($case_insensitive==true) $strcmp_fn = "strnatcasecmp";
      else $strcmp_fn = "strnatcmp";

      if($sorting=='asc'){
        $fn = create_function('$a,$b', '
                    if(is_object($a) && is_object($b)){
                        return '.$strcmp_fn.'($a->'.$field.'(), $b->'.$field.'());
                    }else if(is_array($a) && is_array($b)){
                        return '.$strcmp_fn.'($a["'.$field.'()"], $b["'.$field.'"]);
                    }else return 0;
                ');
      } else {
        $fn = create_function('$a,$b', '
                    if(is_object($a) && is_object($b)){
                        return '.$strcmp_fn.'($b->'.$field.'(), $a->'.$field.'());
                    }else if(is_array($a) && is_array($b)){
                        return '.$strcmp_fn.'($b["'.$field.'()"], $a["'.$field.'()"]);
                    }else return 0;
                ');
      }
      usort($arr, $fn);
      return true;
    } else {
      return false;
    }
  }
  
  /**
   * Get a subset of an input array
   * 
   * @param array $arr
   * @param integer $start
   * @param integer $length
   */
  private function limit(&$arr, $start, $length)
  {
    $arr = array_slice($arr, $start, $length);
  }

  /**
   * Search for a given search term within the given properties
   * of an array of objects
   * 
   * @param array $objects
   * @param string $search
   * @param array $properties
   */
  private function search($objects, $search, $properties)
  {
    $results = array();

    $search = strtolower($search);
    
    $regex = "/^{$search}/";

    if(is_null($objects) || count($objects) == 0){
      return $results;
    }
    
    foreach($objects as $object){

      foreach($properties as $property){

        $getter = 'get' . ucfirst($property);

        $value = strtolower($object->$getter());

        preg_match($regex, $value, $matches);

        if(count($matches) > 0){
          $results[] = $object;
          break;
        }
      }
    }

    return $results;
  }
}
