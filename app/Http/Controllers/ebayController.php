<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Ebay;


class ebayController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//get the parameters sent by the url
		$inputs = \Request::all();
	    $keywords  = isset($inputs['keywords'])?$inputs['keywords']:"";
	    $sorting   = isset($inputs['sorting'])?$inputs['sorting']:"BestMatch";	  
	    $price_min = isset($inputs['price_min'])?$inputs['price_min']:null;
	    $price_max = isset($inputs['price_max'])?$inputs['price_max']:null;

	    $listItems = Ebay::getItems($keywords,$price_min,$price_max,$sorting);
		
		return \Response::json($listItems);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
