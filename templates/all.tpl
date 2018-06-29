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
				min-width: 80px;
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
			.oculta{
				display: none;

			}

	</style>
</head>
<body>
	<h1>Vuelos para hoy en Aeropuertos de Cuba</h1>
	<h2>{$datos["fecha"]}</h2>
	<h2>{$datos["selected"]["descripcion"]}</h2>
	
	<div id="vuelos">
		{link href="VUELOS AEROPUERTO" caption="Cambiar aeropuerto  <span>&#9992</span>" desc="m:Cambiar aeropuerto [HAVANA,VARADERO,CIEN FUEGOS,CAMAGUEY,HOLGUIN,SANTA CLARA,CAYO COCO,CAYO LARGO,SANTIAGO,MANZANILLO]*" popup="true" wait="true"}
		<!--{link href="VUELOS VUELO" caption="Buscar Vuelo <span>&#9906</span>" desc="Inroduzca codigo del vuelo.Ejemplo CU 156" popup="true" wait="true"}-->

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
			<!--<th>Estatus</th>-->
		</tr>
		{foreach $datos["arrivals"] as $filas}
		<tr>
			{foreach $filas as $info}

				<td {if $info==$filas[count($filas)-1]} class="oculta"{/if}>
					{if $filas[0]==$info}
						{link href="VUELOS VUELO $info-{$filas[1]}" caption=$info wait="true"}
						
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
			<!--<th>Estatus</th>-->
		</tr>
		{foreach $datos["departures"] as $filas}
		<tr>
			{foreach $filas as $info}
				<td {if $info==$filas[count($filas)-1]} class="oculta"{/if}>
					{if $filas[0]==$info}
						{link href="VUELOS VUELO $info-{$filas[1]}" caption=$info}
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