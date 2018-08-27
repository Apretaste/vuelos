<style type="text/css">
#detalle a{
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
#message{

}
#message p{
	font-size: 2.3em;
	text-align: center;
}
#message span{
	font-weight: bold;
	font-style: italic;
	color: lightgreen;
}

</style>
<div id="detalle">
	{link href="VUELOS" caption="Todos los vuelos"}
	<div id="message">
		<p>El vuelo <span>{$vuelo}</span> no existe</p>
	</div>
</div>