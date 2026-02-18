
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Productos
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

<div class="row">
	<div class="col-md-12">

<div class="row">
  <div class="col-md-3">
<div class="btn-group">
  <a href="index.php?view=newproduct" class="btn btn-default">Agregar Producto</a>
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="report/products-word.php">Word 2007 (.docx)</a></li>
    <li><a href="report/products-xlsx.php">Excel (.xlsx)</a></li>
<li><a onclick="thePDF()" id="makepdf" class="">PDF (.pdf)</a></li>

  </ul>
</div>
</div>
</div>
<div class="col-md-9">

<form class="form-horizontal" id="filterproducts">
  <div class="form-group">
    <div class="col-sm-3">
      <input type="text" name="q" class="form-control" id="inputEmail3" placeholder="Nombre">
    </div>

    <div class="col-sm-3">
    <select name="category_id" class="form-control">
      <option value="">-- CATERGORIA--</option>
      <?php foreach(CategoryData::getAll() as $stock):?>
        <option value="<?php echo $stock->id; ?>"><?php echo $stock->name; ?></option>
      <?php endforeach; ?>
    </select>    
  </div>

    <div class="col-sm-2">
      <button type="submit" class="btn btn-primary">Buscar</button>
    </div>

  </div>


</form>


</div>
</div>

<div class="allfilterproducts">

	</div>
</div>
        </section><!-- /.content -->
<script type="text/javascript">
  $(document).ready(function(){
      $(".allfilterproducts").html("<i class='fa fa-refresh fa-spin'></i>");
    $.get("./?action=filterproducts",$("#filterproducts").serialize(),function(data){
      $(".allfilterproducts").html(data);
    });

    $("#filterproducts").submit(function(e){
      e.preventDefault();
      $(".allfilterproducts").html("<i class='fa fa-refresh fa-spin'></i>");
    $.get("./?action=filterproducts",$("#filterproducts").serialize(),function(data){
      $(".allfilterproducts").html(data);
    });

    })
  });
</script>

