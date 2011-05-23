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
 * This class represents a Browser entity
 * 
 * @package demo
 * @author	Marc Roulias <marc@lampjunkie.com>
 */
class Browser
{
  public $renderingEngine;  	
  public $browser;  	
  public $platform;  	
  public $engineVersion;  	
  public $cssGrade;
  
  public function __construct($renderingEngine, $browser, $platform, $engineVersion, $cssGrade)
  {
    $this->renderingEngine = $renderingEngine;  	
    $this->browser = $browser;  	
    $this->platform = $platform;  	
    $this->engineVersion = $engineVersion;  	
    $this->cssGrade = $cssGrade;
  }

  public function getRenderingEngine()
  {
      return $this->renderingEngine;
  }

  public function setRenderingEngine($renderingEngine)
  {
      $this->renderingEngine = $renderingEngine;
  }

  public function getBrowser()
  {
      return $this->browser;
  }

  public function setBrowser($browser)
  {
      $this->browser = $browser;
  }

  public function getPlatform()
  {
      return $this->platform;
  }

  public function setPlatform($platform)
  {
      $this->platform = $platform;
  }

  public function getEngineVersion()
  {
      return $this->engineVersion;
  }

  public function setEngineVersion($engineVersion)
  {
      $this->engineVersion = $engineVersion;
  }

  public function getCssGrade()
  {
      return $this->cssGrade;
  }

  public function setCssGrade($cssGrade)
  {
      $this->cssGrade = $cssGrade;
  }
}