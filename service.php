<?php
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class Vuelos extends Service
{
	/**
	 * Function executed when the service is called
	 *
	 * @return Response
	 */
	public function _main(Request $request)
	{

		$response = new Response();
		$response->setCache("day");
		$response->setResponseSubject("Aeropuertos de Cuba");
		$response->createFromTemplate("all.tpl", $this->busqueda('HAVANA'));
		return $response;
	
	}
	
	public function _aeropuerto(Request $request){
		$response= new Response();
		$response->setCache("day");
		$response->setResponseSubject("Aeropuertos de Cuba");
		$response->createFromTemplate("all.tpl", $this->busqueda($request->query));
		return $response;
	}
	private function busqueda($aero){

			$aeropuertos=[
				"HAVANA"=>array("codigo"=>"HAV","desc"=>"Aeropuerto Internacional Jose Marti. La Havana"),
				"VARADERO"=>array("codigo"=>"VRA","desc"=>"Aeropuerto Juan Gualberto Gomez. Varadero"),
				"CIEN_FUEGOS"=>array("codigo"=>"CFG","desc"=>"Aeropuerto Jaime Gonzales. Cien Fuegos"),
				"CAMAGUEY"=>array("codigo"=>"CMW","desc"=>"Aeropuerto Internacional Ignacio Agramonte. Camaguey"),
				"HOLGUIN"=>array("codigo"=>"HOG","desc"=>"Aeropuerto Internacional Frank Pais. Holguin"),
				"SANTA_CLARA"=>array("codigo"=>"SNU","desc"=>"Aeropuerto Internacional Abel Santamaria. Santa Clara"),
				"CAYO_COCO"=>array("codigo"=>"CCC","desc"=>"Aeropuerto Internacional Jardines del Rey. Cayo Coco"),
				"CAYO_LARGO"=>array("codigo"=>"CYO","desc"=>"Aeropuerto Internacional Vilo Acuna. Cayo Largo del Sur"),
				"SANTIAGO"=>array("codigo"=>"SCU","desc"=>"Aeropuerto Internacional Antonio Maceo. Santiago de Cuba"),
				"MANZANILLO"=>array("codigo"=>"MZO","desc"=>"Aeropuerto Internacional Sierra Maestra. MAnzanillo de Cuba")

			];

		$client = new Client();
			$guzzle = $client->getClient();
			$client->setClient($guzzle);

			// create a crawler
			$crawler_arrivals = $client->request('GET', "http://www.flightstats.com/go/weblet?guid=49e3481552e7c4c9:32c90f00:12769f08bd4:6218&weblet=status&action=AirportFlightStatus&airportCode=".$aeropuertos[$aero]["codigo"]."&airportQueryType=1");
			$crawler_departures = $client->request('GET', "http://www.flightstats.com/go/weblet?guid=49e3481552e7c4c9:32c90f00:12769f08bd4:6218&weblet=status&action=AirportFlightStatus&airportCode=".$aeropuertos[$aero]["codigo"]."&airportQueryType=2");

			$datos = array();
			//aeropuerto seleccionado
			$datos["selected"]=$aeropuertos[$aero]["desc"];
			//arrivals
			$datos["arrivals"]=$crawler_arrivals->filter('.tableListingTable > tr')->each(function(Crawler $item){
				return $item->filter('td:not(.header)')->each(function(Crawler $item2){
					return $item2->text();
				});
			});
			//departures
			$datos["departures"]=$crawler_departures->filter('.tableListingTable > tr')->each(function(Crawler $item){
				return $item->filter('td:not(.header)')->each(function(Crawler $item2){
					return $item2->text();
				});
			});
		return ["aeropuertos"=>$aeropuertos,"datos"=>$datos];
	}
	
}
