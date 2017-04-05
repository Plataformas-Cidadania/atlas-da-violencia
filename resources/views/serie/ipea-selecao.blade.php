<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sem título</title>
<style>
	.box-options{
		border: solid 1px #CCCCCC;
		border-radius: 3px;
		min-height: 500px;
		padding: 15px;
		margin: 10px;
		
	}
	.box-options-list{
				
	}
	.box-options-select{
		background-color: #FBFBFB;
	}
	.box-options ul{
		margin: 0;
		padding: 0;
		
	}
	.box-options li{
		margin: 0;
		padding: 3px;
		list-style: none;
		border-bottom: solid 1px #EEEEEE;
		cursor: pointer;
	}
	.box-options li:hover{
		background-color: #EEEEEE;
	}
	.box-options i{
		color: #333333;
	}
	.fa-options-active{
		color: #85B200!important;
	}
	.fa-options-times{
		float: right;
		color: #8C0000!important;
		font-size: 15px!important;
		margin-top: 3px;
	}
	
	.bar-prog{
		height: 5px;
		background-color: #EEEEEE;
		margin-top: 10px;
	}
	
	.fa-itens{
		margin: 0 10% 0 10%;
		float: left;
		color: #CCCCCC;
	}
	.fa-itens-active{
		color: #3498DB!important;
	}
	</style>
	
	
	
	
	<!-- Última versão CSS compilada e minificada -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Tema opcional -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">




	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	
	
	
	
</head>

<body>

<div class="row">
	<div class="col-sm-6 col-md-6">
		<div class="box-options box-options-list">
			<ul>
			  <h4><i class="fa fa-check" aria-hidden="true"></i> Selecione os Estados</h4><hr>
				<li><strong><i class="fa fa-square-o " aria-hidden="true"></i> Todos</strong></li>
				<li><i class="fa fa-check-square fa-options-active" aria-hidden="true"></i> Rio de Janeiro</li>
				<li><i class="fa fa-square-o " aria-hidden="true"></i> São Paulo</li>
				<li><i class="fa fa-square-o" aria-hidden="true"></i> Minas Gerais</li>
				<li><i class="fa fa-check-square fa-options-active" aria-hidden="true"></i> Acre</li>
			</ul>
		</div>
	</div>
	<div class="col-sm-6 col-md-6">
		<div class="box-options box-options-select">
			<ul>
			  <h4><i class="fa fa-check-square-o" aria-hidden="true"></i> Itens selecionados</h4><hr>
				<li><i class="fa fa-check-square fa-options-active" aria-hidden="true"></i> 
				Rio de Janeiro 
				<i class="fa fa-times fa-options-times" aria-hidden="true"></i>
				</li>
				<li><i class="fa fa-check-square fa-options-active" aria-hidden="true"></i> Acre</li>
			</ul>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<br><br><br>
		<i class="fa fa-check-circle fa-2x fa-itens fa-itens-active" aria-hidden="true"></i>
		<i class="fa fa-circle fa-2x fa-itens" aria-hidden="true"></i>
		<i class="fa fa-check-circle fa-2x fa-itens fa-itens-active" aria-hidden="true"></i>
		<i class="fa fa-circle fa-2x fa-itens" aria-hidden="true"></i>
		<div class="bar-prog"></div>
	</div>
	<div class="col-md-12">
		<br>
		<p class="bg-danger text-center" style="margin: 0 20%;"><br>Donec ullamcorper nulla non metus auctor fringilla. <br><br></p>
	</div>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- Última versão JavaScript compilada e minificada -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
