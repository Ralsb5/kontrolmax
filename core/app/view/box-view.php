<section class="content">
<div class="row">
	<div class="col-md-12">
		<h1><i class='fa fa-archive'></i> Caja</h1>
<div class="btn-group ">
<a href="javascript:void()" id="process" class="btn btn-primary ">Crear Corte de Caja <i class="fa fa-check"></i></a>
<a href="./index.php?view=boxhistory" class="btn btn-primary "><i class="fa fa-clock-o"></i> Ver Historial</a>
</div>

<script type="text/javascript">
	$("#process").click(function(){
		x = confirm("Estas seguro que deseas continuar?")
		if(x){
			window.location = "./index.php?view=processbox";
		}
	});
</script>
		<p>Se va a crear un corte de caja para cada usuario/vendedor.</p>
		<p>Solo el administrador puede crear un corte de cada con ventas, compras, depositos y gastos.</p>
<!--		<p>Al procesar ventas se generara un corte de caja para todas las ventas del almacen: <b><?php echo StockData::getPrincipal()->name;?></b></p> -->
		<div class="clearfix"></div>

<h2>Ventas</h2>
<?php
$products = SellData::getSellsUnBoxed();
if(count($products)>0){
$total_total = 0;
?>
<br>
<div class="box box-primary">
<table class="table table-bordered table-hover	">
	<thead>
		<th></th>
		<th>Producto</th>
		<th>Total</th>
		<th>Vendedor</th>
		<th>Almacen</th>
		<th>Fecha</th>
	</thead>
	<?php foreach($products as $sell):?>

	<tr>
		<td style="width:30px;">
</td>
		<td>

<?php
$operations = OperationData::getAllProductsBySellId($sell->id);
echo count($operations);
?>
</td>
		<td>

<?php
		$total_total += $sell->total-$sell->discount;
		echo "<b>".Core::$symbol." ".number_format($sell->total-$sell->discount,2,".",",")."</b>";

?>			

		</td>
		<td>
			<?php
			$u = UserData::getById($sell->user_id);
			echo $u->name." ".$u->lastname;
			?>
		</td>
		<td><?php echo $sell->getStockTo()->name; ?></td>
		<td><?php echo $sell->created_at; ?></td>
	</tr>

<?php endforeach; ?>

</table>
</div>
<h3>Total: <?php echo Core::$symbol." ".number_format($total_total,2,".",","); ?></h3>
	<?php
}else {

?>
	<div class="jumbotron">
		<h2>No hay ventas</h2>
		<p>No se ha realizado ninguna venta.</p>
	</div>

<?php } ?>

<?php if(Core::$user->kind==1):?>


<h2>Compras</h2>
<?php
$products = SellData::getResUnBoxed();
if(count($products)>0){
$total_total = 0;
?>
<br>
<div class="box box-primary">
<table class="table table-bordered table-hover	">
	<thead>
		<th></th>
		<th>Producto</th>
		<th>Total</th>
		<th>Vendedor</th>
		<th>Almacen</th>
		<th>Fecha</th>
	</thead>
	<?php foreach($products as $sell):?>

	<tr>
		<td style="width:30px;">
</td>
		<td>

<?php
$operations = OperationData::getAllProductsBySellId($sell->id);
echo count($operations);
?>
</td>
		<td>

<?php
		$total_total += $sell->total-$sell->discount;
		echo "<b>".Core::$symbol." ".number_format($sell->total-$sell->discount,2,".",",")."</b>";

?>			

		</td>
		<td>
			<?php
			$u = UserData::getById($sell->user_id);
			echo $u->name." ".$u->lastname;
			?>
		</td>
		<td><?php echo $sell->getStockTo()->name; ?></td>
		<td><?php echo $sell->created_at; ?></td>
	</tr>

<?php endforeach; ?>

</table>
</div>
<h3>Total: <?php echo Core::$symbol." ".number_format($total_total,2,".",","); ?></h3>
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
		$users = SpendData::getAllUnBoxed();
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
		$users = SpendData::getDepUnBoxed();
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
<?php endif; ?>



<br><br><br><br><br><br><br><br><br><br>
	</div>
</div>
</section>