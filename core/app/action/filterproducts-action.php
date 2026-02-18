<?php
$show_caducidad_field = false;
$show_image_field = false;
?>
<br>
<?php
$currency = ConfigurationData::getByPreffix("currency")->val;
$products = array();
//$products = ProductData::getAll();
$limit = 25;
$page = 1;
$nextproducts = array();
$prevproducts = array();
// print_r($_GET);
if(isset($_GET["q"]) && isset($_GET["category_id"])){
	$sql = "select * from product ";
	$extra = array();
	if($_GET["q"]!="" || $_GET["category_id"]!=""){
		if($_GET["q"]!=""){
			$extra[] =" (name like '%$_GET[q]%' or code like '%$_GET[q]%' or barcode like '%$_GET[q]%') ";
		}
		if($_GET["category_id"]!=""){
			$extra[] =" category_id=$_GET[category_id] ";
		}
 $sql2 = $sql." where ".implode(" and ", $extra);
	$products = ProductData::getAllBySQL($sql2);
	}else{
		$sql.=" limit 100";
	$products = ProductData::getAllBySQL($sql);
	}

//	$products = ProductData::getAllBySQL($sql);
}
else{
if(isset($_GET["page"]) && $_GET["page"]!=""){
	if(is_numeric($_GET["page"])){
		$page = $_GET["page"];
	}
}
if($page>1){

$products = ProductData::getAllByPage(($page-1)*$limit,$limit);
//print_r($products);
//echo  "prev ....";
$prevproducts = ProductData::getAllByPage(($page-2)*$limit,$limit);
//echo  "prev ....";
//echo  "next ....";
 $nextproducts = ProductData::getAllByPage($page*$limit,$limit);
//echo  "next ....";

}else{
$products = ProductData::getAllByPage(0,$limit);
$nextproducts = ProductData::getAllByPage($limit,$limit);
}
}


if(count($products)>0){
?>
<div class="box box-primary">
  <div class="box-header">
    <h3 class="box-title">Productos</h3>

  </div><!-- /.box-header -->
  <div class="box-body no-padding">
<div class="box-body table-responsive">
<table class="table  table-bordered datatable table-hover">
	<thead>
		<th>Codigo</th>
    <?php if($show_image_field):?>
		<th>Imagen</th>
  <?php endif;  ?>
		<th>Nombre</th>
		<th>Precio Entrada</th>
		<th>Precio Salida</th>
		<th>Precio Salida 2</th>
		<th>Precio Salida 3</th>
		<th>Categoria</th>
		<th>Minima</th>
    <?php if($show_caducidad_field):?>
    <th>F. caducidad</th>
  <?php endif; ?>
    <th>Tipo</th>
		<th>Activo</th>
		<th></th>
	</thead>
	<?php foreach($products as $product):?>
	<tr>
		<td><?php echo $product->code; ?></td>
    <?php if($show_image_field):?>
		<td>
			<?php if($product->image!=""):?>
				<img src="storage/products/<?php echo $product->image;?>" style="width:64px;">
			<?php endif;?>      
		</td>
      <?php endif;?>
		<td><?php echo $product->name; ?></td>
		<td><?php echo $currency; ?> <?php echo number_format($product->price_in,2,'.',','); ?></td>
		<td><?php echo $currency; ?> <?php echo number_format($product->price_out,2,'.',','); ?></td>
		<td><?php echo $currency; ?> <?php echo number_format($product->price_out2,2,'.',','); ?></td>
		<td><?php echo $currency; ?> <?php echo number_format($product->price_out3,2,'.',','); ?></td>

		<td><?php if($product->category_id!=null){echo $product->getCategory()->name;}else{ echo "<center>----</center>"; }  ?></td>
		<td><?php echo $product->inventary_min; ?></td>
    <?php if($show_caducidad_field):?>
    <td><?php echo $product->expire_at; ?></td>
  <?php endif; ?>
<td>
  <?php
if($product->kind==1){
  echo "<span class='label label-info'>Producto</span>";
}else if($product->kind==2){
  echo "<span class='label label-success'>Servicio</span>";

}
  ?>


</td>
		<td><?php if($product->is_active): ?><i class="fa fa-check"></i><?php endif;?></td>
		

		<td style="width:90px;">
		<a target="_blank" href="index.php?action=productqr&id=<?php echo $product->id; ?>" class="btn btn-xs btn-default"><i class="fa fa-qrcode"></i></a>
		<a href="index.php?view=editproduct&id=<?php echo $product->id; ?>" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-pencil"></i></a>
		<a href="index.php?view=delproduct&id=<?php echo $product->id; ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
		</td>
	</tr>
	<?php endforeach;?>
</table>
</div>
  </div><!-- /.box-body -->
</div><!-- /.box -->
<?php if(count($prevproducts)>0):?>
	<a id="prev" class="btn btn-default"><i class="fa fa-arrow-left"></i> Anterior  </a>
	<script type="text/javascript">
		$("#prev").click(function(){

				$(".allfilterproducts").html("Cargando ...");
				$.get("./?action=filterproducts&page=<?php echo $page-1; ?>","",function(data){
				$(".allfilterproducts").html(data);
				//console.log(data);
			})
		})
	</script>
<?php endif; ?>
<?php if(count($nextproducts)>0):?>
	<a id="next" class="btn btn-default">Siguiente <i class="fa fa-arrow-right"></i> </a>
	<script type="text/javascript">
		$("#next").click(function(){

				$(".allfilterproducts").html("Cargando ...");
				$.get("./?action=filterproducts&page=<?php echo $page+1; ?>","",function(data){
				$(".allfilterproducts").html(data);
				//console.log(data);
			})
		})
	</script>
<?php endif; ?>

	<?php
}else{
	?>
	<div class="alert alert-info">
		<h2>No hay productos</h2>
		<p>No se han agregado productos a la base de datos, puedes agregar uno dando click en el boton <b>"Agregar Producto"</b>.</p>
	</div>
	<?php
}

?>

<script type="text/javascript">
        function thePDF() {
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 40, 65);
        doc.setFontSize(18);
        doc.text("LISTADO DE PRODUCTOS", 40, 80);
        doc.setFontSize(12);
        doc.text("Usuario: <?php echo Core::$user->name." ".Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s");?> ", 40, 90);
var columns = [
    {title: "Id", dataKey: "id"}, 
    {title: "Codigo", dataKey: "code"}, 
    {title: "Nombre del Producto", dataKey: "name"}, 
    {title: "Precio de entrada", dataKey: "price_in"}, 
    {title: "Precio de Salida", dataKey: "price_out"}, 
];
var rows = [
  <?php foreach($products as $product):
  ?>
    {
      "id": "<?php echo $product->id; ?>",
      "code": "<?php echo $product->code; ?>",
      "name": "<?php echo $product->name; ?>",
      "price_in": "$ <?php echo number_format($product->price_in,2,'.',',');?>",
      "price_out": "$ <?php echo number_format($product->price_out,2,'.',',');?>",
      },
 <?php endforeach; ?>
];
doc.autoTable(columns, rows, {
    theme: 'grid',
    overflow:'linebreak',
    styles: { 
        fillColor: <?php echo Core::$pdf_table_fillcolor;?>
    },
    columnStyles: {
        id: {fillColor: <?php echo Core::$pdf_table_column_fillcolor;?>}
    },
    margin: {top: 100},
    afterPageContent: function(data) {
    }
});
doc.setFontSize(12);
doc.text("<?php echo Core::$pdf_footer;?>", 40, doc.autoTableEndPosY()+25);
<?php 
$con = ConfigurationData::getByPreffix("report_image");
if($con!=null && $con->val!=""):
?>
var img = new Image();
img.src= "storage/configuration/<?php echo $con->val;?>";
img.onload = function(){
doc.addImage(img, 'PNG', 495, 20, 60, 60,'mon');	
doc.save('products-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
}
<?php else:?>
doc.save('products-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
<?php endif; ?>
}
</script>

