<?php

/**
 * This file is part of the DataTable package
 * 
 * (c) Marc Roulias <marc@lampjunkie.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * This class wraps the data that needs to be returned from a DataTable->loadData() method
 * 
 * @package DataTable
 * @author	Marc Roulias <marc@lampjunkie.com>
 */
class DataTable_DataResult
{
  /**
   * The results to pass back to the DataTable rendering
   * 
   * This variable needs to be an array of objects
   * 
   * @var array
   */
  protected $data;
  
  /**
   * The total number of results
   * 
   * This is the total number of results that can be shown
   * in the datatable (before pagination)
   * 
   * @var integer
   */
  protected $numTotalResults;

  public function __construct($data, $numTotalResults)
  {
    $this->data = $data;
    $this->numTotalResults = $numTotalResults;
  }

  public function getData()
  {
    return $this->data;
  }

  public function getNumTotalResults()
  {
    return $this->numTotalResults;
  }
}
