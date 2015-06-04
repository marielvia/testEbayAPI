<?php namespace App\Services;

use App\EbayFilter\Contracts\KeywordEbayFilter;
use App\EbayFilter\Contracts\MaxPriceEbayFilter;
use App\EbayFilter\Contracts\MinPriceEbayFilter;
use App\EbayFilter\Contracts\SortingEbayFilter;

class Ebay {

	//get items from ebay api
	public static function getItems($filterUrl){
		// Construct the findItemsAdvanced HTTP GET call
		$url  =  config('ebay.url') . '?';
      	$url .= 'OPERATION-NAME=findItemsAdvanced';
      	$url .= '&SERVICE-VERSION=' . config('ebay.version');
		$url .= '&SECURITY-APPNAME='. config('ebay.app_id');
      	$url .= '&GLOBAL-ID=' . config('ebay.global_id');
		$url .= '&descriptionSearch=false'; //don't search the keyword(s) in the item description
		$url .= '&outputSelector=SellerInfo';		
		$url .= '&response-data-format=' . config('ebay.format');

		//get filter with keyword
		$keyword = new KeywordEbayFilter($filterUrl);
		$url  .= $keyword->createFilter();

		//get filter with min price
		$minPrice = new MinPriceEbayFilter($filterUrl);
		$url .= $minPrice->createFilter();

		//get filter with max price
		$maxPrice = new MaxPriceEbayFilter($filterUrl);
		$url .= $maxPrice->createFilter();

		//get filter with sorting
		$sorting = new SortingEbayFilter($filterUrl);
		$url .= $sorting->createFilter();

		$results = json_decode(file_get_contents($url),true);
		$count = 0;
		$data = [];

		if($results !== false && $results['findItemsAdvancedResponse'][0]['ack'][0] === 'Success') {
			$count = $results['findItemsAdvancedResponse'][0]['searchResult'][0]['@count'];
			if ($count>0) 
				$data = self::resultParser($results);
		}

		$listItems = array("result"=>$count,"items"=>$data);
		
		return $listItems;
	}

	private static function resultParser($results){
		$data = [];
	    $items = $results['findItemsAdvancedResponse'][0]['searchResult'][0]['item'];
		foreach ($items as $key => $item) {
			$data[$key]['provider'] = 'Ebay';
			$data[$key]['merchant_id'] = isset($item['sellerInfo'][0]['sellerUserName'][0])?$item['sellerInfo'][0]['sellerUserName'][0]:'';
			$data[$key]['itemId'] = $item['itemId'][0];
			$data[$key]['click_out_link'] = $item['viewItemURL'][0];
			$data[$key]['main_photo_url'] = isset($item['galleryURL']) ? $item['galleryURL'][0] : '';
			$data[$key]['price'] = $item['sellingStatus'][0]['currentPrice'][0]['__value__'];
			$data[$key]['price_currency'] = $item['sellingStatus'][0]['currentPrice'][0]['@currencyId'];
			$data[$key]['shipping_price'] = isset($item['shippingInfo'][0]['shippingServiceCost'][0]['__value__'])?$item['shippingInfo'][0]['shippingServiceCost'][0]['__value__']:'';
			$data[$key]['title'] = $item['title'][0];
			$data[$key]['valid_until'] = $item['listingInfo'][0]['endTime'][0];
		}
		return $data;
	}

}
