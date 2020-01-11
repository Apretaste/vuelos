<?php

use Apretaste\Notifications;
use Apretaste\Money;
use Apretaste\Person;
use Apretaste\Request;
use Apretaste\Response;
use Framework\Database;
use Apretaste\Challenges;
use Apretaste\Level;
use Framework\Utils;

class Service
{
	/**
	 * Choose an airport and arrivals/departures
	 *
	 * @param Request  $request
	 * @param Response $response
	 *
	 * @throws \Exception
	 * @author salvipascual
	 */
	public function _main(Request $request, Response &$response)
	{
		// get all airports
		$airports = $this->getAvailableAirports();

		// send data to the view
		$response->setCache('year');
		$response->setTemplate('home.ejs', ['airports' =>$airports]);

		Challenges::complete('view-vuelos', $request->person->id);
	}

	/**
	 * Check the airport's flights board
	 *
	 * @param Request  $request
	 * @param Response $response
	 *
	 * @throws \Framework\Alert
	 * @author salvipascual
	 */
	public function _board(Request $request, Response &$response)
	{
		// get airport details
		$airport = $request->input->data->airport;
		$type = $request->input->data->type;

		// get the airport's flights board
		if ($type==='arrivals') {
			$board = self::getArrivals($airport);
		}
		if ($type==='departures') {
			$board = self::getDepartures($airport);
		}

		// get the current airport name
		$airports = $this->getAvailableAirports();
		$key = array_search($airport, array_column($airports, 'code'), true);

		// clean the board array to save user's balance
		foreach ($board as $b) {
			unset($b->aircrafttype, $b->originCode, $b->originName, $b->destinationCode, $b->destinationName);
		}

		// get content for the view
		$content = [
				'type'  => $type,
				'code'  => $airport,
				'name'  => $airports[$key]['name'],
				'board' => $board
		];

		// send data to the view
		$response->setCache('hour');
		$response->setTemplate('board.ejs', $content);
	}

	/**
	 * Check the airport's flights board
	 *
	 * @author salvipascual
	 * @return array
	 */
	private function getAvailableAirports()
	{
		return [
			['code' => 'HAV', 'name' => 'José Martí (La Habana)'],
			['code' => 'VRA', 'name' => 'Juan Gualberto Gómez (Varadero)'],
			['code' => 'CFG', 'name' => 'Jaime Gonzáles (Cienfuegos)'],
			['code' => 'CMW', 'name' => 'Ignacio Agramonte (Camagüey)'],
			['code' => 'HOG', 'name' => 'Frank País (Holguín)'],
			['code' => 'SNU', 'name' => 'Abel Santamaría (Santa Clara)'],
			['code' => 'CCC', 'name' => 'Jardines del Rey (Cayo Coco)'],
			['code' => 'CYO', 'name' => 'Vilo Acuna (Cayo Largo del Sur)'],
			['code' => 'SCU', 'name' => 'Antonio Maceo (Santiago de Cuba)'],
			['code' => 'MZO', 'name' => 'Sierra Maestra (Manzanillo)'],
		];
	}

	/**
	 * Arrivals
	 *
	 * @param $airportCode
	 *
	 * @return array
	 */
	public static function getArrivals($airportCode)
	{
		// set startup params
		$flights = [];
		$params = ['airport' => $airportCode];

		// get flights that are on the way
		$res = self::call('Enroute', $params);

		// format outcome
		foreach ($res->EnrouteResult->enroute as $fl) {
			$flight = new stdClass();
			$flight->type = 'Enroute';
			$flight->number = $fl->ident;
			$flight->aircrafttype = $fl->aircrafttype;
			$flight->departuretime = date("H:i", $fl->filed_departuretime);
			$flight->originCode = $fl->origin;
			$flight->originName = $fl->originName;
			$flight->originCity = $fl->originCity;
			$flight->arrivaltime = date("H:i", $fl->estimatedarrivaltime);
			$flight->destinationCode = $fl->destination;
			$flight->destinationName = $fl->destinationName;
			$flight->destinationCity = $fl->destinationCity;
			$flights[] = $flight;
		}

		// get flights that arrived
		$res = self::call('Arrived', $params);

		// format outcome
		foreach ($res->ArrivedResult->arrivals as $fl) {
			$flight = new stdClass();
			$flight->type = 'Arrived';
			$flight->number = $fl->ident;
			$flight->aircrafttype = $fl->aircrafttype;
			$flight->departuretime = date("H:i", $fl->actualdeparturetime);
			$flight->originCode = $fl->origin;
			$flight->originName = $fl->originName;
			$flight->originCity = $fl->originCity;
			$flight->arrivaltime = date("H:i", $fl->actualarrivaltime);
			$flight->destinationCode = $fl->destination;
			$flight->destinationName = $fl->destinationName;
			$flight->destinationCity = $fl->destinationCity;
			$flights[] = $flight;
		}

		// sort by arrival time
		function cmp($a, $b)
		{
			return strcmp($a->arrivaltime, $b->arrivaltime);
		}
		usort($flights, "cmp");

		// return all flights
		return $flights;
	}

	/**
	 * Departures
	 *
	 * @param $airportCode
	 *
	 * @return array
	 */

	public static function getDepartures($airportCode)
	{
		// set startup params
		$flights = [];
		$params = ['airport' => $airportCode];

		// get flights that are on the way
		$res = self::call('Scheduled', $params);

		// format outcome
		foreach ($res->ScheduledResult->scheduled as $fl) {
			$flight = new stdClass();
			$flight->type = 'Scheduled';
			$flight->number = $fl->ident;
			$flight->aircrafttype = $fl->aircrafttype;
			$flight->departuretime = date("H:i", $fl->filed_departuretime);
			$flight->originCode = $fl->origin;
			$flight->originName = $fl->originName;
			$flight->originCity = $fl->originCity;
			$flight->arrivaltime = date("H:i", $fl->estimatedarrivaltime);
			$flight->destinationCode = $fl->destination;
			$flight->destinationName = $fl->destinationName;
			$flight->destinationCity = $fl->destinationCity;
			$flights[] = $flight;
		}

		// get flights that arrived
		$res = self::call('Departed', $params);

		// format outcome
		foreach ($res->DepartedResult->departures as $fl) {
			$flight = new stdClass();
			$flight->type = 'Departed';
			$flight->number = $fl->ident;
			$flight->aircrafttype = $fl->aircrafttype;
			$flight->departuretime = date("H:i", $fl->actualdeparturetime);
			$flight->originCode = $fl->origin;
			$flight->originName = $fl->originName;
			$flight->originCity = $fl->originCity;
			$flight->arrivaltime = date("H:i", $fl->estimatedarrivaltime);
			$flight->destinationCode = $fl->destination;
			$flight->destinationName = $fl->destinationName;
			$flight->destinationCity = $fl->destinationCity;
			$flights[] = $flight;
		}

		// sort by arrival time
		function cmp($a, $b)
		{
			return strcmp($a->arrivaltime, $b->arrivaltime);
		}
		usort($flights, "cmp");

		// return all flights
		return $flights;
	}

	/**
	 * Curl
	 *
	 * @param $entry
	 * @param $params
	 *
	 * @return mixed
	 */
	private static function call($entry, $params)
	{
		// get content from cache
		$cache = TEMP_PATH . "flights_$entry" . md5(json_encode($params)) . date("YmH") . ".cache";
		if (file_exists($cache)) {
			$content = unserialize(file_get_contents($cache));
		}

		// contact the API
		else {
			// get the API key from the configs
			$di = Phalcon\DI\FactoryDefault::getDefault();
			$config = $di->get('config')['flightaware'];
			$username = $config['username'];
			$apiKey = $config['apiKey'];

			// add params to the URL
			$url = "https://flightxml.flightaware.com/json/FlightXML2/$entry?" . http_build_query($params);

			// call the api
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $apiKey);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);

			// get results & close the connection
			$content = json_decode($result);
			curl_close($ch);

			// create the cache and return
			file_put_contents($cache, serialize($content));
		}

		// return content
		return $content;
	}
}
