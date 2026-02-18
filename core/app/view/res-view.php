<section class="content">
<div class="row">
	<div class="col-md-12">
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <?php if(Core::$user->kind==1):?>
    <li><a href="report/res-word.php">Word 2007 (.docx)</a></li>
    <li><a href="report/res-xlsx.php">Excel 2007 (.xlsx)</a></li>
  <?php endif; ?>
<li><a onclick="thePDF()" id="makepdf" class="">PDF (.pdf)</a></li>

  </ul>
</div>
		<h1><i class='glyphicon glyphicon-shopping-cart'></i> Compras</h1>
		<div class="clearfix"></div>
<form id="filterres">
  <input type="hidden" name="view" value="sells">
<div class="row">
  <div class="col-md-2">
    <label>Almacen</label>
    <select name="stock_id" class="form-control">
      <option value="">-- ALMACEN--</option>
      <?php foreach(StockData::getAll() as $stock):?>
        <option value="<?php echo $stock->id; ?>"><?php echo $stock->name; ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="col-md-2">
    <label>Fecha inicio</label>
    <input type="date" name="start_at" value="<?php echo date('Y-m-d'); ?>" required class="form-control">
  </div>
  <div class="col-md-2">
    <label>Fecha fin</label>
    <input type="date" name="finish_at" value="<?php echo date('Y-m-d'); ?>" required class="form-control">
  </div>
  <div class="col-md-2">
    <label>Aplicar Filtro</label><br>
    <input type="submit" value="Aplicar Filtro" class="btn btn-primary">
  </div>

</div>
</form>

<div class="allfilterres">
</div>


<script type="text/javascript">
  $(document).ready(function(){
    $.get("./?action=filterres",$("#filterres").serialize(),function(data){
      $(".allfilterres").html(data);
    });

    $("#filterres").submit(function(e){
      e.preventDefault();
    $.get("./?action=filterres",$("#filterres").serialize(),function(data){
      $(".allfilterres").html(data);
    });

    })
  });
</script>

  </div>
</div>
</section>