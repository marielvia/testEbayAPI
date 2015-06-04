<?php namespace App\EbayFilter\Contracts;

class SortingEbayFilter extends EbayFilter{

	private $sorting;

	public function __construct($filterUrl){
	    $this->sorting  = isset($filterUrl['sorting'])?$filterUrl['sorting']:"BestMatch";

	}

	public function createFilter(){
	    $filterSorting = '&sortOrder='.$this->sorting;

		return $filterSorting;
	}
}