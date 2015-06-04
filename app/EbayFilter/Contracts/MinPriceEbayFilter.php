<?php namespace App\EbayFilter\Contracts;

class MinPriceEbayFilter extends EbayFilter{

    private $currentFilter;
    private $isMinPrice;
    private $minPrice;
    
	public function __construct($filterUrl){
		$this->isMinPrice = isset($filterUrl['price_min'])?true:false;
	    if($this->isMinPrice){
	    	$this->minPrice = $filterUrl['price_min'];
			$this->currentFilter = parent::$filterNumber;
			parent::$filterNumber++;
	    }

	}
	
	public function createFilter(){
		$filterMinPrice = '';
	    if($this->isMinPrice){ //if the min price is sent in the url
			$filterMinPrice = '&itemFilter('.$this->currentFilter.').name=MinPrice';
			$filterMinPrice .= '&itemFilter('.$this->currentFilter.').value=' . $this->minPrice;
		}
		return $filterMinPrice;
	}
}