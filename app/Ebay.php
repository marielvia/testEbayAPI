<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Ebay extends Model {

	private static $url = 'http://svcs.sandbox.ebay.com/services/search/FindingService/v1';
	private static $app_id = "WandoInt-217b-42d8-a699-e79808dd505e"; //api key
	private static $global_id = "EBAY-DE"; //e-bay region (eg. EBAY-DE)
	private static $version = '1.0.0'; //version of the API to use
	private static $format = 'json'; //format of the returned data 

	//get items from ebay api
	public static function getItems($keywords,$price_min,$price_max,$sorting){
		$i=0;		

		// Construct the findItemsAdvanced HTTP GET call
		$url  =  self::$url . '?';
      	$url .= 'OPERATION-NAME=findItemsAdvanced';
      	$url .= '&SERVICE-VERSION=' . self::$version;
		$url .= '&SECURITY-APPNAME='. self::$app_id;
      	$url .= '&GLOBAL-ID=' . self::$global_id;
      	$url .= '&keywords=' . urlencode($keywords);
		$url .= '&sortOrder='.$sorting;
		if(isset($price_min)){ //if the min price is sent in the url
			$url .= '&itemFilter('.$i.').name=MinPrice';
			$url .= '&itemFilter('.$i.').value=' . $price_min;
			$i++;
		}
		if(isset($price_min)){  //if the max price is sent in the url
			$url .= '&itemFilter('.$i.').name=MaxPrice';
			$url .= '&itemFilter('.$i.').value=' . $price_min;
		}
		$url .= '&descriptionSearch=false'; //don't search the keyword(s) in the item description
		$url .= '&outputSelector=SellerInfo';		
		$url .= '&response-data-format=' . self::$format;

		$results = json_decode(file_get_contents($url),true);
		$count = 0;
		$data = [];

		if($results !== false && $results['findItemsAdvancedResponse'][0]['ack'][0] === 'Success') {
			$count = $results['findItemsAdvancedResponse'][0]['searchResult'][0]['@count'];
			if ($count>0) {
			    $items = $results['findItemsAdvancedResponse'][0]['searchResult'][0]['item'];
				foreach ($items as $key => $item) {
					$data[$key]['provider'] = 'Ebay';
					$data[$key]['merchant_id'] = isset($item['sellerInfo'][0]['sellerUserName'][0])?$item['sellerInfo'][0]['sellerUserName'][0]:'';
					$data[$key]['itemId'] = $item['itemId'][0];
					$data[$key]['click_out_link'] = $item['viewItemURL'][0];
					$data[$key]['main_photo_url'] = isset($item['galleryURL']) ? $item['galleryURL'][0] : '';
					$data[$key]['price'] = $item['sellingStatus'][0]['currentPrice'][0]['__value__'];
					$data[$key]['price_currency'] = $item['sellingStatus'][0]['currentPrice'][0]['@currencyId'];
					$data[$key]['shipping_price'] = $item['shippingInfo'][0]['shippingServiceCost'][0]['__value__'];
					$data[$key]['title'] = $item['title'][0];
					$data[$key]['valid_until'] = $item['listingInfo'][0]['endTime'][0];
				}
			}
		}

		$listItems = array("result"=>$count,"items"=>$data);
		return $listItems;
	}

}
