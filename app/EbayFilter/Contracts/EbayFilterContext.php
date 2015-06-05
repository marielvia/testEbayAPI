<?php namespace App\EbayFilter\Contracts;

use App\EbayFilter\Contracts\KeywordEbayFilter;
use App\EbayFilter\Contracts\MaxPriceEbayFilter;
use App\EbayFilter\Contracts\MinPriceEbayFilter;
use App\EbayFilter\Contracts\SortingEbayFilter;
use App\EbayFilter\Contracts\EbayFilter;

class EbayFilterContext{

    public function createFilter(EbayFilter $ebayFilter) {
    	return $ebayFilter->createFilter();
    }
}