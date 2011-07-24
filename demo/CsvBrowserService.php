<?php

/**
 * This file is part of the DataTable demo package
 * 
 * (c) Marc Roulias <marc@lampjunkie.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

include_once('IBrowserService.php');
include_once('Browser.php');

/**
 * This implementation of IBrowserService loads results from a given CSV file
 * 
 * @package demo
 * @author	Marc Roulias <marc@lampjunkie.com>
 */
class CsvBrowserService implements IBrowserService
{
  /**
   * The path of the CSV file to use
   * @var string
   */
  protected $file;
  
  /**
   * Set the path of the CSV file to load data from
   * 
   * @param string $file
   */
  public function __construct($file)
  {
    $this->file = $file;
  }

  /**
   * (non-PHPdoc)
   * @see IBrowserService::getAll()
   */
  public function getAll($offset, $num, $sort, $sortDirection = 'asc', $isCount = false)
  {
    $browsers = $this->loadData();

    // check if we just need a count of results
    if($isCount){

      return count($browsers);

    } else {

      // sort results by sort column passed in
      $this->sortObjectArray($sort, $browsers, $sortDirection);

      // limit the results based on parameters passed in
      $this->limit($browsers, $offset, $num);
			  	  
      return $browsers;
    }
  }

  /**
   * (non-PHPdoc)
   * @see IBrowserService::searchAll()
   */
  public function searchAll($search, $columns, $offset, $num, $sort, $sortDirection = 'asc', $isCount = false)
  { 
    // load all of the browsers
    $browsers = $this->loadData();
    
    // filter the data based on the search parameters given
    $browsers = $this->search($browsers, $search, $columns);
    
    // check if we just need a count of results
    if($isCount){

      return count($browsers);

    } else {

      // sort results by sort column passed in
      $this->sortObjectArray($sort, $browsers, $sortDirection);

      // limit the results based on parameters passed in
      $this->limit($browsers, $offset, $num);
			  	  
      return $browsers;
	}
  }
 
  /**
   * ==========================================================================
   * Utility Methods for dealing with CSV data, etc...
   * ==========================================================================
   */

  /**
   * Get an array of Browser objects
   * 
   * @return array
   */
  private function loadData()
  {
    $browsers = array();
    
    if (($handle = fopen($this->file, "r")) !== FALSE) {
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