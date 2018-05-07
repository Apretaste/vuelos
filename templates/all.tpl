<!DOCTYPE html>
<html>
<head>
	<title></title>

	<style type="text/css">

			#arrivals,#departures{
				/*border: 1px solid black;*/
			}
			th{
				background: silver;
			}
			#vuelos table tr:nth-child(odd){
				background: rgba(78, 226, 109,.2);
				min-height: 40px;
			}
			td{
				text-align: center;
			}
			#vuelos a{
				background-color: #5EBB47;
				border: 1px solid #5dbd00;
				border-radius: 3px;
				color: #FFFFFF;
				/*display: inline-block;*/
				font-family: sans-serif;
				font-size: 16px;
				line-height: 44px;
				text-align: center;
				width: 150px;
				padding: 1%;
				text-decoration: none;

			}
			#datos a{
				display: block;
				padding: .5%;

			}
			#datos a:active{
				opacity: .7;
			}
			#datos table{
				width: 100%;
			}
			a span{
				font-size: 1.4em;
			}



	</style>
</head>
<body>
	<h1>Vuelos para hoy en Aeropuertos de Cuba</h1>
	<h2>{$datos["fecha"]}</h2>
	<h2>{$datos["selected"]["descripcion"]}</h2>
	
	<div id="vuelos">
		{link href="VUELOS AEROPUERTO" caption="Cambiar aeropuerto  <span>&#9992</span>" desc="m:Cambiar aeropuerto [HAVANA,VARADERO,CIEN_FUEGOS,CAMAGUEY,HOLGUIN,SANTA_CLARA,CAYO_COCO,CAYO_LARGO,SANTIAGO,MANZANILLO]*" popup="true" wait="true"}
		{link href="VUELOS VUELO" caption="Buscar Vuelo <span>&#9906</span>" desc="Inroduzca codigo del vuelo" popup="true" wait="true"}


{space15}
	<div id="datos">
		<h2>LLegadas</h2>
	<table id="arrivals">
		
		{if count($datos["arrivals"])>1}
		<tr>
			<th>Vuelo</th>
			<th>Aerolinea</th>
			<th>Origen</th>
			<th>Llegada</th>
			<th>status</th>
		</tr>
		{foreach $datos["arrivals"] as $filas}
		<tr>
			{foreach $filas as $info}

				<td>
					{if $filas[0]==$info}
						{link href="VUELOS VUELO $info" caption=$info}
					{else}
						{$info}
					{/if}

				</td>
			{/foreach}
		</tr>
		{/foreach}
		{else}
		<p>No hay proximas llegadas para hoy en este aeropuerto</p>
		{/if}
		
	</table>
	
	{space10}
	<h2>Salidas</h2>
	<table id="departures">
		
		{if count($datos["departures"])>1}
		<tr>
			<th>Vuelo</th>
			<th>Aerolinea</th>
			<th>Destino</th>
			<th>Salida</th>
			<th>status</th>
		</tr>
		{foreach $datos["departures"] as $filas}
		<tr>
			{foreach $filas as $info}
				<td>
					{if $filas[0]==$info}
						{link href="VUELOS VUELO $info" caption=$info}
					{else}
						{$info}
					{/if}

				</td>
			{/foreach}
		</tr>
		{/foreach}
		{else}
			<p>No hay proximas salidas para hoy en este aeropuerto</p>
		{/if}
	</table>
	</div>
	</div>

</body>
</html>