<?php namespace App\EbayFilter\Contracts;

//Base class for Strategy Pattern
abstract class EbayFilter {
	//filter index
	public static $filterNumber = 0;
    
    //method used to create the ebay filter
    abstract public function createFilter();

}