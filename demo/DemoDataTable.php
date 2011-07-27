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
 * Demonstration of implementing DataTable_DataTable
 * 
 * This class shows how to extend and implement DataTable_DataTable
 * 
 * As a simple example this table is set up as an AJAX-enabled
 * table and pulls it's data from a given IBrowserService implementation
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
   * Set the IBrowserService implementation
   * 
   * This is the service object where we will pull our data from
   * 
   * @param IBrowserService $browserService
   */
  public function setBrowserService(IBrowserService $browserService)
  {
    $this->browserService = $browserService;
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
    // get the name of the sort property that was passed in
    $sortProperty = $this->config->getColumns()->get($request->getSortColumnIndex())->getSortKey();

    // check if a search term was passed in
    if($request->hasSearch()){
      
      // call the searchAll() service method
      $results = $this->browserService->searchAll($request->getSearch(), 
                                                  $this->getSearchableColumnNames(), 
                                                  $request->getDisplayStart(), 
                                                  $request->getDisplayLength(), 
                                                  $sortProperty, 
                                                  $request->getSortDirection());
      
      // get the total number of results (for pagination)
      $totalLength = $this->browserService->searchAll($request->getSearch(), 
                                                      $this->getSearchableColumnNames(), 
                                                      $request->getDisplayStart(), 
                                                      $request->getDisplayLength(), 
                                                      $sortProperty, 
                                                      $request->getSortDirection(), 
                                                      true);
    
    } else {
      
      // call the getAll() service method
      $results = $this->browserService->getAll($request->getDisplayStart(), 
                                               $request->getDisplayLength(), 
                                               $sortProperty, 
                                               $request->getSortDirection());
      
      // get the total number of results (for pagination)
      $totalLength = $this->browserService->getAll($request->getDisplayStart(), 
                                                   $request->getDisplayLength(), 
                                                   $sortProperty, 
                                                   $request->getSortDirection(), 
                                                   true);
    }

    // return the final result set
    return new DataTable_DataResult($results, $totalLength, $totalLength);
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
}