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
 * This class holds the values that are passed along in a DataTable AJAX request
 * 
 * @package DataTable
 * @author	Marc Roulias <marc@lampjunkie.com>
 */
class DataTable_Request
{
  /**
   * The current start position
   * @var integer
   */
  protected $displayStart;
  
  /**
   * The current display length
   * @var integer
   */
  protected $displayLength;
  
  /**
   * The index number of the column to sort on
   * @var integer
   */
  protected $sortColumnIndex;
  
  /**
   * The current sort direction
   * @var string
   */
  protected $sortDirection;

  /**
   * The search string
   * @var string
   */
  protected $search;
  
  /**
   * The 'sEcho' value that was passed in
   * @var integer
   */
  protected $echo;

  public function setDisplayStart($displayStart)
  {
    $this->displayStart = $displayStart;
  }

  public function getDisplayStart()
  {
    return $this->displayStart;
  }

  public function setDisplayLength($displayLength)
  {
    $this->displayLength = $displayLength;
  }

  public function getDisplayLength()
  {
    return $this->displayLength;
  }

  public function setSortColumnIndex($sortColumnIndex)
  {
    $this->sortColumnIndex = $sortColumnIndex;
  }

  public function getSortColumnIndex()
  {
    return $this->sortColumnIndex;
  }

  public function setSortDirection($sortDirection)
  {
    $this->sortDirection = $sortDirection;
  }

  public function getSortDirection()
  {
    return $this->sortDirection;
  }

  public function setSearch($search)
  {
    $this->search = $search;
  }

  public function getSearch()
  {
    return $this->search;
  }

  public function hasSearch()
  {
    return !(is_null($this->search) || $this->search == '');
  }
  
  public function setEcho($echo)
  {
    $this->echo = $echo;
  }

  public function getEcho()
  {
    return $this->echo;
  }

  /**
   * Hydrate the current object from a $_GET, $_POST, or $_REQUEST array
   * 
   * @param array $request
   */
  public function fromPhpRequest(array $request)
  {
    $this->setSortColumnIndex($request['iSortCol_0']);
    $this->setSortDirection($request['sSortDir_0']);
    $this->setDisplayLength($request['iDisplayLength']);
    $this->setDisplayStart($request['iDisplayStart']);
    $this->setEcho($request['sEcho']);
    $this->setSearch($request['sSearch']);
  }
}
