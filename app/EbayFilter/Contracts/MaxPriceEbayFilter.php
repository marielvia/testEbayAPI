<?php namespace App\EbayFilter\Contracts;

class MaxPriceEbayFilter extends EbayFilter{

    private $currentFilter;
    private $maxPrice;
    private $isMaxPrice;
    
	public function __construct($filterUrl){
		$this->isMaxPrice = isset($filterUrl['price_max'])?true:false;
	    if($this->isMaxPrice){
	    	$this->maxPrice = $filterUrl['price_max'];
			$this->currentFilter = parent::$filterNumber;
			parent::$filterNumber++;
	    }
	}
	
	public function createFilter(){
		$filterMaxPrice = '';
	    if($this->isMaxPrice){ //if the min price is sent in the url
			$filterMaxPrice = '&itemFilter('.$this->currentFilter.').name=MaxPrice';
			$filterMaxPrice .= '&itemFilter('.$this->currentFilter.').value=' . $this->maxPrice;
		}
		return $filterMaxPrice;
	}
}