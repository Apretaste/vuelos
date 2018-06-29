<?php

/**
 * Apretaste Vuelos Service
 *
 * @author vilferalvarez
 * @version 2.0
 */


use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;


class Vuelos extends Service
{
	private $diccionario;
	/**
	 * Function executed when the service is called
	 *
	 * @return Response
	 */
	public function Vuelos(){
		$this->diccionario=array("Arrived"=>"Aterrizado","Scheduled"=>"Programado","Departed"=>"En vuelo","Landed"=>"Aterrizado","En Route"=>"En vuelo","Scheduled Delayed"=>"Programado Demorado","En  Route  Delayed"=>"En Vuelo demorado","Cancelled"=>"Cancelado");
	}
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

		date_default_timezone_set("America/Havana");


			$aero=str_replace(" ","_",$aero);

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

			for($i=1;$i<count($datos["arrivals"]);$i++){
				$datos["arrivals"][$i][4]=$this->traducir(trim($datos["arrivals"][$i][4]));
			}
			for($i=1;$i<count($datos["departures"]);$i++){
				$datos["departures"][$i][4]=$this->traducir(trim($datos["departures"][$i][4]));
			}
			
			$datos["fecha"]=date('d \d\e F \d\e Y');
		return ["aeropuertos"=>$aeropuertos,"datos"=>$datos];
	}

	/**
	 * Busqueda de Vuelo
	 *
	 * @author kumahacker
	 * @param string $q
	 *
	 * @return vuelo.tpl
	 */
	public function _vuelo(Request $request){
		date_default_timezone_set("America/Havana");
		$fecha=date("Y/n/j");
		$search=trim(trim($request->query,'^'));
		$vuelo=substr($search,0,strpos($search,"-"));
		$vuelo=strtolower(str_replace(" ","",$vuelo));

		$search=strtolower($vuelo.str_replace(" ","-",substr($search,strpos($search,"-")+1,strlen($search))));
		
		
		$url="https://es.aviability.com/estado-de-vuelo/estado-$search";
		
		$client = new Client();
			$guzzle = $client->getClient();
			$client->setClient($guzzle);
			$crawler=$client->request("GET",$url);
			$datos=array();
			$datos["titulo-principal"]=$crawler->filter('h1')->each(function(Crawler $item){
				return $item->text();
			});

			$datos["vuelo"]=strtoupper(trim($request->query,'^'));


			$datos["titulo-secundario"]=$crawler->filter('h2')->each(function(Crawler $item){
				return $item->text();
			});
			$datos["num_vuelo"]=$crawler->filter('.stn')->each(function(Crawler $item){
				return $item->text();
			});
			
			
			$datos["aerolinea"]=$crawler->filter('.sta')->each(function(Crawler $item){
				return $item->text();		
			});
		
			$datos["status-vuelo"]=$crawler->filter('.sts')->each(function(Crawler $item){
				return $item->text();
			});
			
			$datos["dep"]=$crawler->filter('div.stb')->each(function(Crawler $item){
				return $item->filter('stg.ste,.stg.ste div:first-child,.stg div:first-child')->each(function(Crawler $item2){
					return $item2->text();
				});
			});
			for ($i=0; $i < 3; $i++) { 
				$datos["hora-actual-1"][]=array_pop($datos["dep"][0]);
				array_pop($datos["dep"][1]);
			}

		
			$datos["arr"]=$crawler->filter('div.stb')->each(function(Crawler $item){
				return $item->filter('stg.ste,.stg.ste div:last-child,.stg div:last-child')->each(function(Crawler $item2){
					return $item2->text();
				});
			});
			for ($i=0; $i < 3; $i++) { 
				$datos["hora-actual-2"][]=array_pop($datos["arr"][0]);
				array_pop($datos["arr"][1]);
			}
			$duraciones=$crawler->filter('.stg.ste')->each(function(Crawler $item){
				return $item->text();
			});

			foreach($duraciones as $du){
				if(strpos($du,"uraci")){
					$datos["duracion"]=$du;
				}

			}
			/*
			if(count($datos["dep"])>1) {
				$datos["dep"]=array_slice($datos["dep"][0],0,10);
				$datos["dep"]=array_slice($datos["dep"][1],0,10);
				$datos["arr"]=array_slice($datos["arr"][0],0,10);
				$datos["arr"]=array_slice($datos["arr"][1],0,10);
			}*/
			
		
		//.stg.ste,div.stb:nth-child(6) .stg.ste div:first-child,div.stb:nth-child(6) .stg div:first-child

			$response = new Response();
		$response->setCache("day");
		$response->setResponseSubject("Vuelo ".$request->query);
		$response->createFromTemplate("vuelo.tpl", ["datos"=>$datos]);
		return $response;
		
	}
	public function traducir($estatus){
		if(array_key_exists($estatus,$this->diccionario)){
		return $this->diccionario[$estatus];
		}
		else{
			return $estatus;
		}
	}
}
