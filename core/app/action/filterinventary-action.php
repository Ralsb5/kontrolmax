
<?php
$stock = StockData::getById($_GET["stock"]);
?>
<?php

$products = array();
//$products = ProductData::getAll();
$limit = 25;
$page = 1;
$nextproducts = array();
$prevproducts = array();

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


if(count($products)>0){
	?>
<div class="clearfix"></div>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Inventario</h3>

  </div><!-- /.box-header -->
  <div class="box-body">
  <table class="table table-bordered datatable table-hover">
	<thead>
		<th>Codigo</th>
		<th>Nombre</th>
		<th>Por Recibir</th>
		<th>Disponible</th>
		<th>Por Entregar</th>
		<th></th>
	</thead>
	<?php foreach($products as $product):
	$r=OperationData::getRByStock($product->id,$_GET["stock"]);
	$q=OperationData::getQByStock($product->id,$_GET["stock"]);
	$d=OperationData::getDByStock($product->id,$_GET["stock"]);
	?>
	<tr class="<?php if($q<=$product->inventary_min/2){ echo "danger";}else if($q<=$product->inventary_min){ echo "warning";}?>">
		<td><?php echo $product->code; ?></td>
		<td><?php echo $product->name; ?></td>
		<td>
			<?php echo $r; ?>
		</td>
		<td>
			<?php echo $q; ?>
		</td>
		<td>
			<?php echo $d; ?>
		</td>
		<td style="width:243px;">
<!--		<a href="index.php?view=input&product_id=<?php echo $product->id; ?>" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-circle-arrow-up"></i> Alta</a>-->

<?php if(Core::$user->kind==1):?>
    <a href="index.php?view=inventaryadd&product_id=<?php echo $product->id; ?>&stock=<?php echo $_GET["stock"];?>" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-plus"></i> Agregar</a>
    <a href="index.php?view=inventarysub&product_id=<?php echo $product->id; ?>&stock=<?php echo $_GET["stock"];?>" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-plus"></i> Quitar</a>
    <a href="index.php?view=history&product_id=<?php echo $product->id; ?>&stock=<?php echo $_GET["stock"];?>" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-time"></i> Historial</a>
<?php endif; ?>
		</td>
	</tr>
	<?php endforeach;?>
</table>

<?php if(count($prevproducts)>0):?>
	<a id="prev" class="btn btn-default"><i class="fa fa-arrow-left"></i> Anterior  </a>
	<script type="text/javascript">
		$("#prev").click(function(){

				$(".allfilterinventary").html("Cargando ...");
				$.get("./?action=filterinventary&stock=<?php echo $_GET['stock'];?>&page=<?php echo $page-1; ?>","",function(data){
				$(".allfilterinventary").html(data);
				//console.log(data);
			})
		})
	</script>
<?php endif; ?>
<?php if(count($nextproducts)>0):?>
	<a id="next" class="btn btn-default">Siguiente <i class="fa fa-arrow-right"></i> </a>
	<script type="text/javascript">
		$("#next").click(function(){

				$(".allfilterinventary").html("Cargando ...");
				$.get("./?action=filterinventary&stock=<?php echo $_GET['stock'];?>&page=<?php echo $page+1; ?>","",function(data){
				$(".allfilterinventary").html(data);
				//console.log(data);
			})
		})
	</script>
<?php endif; ?>


  </div><!-- /.box-body -->
</div><!-- /.box -->



<div class="clearfix"></div>

	<?php
}else{
	?>
	<div class="jumbotron">
		<h2>No hay productos</h2>
		<p>No se han agregado productos a la base de datos, puedes agregar uno dando click en el boton <b>"Agregar Producto"</b>.</p>
	</div>
	<?php
}

?>
<br><br><br><br><br><br><br><br><br><br>

<script type="text/javascript">
        function thePDF() {
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 40, 65);
        doc.setFontSize(18);
        doc.text("ESTADO DEL INVENTARIO: <?php echo $stock->name;?>", 40, 80);
        doc.setFontSize(12);
        doc.text("Usuario: <?php echo Core::$user->name." ".Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s");?> ", 40, 90);

var columns = [
//    {title: "Reten", dataKey: "reten"},
    {title: "Codigo", dataKey: "code"}, 
    {title: "Nombre del Producto", dataKey: "product"}, 
    {title: "Por Recibir", dataKey: "pr"}, 
    {title: "Disponible", dataKey: "disponible"}, 
    {title: "Por Enviar", dataKey: "pv"}, 
//    ...
];



var rows = [
  <?php foreach($products as $product):
	$r=OperationData::getRByStock($product->id,$_GET["stock"]);
	$q=OperationData::getQByStock($product->id,$_GET["stock"]);
	$d=OperationData::getDByStock($product->id,$_GET["stock"]);
  ?>
    {
      "code": "<?php echo $product->code; ?>",
      "product": "<?php echo $product->name; ?>",
      "pr": "<?php echo $r;?>",
      "disponible": "<?php echo $q;?>",
      "pv": "<?php echo $d; ?>",
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
//        doc.text("Header", 40, 30);
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
doc.save('inventary-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
}
<?php else:?>
doc.save('inventary-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
<?php endif; ?>


//doc.output("datauri");

        }
    </script>