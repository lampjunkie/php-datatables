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
 * This implementation of IBrowserService loads results from a database
 * 
 * @package demo
 * @author	Marc Roulias <marc@lampjunkie.com>
 */
class DbBrowserService implements IBrowserService
{
  /**
   * The PDO connection object
   * Enter description here ...
   * @var unknown_type
   */
  protected $connection;

  /**
   * Mapping of Browser property names to their database column names
   * 
   * @var array
   */
  protected $propertyToColumnMapping = array('renderingEngine' => 'rendering_engine',
											 'browser'         => 'browser',
											 'platform'        => 'platform',
											 'engineVersion'   => 'engine_version',
											 'cssGrade'        => 'css_grade');

  /**
   * Build the connection to the browsers database
   * 
   * @param string $host
   * @param string $database
   * @param string $user
   * @param string $password
   */
  public function __construct($host, $database, $user, $password)
  {
    try {
      $this->connection = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    } catch (PDOException $e){
      die($e->getMessage());
    }
  }

  /**
   * (non-PHPdoc)
   * @see IBrowserService::getAll()
   */
  public function getAll($offset, $num, $sort, $sortDirection = 'asc', $isCount = false)
  {
    $sort = $this->propertyToColumnMapping[$sort];

    // check if we just need a count of results
    if($isCount){

      $query = "SELECT count(id) as count FROM browsers";

      $statement = $this->connection->query($query);

      $result = $statement->fetch();

      return $result['count'];

    } else {

      $query = "SELECT
      			  *
			  	FROM
			  	  browsers
			  	ORDER BY 
			      {$sort} {$sortDirection}
		  	  	LIMIT
		  	      {$offset}, {$num}";

      $statement = $this->connection->query($query);
	  $results = $statement->fetchAll();

      $browsers = $this->hydrateResults($results);
      
      return $browsers;
    }
  }

  /**
   * (non-PHPdoc)
   * @see IBrowserService::searchAll()
   */
  public function searchAll($search, $columns, $offset, $num, $sort, $sortDirection = 'asc', $isCount = false)
  {
    $whereSqlParts = array();

    foreach($columns as $column){
      // get db column name
      $columnName = $this->propertyToColumnMapping[$column];
      $whereSqlParts[] = "{$column} LIKE '%{$search}%'";
    }

    // build where clause for all columns we are searching against
    $whereSql = implode($whereSqlParts, ' OR ');

    // check if we just need a count of results
    if($isCount){

      $query = "SELECT count(*) as count FROM browsers WHERE {$whereSql}";

      $statement = $this->connection->query($query);

      $result = $statement->fetch();

      return $result['count'];

    } else {

      $sort = $this->propertyToColumnMapping[$sort];

      $query = "SELECT *
  				FROM
  				  browsers
  			  	WHERE
  			      {$whereSql}
  			  	ORDER BY 
  			      {$sort} {$sortDirection}
  			  	LIMIT
  			      {$offset}, {$num}";

      $statement = $this->connection->query($query);
      $results = $statement->fetchAll();

      $browsers = $this->hydrateResults($results);

      return $browsers;
    }
  }
  
  /**
   * Hydrate a db result set into an array of Browser objects
   * 
   * @param array $results	Array of Browser objects
   */
  protected function hydrateResults($results)
  {
    $browsers = array();
    
	// convert result set into array of Browser objects
    foreach($results as $result){
      $browsers[] = new Browser($result['rendering_engine'], $result['browser'], $result['platform'], $result['engine_version'], $result['css_grade']);
    }
    
    return $browsers;
  }
}
