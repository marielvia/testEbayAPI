<?php namespace App\Services;

use App\EbayFilter\Contracts\EbayFilterContext;
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

		$ebayContext = new EbayFilterContext;

		//get filter with keyword
		$url  .= $ebayContext->createFilter(new KeywordEbayFilter($filterUrl));

		//get filter with min price
		$url  .= $ebayContext->createFilter(new MinPriceEbayFilter($filterUrl));

		//get filter with max price
		$url  .= $ebayContext->createFilter(new MaxPriceEbayFilter($filterUrl));

		//get filter with sorting
		$url  .= $ebayContext->createFilter(new SortingEbayFilter($filterUrl));

		$results = json_decode(file_get_contents($url),true);
		$count = 0;
		$data = [];
		$errorMessage = "";

		if($results !== false && $results['findItemsAdvancedResponse'][0]['ack'][0] === 'Success') {
			$count = $results['findItemsAdvancedResponse'][0]['searchResult'][0]['@count'];
			if ($count>0) 
				$data = self::resultParser($results);
		}else{
			$errorMessage = isset($results['findItemsAdvancedResponse'][0]['errorMessage'][0]['error'][0]['message'])?$results['findItemsAdvancedResponse'][0]['errorMessage'][0]['error'][0]['message'][0]:"Unknown Error";
		}

		$listItems = array("result"=>$count,"items"=>$data,"error_message"=>$errorMessage);
		
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
