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
		$response->createFromTemplate("all.tpl", $this->busqueda('HAV'));
		return $response;

		
		
	}
	
	public function _aeropuerto(Request $request){

		$response= new Response();
		$response->setCache("day");
		$response->setResponseSubject("Aeropuertos de Cuba");
		$response->createFromTemplate("all.tpl", $this->busqueda($request));
		return $response;

		
	}
	private function busqueda($aero){
		$aeropuertos=json_encode([
			["caption"=>"Havana", "href"=>"VUELOS AEROPUERTO HAV"],
			["caption"=>"Cienfuegos", "href"=>"VUELOS AEROPUERTO CFG"],
			["caption"=>"Camaguey", "href"=>"VUELOS AEROPUERTO CMG"],
			["caption"=>"Holguin", "href"=>"VUELOS AEROPUERTO HOG"],
			["caption"=>"Santa Clara", "href"=>"VUELOS AEROPUERTO SNU"],
			["caption"=>"Cayo Coco", "href"=>"VUELOS AEROPUERTO CCC"],
			["caption"=>"Cayo Largo", "href"=>"VUELOS AEROPUERTO CYO"],
			["caption"=>"Santiago de Cuba", "href"=>"VUELOS AEROPUERTO SCU"],
			["caption"=>"Manzanillo de Cuba", "href"=>"VUELOS AEROPUERTO MZO"],
			]);
		$client = new Client();
			$guzzle = $client->getClient();
			$client->setClient($guzzle);

			// create a crawler
			$crawler_arrivals = $client->request('GET', "http://www.flightstats.com/go/weblet?guid=49e3481552e7c4c9:32c90f00:12769f08bd4:6218&weblet=status&action=AirportFlightStatus&airportCode=".$aero."&airportQueryType=1");
			$crawler_departures = $client->request('GET', "http://www.flightstats.com/go/weblet?guid=49e3481552e7c4c9:32c90f00:12769f08bd4:6218&weblet=status&action=AirportFlightStatus&airportCode=".$aero."&airportQueryType=2");

			$datos = array();
			
			$datos["arrivals"]=$crawler_arrivals->filter('.tableListingTable > tr')->each(function(Crawler $item){
				return $item->filter('td:not(.header)')->each(function(Crawler $item2){
					return $item2->text();
				});
			});
			$datos["departures"]=$crawler_departures->filter('.tableListingTable > tr')->each(function(Crawler $item){
				return $item->filter('td:not(.header)')->each(function(Crawler $item2){
					return $item2->text();
				});
			});
			
		

		return ["aeropuertos"=>$aeropuertos,"datos"=>$datos];
	}

}
