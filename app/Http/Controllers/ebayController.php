<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Services\Ebay;


class ebayController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//get the parameters sent by the url
		$filterUrl = \Request::all();
		return \Response::json(Ebay::getItems($filterUrl));
	}

}
