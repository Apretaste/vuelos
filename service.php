<?php

// NOTE: This service uses "classes/Flights"

class Service
{
	/**
	 * Choose an airport and arrivals/departures
	 *
	 * @author salvipascual
	 * @param Request $request
	 * @param Response $response
	 */
	public function _main(Request $request, Response $response)
	{
		// get all airports
		$airports = $this->getAvailableAirports();

		// send data to the view
		$response->setCache("year");
		$response->setTemplate("home.ejs", ["airports"=>$airports]);
	}

	/**
	 * Check the airport's flights board
	 *
	 * @author salvipascual
	 * @param Request $request
	 * @param Response $response
	 */
	public function _board(Request $request, Response $response)
	{
		// get airport details
		$airport = $request->input->data->airport;
		$type = $request->input->data->type;

		// get the airport's flights board
		if($type == "arrivals") $board = Flights::getArrivals($airport);
		if($type == "departures") $board = Flights::getDepartures($airport);

		// get the current airport name
		$airports = $this->getAvailableAirports();
		$key = array_search($airport, array_column($airports, 'code'));

		// clean the board array to save user's balance
		foreach ($board as $b) {
			unset($b->aircrafttype);
			unset($b->originCode);
			unset($b->originName);
			unset($b->destinationCode);
			unset($b->destinationName);
		}

		// get content for the view
		$content = [
			"type" => $type,
			"code" => $airport,
			"name" => $airports[$key]['name'],
			"board" => $board
		];

		// send data to the view
		$response->setCache("hour");
		$response->setTemplate("board.ejs", $content);
	}

	/**
	 * Check the airport's flights board
	 *
	 * @author salvipascual
	 * @return Array
	 */
	private function getAvailableAirports() 
	{
		return [
			["code"=>"HAV", "name"=>"José Martí (La Habana)"],
			["code"=>"VRA", "name"=>"Juan Gualberto Gómez (Varadero)"],
			["code"=>"CFG", "name"=>"Jaime Gonzáles (Cienfuegos)"],
			["code"=>"CMW", "name"=>"Ignacio Agramonte (Camagüey)"],
			["code"=>"HOG", "name"=>"Frank País (Holguín)"],
			["code"=>"SNU", "name"=>"Abel Santamaría (Santa Clara)"],
			["code"=>"CCC", "name"=>"Jardines del Rey (Cayo Coco)"],
			["code"=>"CYO", "name"=>"Vilo Acuna (Cayo Largo del Sur)"],
			["code"=>"SCU", "name"=>"Antonio Maceo (Santiago de Cuba)"],
			["code"=>"MZO", "name"=>"Sierra Maestra (Manzanillo)"],
		];
	}
}
