<?php
$currency = ConfigurationData::getByPreffix("currency")->val;
//print_r($_GET);
?>
<?php if((isset($_GET["product_name"]) && $_GET["product_name"]!="") ):?>
<?php
$go = $_GET["go"];
$search  ="";
if($go=="code"){ $search=$_GET["product_code"]; 


}
else if($go=="name"){ $search=$_GET["product_name"]; }

$category = "";
if(isset($_GET["category_id"]) and $_GET["category_id"]!=""){
	$category = $_GET["category_id"];
}

$products = array();
if($category==""){
$products = ProductData::getLike($search);
}else{
$products = ProductData::getLikeCat($search,$category);

}

if(count($products)>0){
	?>
<h3>Resultados de la Busqueda</h3>
<div class="box box-primary">
<table class="table table-bordered table-hover">
	<thead>
		<th>Codigo</th>
		<th>Nombre</th>
		<th>Unidad</th>
		<th>Tipo</th>
		<th>En inventario</th>
		<th>Cantidad/ Desc. x unidad</th>
	</thead>
	<?php
$products_in_cero=0;
	 foreach($products as $product):
$q= OperationData::getQByStock($product->id,StockData::getPrincipal()->id);
	?>
	<?php 
	if($product->kind==2||$q>0):?>
		
	<tr class="<?php if($product->kind==1&&$q<=$product->inventary_min){ echo "danger"; }?>">
		<td style="width:80px;"><?php echo $product->code; ?></td>
		<td><?php echo $product->name; ?></td>
		<td><?php echo $product->unit; ?></td>
<td>
  <?php
if($product->kind==1){
  echo "<span class='label label-info'>Producto</span>";
}else if($product->kind==2){
  echo "<span class='label label-success'>Servicio</span>";

}
  ?>


</td>

		
		<td>
			<?php 
			if($product->kind==1){
			echo $q; 
			}else if($product->kind==2){
				echo "";
			}
			?>
		</td>
		<td style="width:310px;">
		<form method="post" action="index.php?view=addtocart" id="addtocart<?php echo $product->id;?>">
			<select class="form-control" name="price">
				<option value="">- PRECIO -</option>
				<option value="<?php echo $product->price_out; ?>">$ <?php echo $product->price_out; ?></option>
				<option value="<?php echo $product->price_out2; ?>">$ <?php echo $product->price_out2; ?></option>
				<option value="<?php echo $product->price_out3; ?>">$ <?php echo $product->price_out3; ?></option>
			</select>
		<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">

<div class="input-group">
		<input type="" class="form-control" style="width: 100px;" required id="sell_q<?php echo $product->id;?>" name="q" placeholder="Cantidad ...">
		<input type="" class="form-control" style="width:100px; " required id="sell_discount_<?php echo $product->id;?>" value="0" name="discount" placeholder="$ Descuento ...">
      <span class="input-group-btn">
		<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Agregar</button>
      </span>
    </div>


		</form></td>
	</tr>
<script>
		$("#addtocart<?php echo $product->id;?>").on("submit",function(e){
		e.preventDefault();
			$.post("./?view=addtocart",$("#addtocart<?php echo $product->id;?>").serialize(),function(data){
				dx = null;
				$.get("./?action=cartofsell",null,function(data2){
					$("#cartofsell").html(data2);
					dx = data2;
				});
					$("#cartofsell").html(dx);

			});
		$("#sell_q<?php echo $product->id;?>").val("");

	});
</script>
<?php else:$products_in_cero++;
?>
<?php  endif; ?>
	<?php endforeach;?>
</table>
</div>
<?php if($products_in_cero>0){ 
if(Core::$user->kind==1){
	echo "<p class='alert alert-warning'>Se omitieron <b>$products_in_cero productos</b> que no tienen existencias en el inventario. <a href='index.php?view=inventary&stock=".StockData::getPrincipal()->id."'>Ir al Inventario</a></p>"; }
}
?>

	<?php
}else{
	echo "<br><p class='alert alert-danger'>No se encontro el producto</p>";
}
?>
<script>

</script>
<?php elseif( (isset($_GET["product_code"]) && $_GET["product_code"]!="")):
	$product = ProductData::getByBarcode($_GET["product_code"]);

?>
<?php if($product!=null):?>
<script>
	//	e.preventDefault();
			$.post("./?view=addtocart","product_id=<?php echo $product->id; ?>&q=1&discount=0&price=<?php echo $product->price_out; ?>",function(data){
				/* dx = null;
				$.get("./?action=cartofsell",null,function(data2){
					$("#cartofsell").html(data2);
					dx = data2;
				});
					$("#cartofsell").html(dx);
					*/
//console.log(data);
window.location = window.location.href;
			});
		//$("#sell_q<?php echo $product->id;?>").val("");

</script>
<?PHP endif; ?>
<?php else:
?>

<?php endif; ?>
