<?php
$products = null;
if(Core::$user->kind==2||  Core::$user->kind==4){
$products = SellData::getResByStockId(Core::$user->stock_id);
}else{

$sql = "select * from sell ";
$whereparams = array();
$whereparams[] = " (operation_type_id=1 and p_id=1 and d_id=1) ";
if(isset($_GET["stock_id"]) && $_GET["stock_id"]!=""){
  $whereparams[] = " stock_to_id=$_GET[stock_id] ";
}
if(isset($_GET["start_at"]) && isset($_GET["finish_at"]) && $_GET["start_at"]!="" && $_GET["finish_at"]!=""){
  $whereparams[] = " ( date(created_at) between '$_GET[start_at]' and '$_GET[finish_at]' ) ";
}

 $sql2 = $sql." where ".implode(" and ", $whereparams)." order by created_at desc";

$products = SellData::getAllBySQL2($sql2);


}
if(count($products)>0){
	?>
<br>
<div class="box box-primary">
  <div class="box-body">
<table class="table table-bordered datatable table-hover	">
	<thead>
		<th></th>
		<th>Folio</th>
		<th>Pago</th>
		<th>Entrega</th>
		<th>Total</th>
		<th>Almacen</th>
		<th>Fecha</th>
		<th></th>
	</thead>
	<?php foreach($products as $sell):
	$operations = OperationData::getAllProductsBySellId($sell->id);
?>

	<tr>
		<td style="width:30px;"><a href="index.php?view=onere&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-eye-open"></i></a></td>
		<td>#<?php echo $sell->id; ?></td>
<td><?php echo $sell->getP()->name; ?></td>
<td><?php echo $sell->getD()->name; ?></td>
<td>
<?php
$total=0;
	foreach($operations as $operation){
		$product  = $operation->getProduct();
		$total += $operation->q*$product->price_in;
	}
		echo "<b>".Core::$symbol." ".number_format($total,2,".",",")."</b>";
?>			

    </td>     <td><?php if($sell->stock_to_id!=null){echo
$sell->getStockTo()->name ;} ?></td>     <td><?php echo $sell->created_at;
?></td>     <td style="width:130px;">    
    <a  target="_blank" href="ticket-re.php?id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default"><i class='fa fa-ticket'></i> Ticket</a>


    <a href="index.php?action=cancelre&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger">Cancelar</a>
<a href="index.php?view=delre&id=<?php echo
$sell->id; ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
</td>   </tr>

<?php endforeach; ?>

</table>
</div>
</div>

	<?php
}else{
	?>
	<div class="jumbotron">
		<h2>No hay datos</h2>
		<p>No se ha realizado ninguna operacion.</p>
	</div>
	<?php
}

?>
<br><br><br><br><br><br><br><br><br><br>

<script type="text/javascript">
    $(".datatable").DataTable({"pageLength":25});

        function thePDF() {
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 40, 65);
        doc.setFontSize(18);
        doc.text("COMPRAS", 40, 80);
        doc.setFontSize(12);
        doc.text("Usuario: <?php echo Core::$user->name." ".Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s");?> ", 40, 90);
var columns = [
    {title: "Id", dataKey: "id"}, 
    {title: "Proveedor", dataKey: "client"}, 
    {title: "Total", dataKey: "total"}, 
    {title: "Estado de pago", dataKey: "p"}, 
    {title: "Estado de entrega", dataKey: "d"}, 
    {title: "Almacen", dataKey: "stock"}, 
    {title: "Fecha", dataKey: "created_at"}, 
];
var rows = [
  <?php foreach($products as $sell):
  ?>
    {
      "id": "<?php echo $sell->id; ?>",
      "client": "<?php if($sell->person_id!=null){$c= $sell->getPerson();echo $c->name." ".$c->lastname;} ?>",
      "total": "<?php
$total= $sell->total-$sell->discount;
		echo Core::$symbol." ".number_format($total,2,".",",");
?>	",
      "p": "<?php echo $sell->getP()->name; ?>",
      "d": "<?php echo $sell->getD()->name; ?>",
      "stock": "<?php echo $sell->getStockTo()->name; ?>",
      "created_at": "<?php echo $sell->created_at; ?>",
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
doc.save('res-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
}
<?php else:?>
doc.save('res-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
<?php endif; ?>
}
</script>
