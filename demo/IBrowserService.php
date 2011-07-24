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
 * This interface defines all of the methods that a Browser Service needs to implement
 * 
 * @package demo
 * @author	Marc Roulias <marc@lampjunkie.com>
 */
interface IBrowserService
{
  /**
   * Get all of the browsers
   * 
   * This method will return an array of Browser objects if $isCount = false, otherwise
   * it will return the total number of results found
   * 
   * @param int 	$offset
   * @param int 	$num
   * @param string 	$sort
   * @param string 	$sortDirection
   * @param boolean $isCount
   * @return mixed	Array of Browser objects or int (if $isCount = true)
   */
  public function getAll($offset, $num, $sort, $sortDirection = 'asc', $isCount = false);
  
  
  /**
   * Search against all of the browsers
   * 
   * This method will return an array of Browser objects if $isCount = false, otherwise
   * it will return the total number of results found
   * 
   * @param string	$search
   * @param array 	$columns
   * @param int 	$offset
   * @param int 	$num
   * @param string 	$sort
   * @param string 	$sortDirection
   * @param boolean $isCount
   * @return mixed	Array of Browser objects or int (if $isCount = true)
   */
  public function searchAll($search, $columns, $offset, $num, $sort, $sortDirection = 'asc', $isCount = false);
}