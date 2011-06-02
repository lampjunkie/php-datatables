<?php

/**
 * This file is part of the DataTable demo package
 * 
 * (c) Marc Roulias <marc@lampjunkie.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// include the Browser entity class
include('Browser.php');

/**
 * Demonstration of implementing DataTable_DataTable
 * 
 * This class shows how to extend and implement DataTable_DataTable
 * 
 * As a simple example this table is set up as an AJAX-enabled
 * table and pulls it's data from the local 'browsers.csv' file.
 * 
 */
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
            ->setTitle("Rendering Engine")
            ->setGetMethod("getRenderingEngine")
            ->setSortKey("renderingEngine")
            ->setIsSortable(true)
            ->setIsDefaultSort(true);

    // create second column
    $column2 = new DataTable_Column();
    $column2->setName("browser")
            ->setTitle("Browser")
            ->setGetMethod("getBrowser")
            ->setSortKey("browser")
            ->setIsSortable(true)
            ->setIsSearchable(true);

    // create third column
    $column3 = new DataTable_Column();
    $column3->setName("platform")
            ->setTitle("Platform(s)")
            ->setGetMethod("getPlatform")
            ->setSortKey("platform")
            ->setIsSortable(true)
            ->setIsDefaultSort(false);

    // create fourth column
    $column4 = new DataTable_Column();
    $column4->setName("engineVersion")
            ->setTitle("Engine Version")
            ->setGetMethod("getEngineVersion")
            ->setSortKey("engineVersion")
            ->setIsSortable(true)
            ->setIsDefaultSort(false);
    
    // create fifth column
    $column5 = new DataTable_Column();
    $column5->setName("cssGrade")
            ->setTitle("CSS Grade")
            ->setGetMethod("getCssGrade")
            ->setSortKey("cssGrade")
            ->setIsSortable(true)
            ->setIsDefaultSort(false);

    // create the actions column
    $column6 = new DataTable_Column();
    $column6->setName("actions")
            ->setTitle("Actions")
            ->setGetMethod("getActions");
    
    // create an invisible column
    $column7 = new DataTable_Column();
    $column7->setName("invisible")
            ->setTitle("Invisible")
            ->setIsVisible(false)
            ->setGetMethod("getInvisible");
    
    // create config
    $config = new DataTable_Config();
    
    // add columns to collection
    $config->getColumns()->add($column1)
                         ->add($column2)
                         ->add($column3)
                         ->add($column4)
                         ->add($column5)
                         ->add($column6)
                         ->add($column7);
     
    // build the language configuration
    $languageConfig = new DataTable_LanguageConfig();
    $languageConfig->setPaginateFirst("Beginning")
                   ->setPaginateLast("End")
                   ->setSearch("Find it:");

    // add LangugateConfig to the DataTableConfig object
    $config->setLanguageConfig($languageConfig);

    // set data table options
    $config->setClass("display")
           ->setDisplayLength(10)
           ->setIsPaginationEnabled(true)
           ->setIsLengthChangeEnabled(true)
           ->setIsFilterEnabled(true)
           ->setIsInfoEnabled(true)
           ->setIsSortEnabled(true)
           ->setIsAutoWidthEnabled(true)
           ->setIsScrollCollapseEnabled(false)
           ->setPaginationType(DataTable_Config::PAGINATION_TYPE_FULL_NUMBERS)
           ->setIsJQueryUIEnabled(false)
           ->setIsServerSideEnabled(true);

    // pass DataTable_Config to the parent
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
    $results = $this->loadFakeData('browsers.csv');

    // get total length of all results (emulate count(*) query)
    $totalLength = count($results);
    
    // search against object array if a search term was passed in
    if(!is_null($request->getSearch())){
      $results = $this->search($results, $request->getSearch(), $this->getSearchableColumnNames());
    }

    // get the count of the filtered results
    $filteredTotalLength = count($results);
    
    // sort results by sort column passed in
    $this->sortObjectArray($this->config->getColumns()->get($request->getSortColumnIndex())->getSortKey(), $results, $request->getSortDirection());

    // limit the results based on parameters passed in
    $this->limit($results, $request->getDisplayStart(), $request->getDisplayLength());
    
    // return the final result set
    return new DataTable_DataResult($results, $totalLength, $filteredTotalLength);
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
   * Format the data for the 'Actions' column
   * 
   * @param Browser $browser
   */
  protected function getActions(Browser $browser)
  {
    $html = "<a href=\"#\" onclick=\"alert('Viewing: {$browser->getBrowser()}');\">View</a>";
    $html .= ' | ';
    $html .= "<a href=\"#\" onclick=\"confirm('Delete {$browser->getBrowser()}?');\">Delete</a>";
    return $html;
  }
  
  /**
   * Format the data for the 'invisible' column
   * 
   * @param Browser $browser
   */
  protected function getInvisible(Browser $browser)
  {
    return 'invisible content: ' . $browser->getBrowser();
  }

  /**
   * Add a callback function for 'fnRowCallback'
   * 
   * @return string
   */
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

  /**
   * ==========================================================================
   * Utility Methods for Demo
   * ==========================================================================
   */

  /**
   * Get an array of Browser objects
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
