<section class="content">
<div class="row">
	<div class="col-md-12">


		<h1><i class='fa fa-archive'></i> Corte de Caja #<?php echo $_GET["id"]; ?></h1>
<!-- Single button -->
<div class="btn-group">
<a href="./index.php?view=boxhistory" class="btn btn-default"><i class="fa fa-arrow-left"></i> Historial</a>
<div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  <ul class="dropdown-menu pull-right" role="menu">
    <li><a href="report/box-word.php?id=<?php echo $_GET["id"];?>">Word 2007 (.docx)</a></li>
  </ul>
</div>
</div>
		<div class="clearfix"></div>
<h1>Ventas</h1>

<?php
$products = SellData::getByBoxId($_GET["id"]);
if(count($products)>0){
$total_total = 0;
?>
<br>
<div class="box box-primary">
<table class="table table-bordered table-hover	">
	<thead>
		<th></th>
		<th>Total</th>
		<th>Fecha</th>
	</thead>
	<?php foreach($products as $sell):?>

	<tr>
		<td style="width:30px;">
<a href="./index.php?view=onesell&id=<?php echo $sell->id; ?>" class="btn btn-default btn-xs"><i class="fa fa-arrow-right"></i></a>			


<?php
$operations = OperationData::getAllProductsBySellId($sell->id);
?>
</td>
		<td>

<?php
		$total_total += $sell->total-$sell->discount;
		echo "<b>".Core::$symbol." ".number_format($sell->total-$sell->discount,2,".",",")."</b>";

?>			

		</td>
		<td><?php echo $sell->created_at; ?></td>
	</tr>

<?php endforeach; ?>

</table>
</div>
<h1>Total: <?php echo Core::$symbol." ".number_format($total_total,2,".",","); ?></h1>
	<?php
}else {

?>
	<div class="jumbotron">
		<h2>No hay ventas</h2>
		<p>No se ha realizado ninguna venta.</p>
	</div>

<?php } ?>


<h1>Compras</h1>

<?php
$products = SellData::getResByBoxId($_GET["id"]);
if(count($products)>0){
$total_total = 0;
?>
<br>
<div class="box box-primary">
<table class="table table-bordered table-hover	">
	<thead>
		<th></th>
		<th>Total</th>
		<th>Fecha</th>
	</thead>
	<?php foreach($products as $sell):?>

	<tr>
		<td style="width:30px;">
<a href="./index.php?view=onesell&id=<?php echo $sell->id; ?>" class="btn btn-default btn-xs"><i class="fa fa-arrow-right"></i></a>			


<?php
$operations = OperationData::getAllProductsBySellId($sell->id);
?>
</td>
		<td>

<?php
		$total_total += $sell->total-$sell->discount;
		echo "<b>".Core::$symbol." ".number_format($sell->total-$sell->discount,2,".",",")."</b>";

?>			

		</td>
		<td><?php echo $sell->created_at; ?></td>
	</tr>

<?php endforeach; ?>

</table>
</div>
<h1>Total: <?php echo Core::$symbol." ".number_format($total_total,2,".",","); ?></h1>
	<?php
}else {

?>
	<div class="jumbotron">
		<h2>No hay ventas</h2>
		<p>No se ha realizado ninguna venta.</p>
	</div>

<?php } ?>

<h2>Gastos</h2>
		<?php
		$users = SpendData::getSpendsByBoxId($_GET["id"]);
		if(count($users)>0){
			// si hay usuarios
			$total = 0;
			?>
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Gastos</h3>
                </div><!-- /.box-header -->
			<table class="table table-bordered table-hover">
			<thead>
				<th>Tipo</th>
			<th>Concepto</th>
			<th>Costo</th>
			<th>Fecha</th>
			</thead>
			<?php
			foreach($users as $user){
				?>
				<tr>
					<td><?php 
					if($user->kind==1){ echo "<span class='label label-success'>Gasto</span>"; } 
					else if($user->kind==2){ echo "<span class='label label-info'>Devolucion</span>"; } 

					?></td>
				<td><?php echo $user->name; ?></td>
				<td><?php echo Core::$symbol; ?> <?php echo number_format($user->price,2,".",","); ?></td>
				<td><?php echo $user->created_at; ?></td>
				</tr>
				<?php
				$total+=$user->price;

			}

echo "</table>";
echo "<div class='box-body'><h3>Gasto Total : ".Core::$symbol." ".number_format($total,2,".",",")."</div></h3>";
echo "</div>";

		}else{
			echo "<p class='alert alert-danger'>No hay Gastos</p>";
		}
		?>
	<h2>Depositos</h2>
		<?php
		$users = SpendData::getDepsByBoxId($_GET["id"]);
		if(count($users)>0){
			// si hay usuarios
			$total = 0;
			?>
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Depositos</h3>
                </div><!-- /.box-header -->
			<table class="table table-bordered table-hover">
			<thead>
				<th>Tipo</th>
			<th>Concepto</th>
			<th>Costo</th>
			<th>Fecha</th>
			</thead>
			<?php
			foreach($users as $user){
				?>
				<tr>
					<td><?php 
					if($user->kind==3){ echo "<span class='label label-success'>Deposito</span>"; } 

					?></td>
				<td><?php echo $user->name; ?></td>
				<td><?php echo Core::$symbol; ?> <?php echo number_format($user->price,2,".",","); ?></td>
				<td><?php echo $user->created_at; ?></td>
				</tr>
				<?php
				$total+=$user->price;

			}

echo "</table>";
echo "<div class='box-body'><h3>Deposito Total : ".Core::$symbol." ".number_format($total,2,".",",")."</div></h3>";
echo "</div>";

		}else{
			echo "<p class='alert alert-danger'>No hay Depositos</p>";
		}
		?>
	</div>
</div>
</section></section>