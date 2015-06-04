<?php namespace App\EbayFilter\Contracts;

class KeywordEbayFilter extends EbayFilter{
	private $keyword;

	public function __construct($filterUrl){
	    $this->keyword  = isset($filterUrl['keywords'])?$filterUrl['keywords']:"";

	}

	public function createFilter(){
		$filterKeywords = '';
	    $filterKeywords = '&keywords=' . urlencode($this->keyword);
		return $filterKeywords;
	}
}