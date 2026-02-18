        <section class="content">
<div class="row">
	<div class="col-md-12">

		<h1>Depositos o Ajustes</h1>
<div class="btn-group ">
	<a href="index.php?view=newdeposit" class="btn btn-default"><i class='fa fa-th-list'></i> Nuevo Deposito</a>
</div><br><br>
		<?php

		$users = SpendData::getDepUnBoxed();
		if(count($users)>0){
			// si hay usuarios
			$total = 0;
			?>
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Depositos o Ajustes</h3>
                </div><!-- /.box-header -->

			<table class="table table-bordered table-hover">
			<thead>
				<th>Tipo</th>
			<th>Concepto</th>
			<th>Costo</th>
			<th>Fecha</th>
			<th></th>
			</thead>
			<?php
			foreach($users as $user){
				?>
				<tr>
					<td><?php 
					if($user->kind==1){ echo "<span class='label label-success'>Deposito</span>"; } 
					else if($user->kind==2){ echo "<span class='label label-info'>Devolucion</span>"; } 

					?></td>
				<td><?php echo $user->name; ?></td>
				<td><?php echo Core::$symbol; ?> <?php echo number_format($user->price,2,".",","); ?></td>
				<td><?php echo $user->created_at; ?></td>
				<td style="width:130px;"><a href="index.php?view=editdeposit&id=<?php echo $user->id;?>" class="btn btn-warning btn-xs">Editar</a> <a href="index.php?action=deldeposit&id=<?php echo $user->id;?>" class="btn btn-danger btn-xs">Eliminar</a></td>
				</tr>
				<?php
				$total+=$user->price;

			}

echo "</table>";
echo "<div class='box-body'><h1>Deposito Total : ".Core::$symbol." ".number_format($total,2,".",",")."</div></h1>";
echo "</div>";

		}else{
			echo "<p class='alert alert-danger'>No hay Depositos o Ajustes</p>";
		}


		?>


	</div>
</div>
</section>