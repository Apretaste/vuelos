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
			$datos["selected"]["descripcion"]=$aeropuertos[$aero]["desc"];
			$datos["selected"]["codigo"]=$aeropuertos[$aero]["codigo"];
			
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
			$datos["fecha"]=date('d \d\e F \d\e Y');
		return ["aeropuertos"=>$aeropuertos,"datos"=>$datos];
	}
	public function _vuelo(Request $request){
		$fecha=date("Y/n/j");

		$param=str_replace(" ","/",$request->query);
		$param=trim(trim($param,'^'),'/');

		$url="https://www.flightstats.com/v2/flight-tracker/".$param."/".$fecha."/?utm_source=49e3481552e7c4c9:32c90f00:12769f08bd4:6218&utm_medium=cpc&utm_campaign=weblet";
		$client = new Client();
			$guzzle = $client->getClient();
			$client->setClient($guzzle);

			$crawler=$client->request("GET",$url);
			$datos=array();

			$datos["vuelo"]=trim($request->query,'^');
			$datos["destinos"]=$crawler->filter('.sc-krDsej.jzsYOo > div')->each(function(Crawler $item){
				return $item->filter('div div')->each(function(Crawler $item2){
					return $item2->text();
				});

			});
			$datos["departure_1"]=$crawler->filter('div.sc-fONwsr.lcarVJ:first-of-type  div.sc-VJcYb.isuJed div')->each(function(Crawler $item){
				return $item->text();
				
			});
			$datos["departure_2"]=$crawler->filter('div.sc-fONwsr.lcarVJ:first-of-type div.sc-hmXxxW.dLzkGK div.sc-TFwJa.dKHRdN')->each(function(Crawler $item){
				return $item->text();
				
			});
			$datos["arrival_1"]=$crawler->filter('div.sc-fONwsr.lcarVJ:last-of-type  div.sc-VJcYb.isuJed div')->each(function(Crawler $item){
				return $item->text();
				
			});
			$datos["arrival_2"]=$crawler->filter('div.sc-fONwsr.lcarVJ:nth-child(2) div.sc-hmXxxW.dLzkGK div.sc-TFwJa.dKHRdN')->each(function(Crawler $item){
				return $item->text();
				
			});
			/*$datos["tiempos"]=$crawler->filter('div.sc-fZwumE.fKxxqu')->each(function(Crawler $itemm){
				return $itemm->filter('div h4, div h5')->each(function( Crawler $i){
					return $i->text();
				}); 

			});*/

			$datos["status"]=$crawler->filter('div.sc-ipZHIp.bveXXR div')->each(function(Crawler $item){
				return $item->text();

			});
			



			$datos["tiempos"]=$crawler->filter('div.sc-iGPElx.NxqEP:first-child div.sc-fZwumE.fKxxqu'/*'div.sc-fZwumE.fKxxqu:first-of-type'*/)->each(function(Crawler $itemm){
				return $itemm->filter('div.sc-jXQZqI.geGMtw')->each(function(Crawler $i){
					return $i->filter('h4,h5')->each(function( Crawler $j){
						return $j->text();
					});
				}); 

			});

			$response = new Response();
		$response->setCache("day");
		$response->setResponseSubject("Vuelo ".$request->query);
		$response->createFromTemplate("vuelo.tpl", ["datos"=>$datos]);
		return $response;

	
	}
	
}
