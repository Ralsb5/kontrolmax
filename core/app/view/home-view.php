<?php
/**
 * Vista de Inicio - Dashboard Principal
 * Inventio Max v9.0
 */

// Verificamos que el usuario este autenticado
if(!isset($_SESSION["user_id"])){
    Core::redir("./");
    exit;
}

// Obtener datos para el dashboard
$user = Core::$user;
$today = date('Y-m-d');
$month_start = date('Y-m-01');
$month_end = date('Y-m-t');

// Obtener totales del dia
$sells_today = SellData::getAllByDateOp($today, $today, 2);
$total_ventas_hoy = 0;
foreach($sells_today as $sell){
    $total_ventas_hoy += ($sell->total - $sell->discount);
}

// Obtener ventas del mes
$sells_month = SellData::getAllByDateOp($month_start, $month_end, 2);
$total_ventas_mes = 0;
foreach($sells_month as $sell){
    $total_ventas_mes += ($sell->total - $sell->discount);
}

// Obtener productos con stock bajo
$products = ProductData::getAll();
$stock_principal = StockData::getPrincipal();
$productos_bajo_stock = 0;
if($stock_principal != null){
    foreach($products as $product){
        $q = OperationData::getQByStock($product->id, $stock_principal->id);
        if($q <= $product->inventary_min){
            $productos_bajo_stock++;
        }
    }
}

// Total de productos y clientes
$total_productos = count($products);
$total_clientes = count(PersonData::getClients());
?>

<section class="content-header">
    <h1>
        Dashboard
        <small>Panel de Control</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="./"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Dashboard</li>
    </ol>
</section>

<section class="content">
    <!-- Cuadros de informacion -->
    <div class="row">
        <!-- Ventas del Dia -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?php echo Core::$symbol; ?><?php echo number_format($total_ventas_hoy, 2); ?></h3>
                    <p>Ventas del Dia</p>
                </div>
                <div class="icon">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <a href="./?view=sells" class="small-box-footer">
                    Ver Ventas <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Ventas del Mes -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?php echo Core::$symbol; ?><?php echo number_format($total_ventas_mes, 2); ?></h3>
                    <p>Ventas del Mes</p>
                </div>
                <div class="icon">
                    <i class="fa fa-line-chart"></i>
                </div>
                <a href="./?view=sellreports" class="small-box-footer">
                    Ver Reportes <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Productos -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3><?php echo $total_productos; ?></h3>
                    <p>Total Productos</p>
                </div>
                <div class="icon">
                    <i class="fa fa-cubes"></i>
                </div>
                <a href="./?view=products" class="small-box-footer">
                    Ver Productos <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Alertas de Stock -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3><?php echo $productos_bajo_stock; ?></h3>
                    <p>Productos con Stock Bajo</p>
                </div>
                <div class="icon">
                    <i class="fa fa-warning"></i>
                </div>
                <a href="./?view=alerts" class="small-box-footer">
                    Ver Alertas <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Accesos Rapidos -->
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-bolt"></i> Accesos Rapidos</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <?php if(Core::$user->kind==1 || Core::$user->kind==3 || Core::$user->kind==4):?>
                        <div class="col-xs-6 col-md-4">
                            <a href="./?view=sell" class="btn btn-app btn-block">
                                <i class="fa fa-usd"></i> Nueva Venta
                            </a>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(Core::$user->kind==1 || Core::$user->kind==4):?>
                        <div class="col-xs-6 col-md-4">
                            <a href="./?view=re" class="btn btn-app btn-block">
                                <i class="fa fa-truck"></i> Nueva Compra
                            </a>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(Core::$user->kind==1):?>
                        <div class="col-xs-6 col-md-4">
                            <a href="./?view=newproduct" class="btn btn-app btn-block">
                                <i class="fa fa-plus"></i> Nuevo Producto
                            </a>
                        </div>
                        <div class="col-xs-6 col-md-4">
                            <a href="./?view=clients" class="btn btn-app btn-block">
                                <i class="fa fa-users"></i> Clientes
                            </a>
                        </div>
                        <div class="col-xs-6 col-md-4">
                            <a href="./?view=providers" class="btn btn-app btn-block">
                                <i class="fa fa-building"></i> Proveedores
                            </a>
                        </div>
                        <?php endif; ?>
                        
                        <div class="col-xs-6 col-md-4">
                            <a href="./?view=search" class="btn btn-app btn-block">
                                <i class="fa fa-search"></i> Buscar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informacion del Sistema -->
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-info-circle"></i> Informacion del Sistema</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr>
                            <td><strong>Usuario:</strong></td>
                            <td><?php echo $user->name . " " . $user->lastname; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tipo de Usuario:</strong></td>
                            <td>
                                <?php 
                                if($user->kind == 1) echo "Administrador";
                                else if($user->kind == 2) echo "Almacenista";
                                else if($user->kind == 3) echo "Vendedor";
                                else if($user->kind == 4) echo "Admin. de Sucursal";
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Sucursal Principal:</strong></td>
                            <td><?php echo ($stock_principal != null) ? $stock_principal->name : "No definida"; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Fecha Actual:</strong></td>
                            <td><?php echo date('d/m/Y H:i:s'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Total Clientes:</strong></td>
                            <td><?php echo $total_clientes; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php if(Core::$user->kind==1 || Core::$user->kind==4):?>
    <!-- Ultimas Ventas -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-shopping-cart"></i> Ultimas <?php echo min(10, count($sells_today)); ?> Ventas del Dia</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <?php if(count($sells_today) > 0): ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Estado Pago</th>
                                <th>Estado Entrega</th>
                                <th>Fecha</th>
                                <th>Accion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $count = 0;
                            foreach($sells_today as $sell): 
                                if($count >= 10) break;
                                $client = $sell->getPerson();
                                $pstatus = $sell->getP();
                                $dstatus = $sell->getD();
                            ?>
                            <tr>
                                <td><?php echo $sell->id; ?></td>
                                <td><?php echo ($client != null) ? $client->name : "Publico General"; ?></td>
                                <td><?php echo Core::$symbol . number_format($sell->total - $sell->discount, 2); ?></td>
                                <td>
                                    <?php if($pstatus != null): ?>
                                        <?php if($pstatus->id == 1): ?>
                                            <span class="label label-success"><?php echo $pstatus->name; ?></span>
                                        <?php elseif($pstatus->id == 2): ?>
                                            <span class="label label-warning"><?php echo $pstatus->name; ?></span>
                                        <?php else: ?>
                                            <span class="label label-danger"><?php echo $pstatus->name; ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($dstatus != null): ?>
                                        <?php if($dstatus->id == 1): ?>
                                            <span class="label label-success"><?php echo $dstatus->name; ?></span>
                                        <?php elseif($dstatus->id == 2): ?>
                                            <span class="label label-warning"><?php echo $dstatus->name; ?></span>
                                        <?php else: ?>
                                            <span class="label label-danger"><?php echo $dstatus->name; ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('H:i:s', strtotime($sell->created_at)); ?></td>
                                <td>
                                    <a href="./?view=onesell&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-info">
                                        <i class="fa fa-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                            <?php 
                                $count++;
                            endforeach; 
                            ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <div class="box-body">
                        <div class="callout callout-info">
                            <p>No hay ventas registradas el dia de hoy.</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="box-footer">
                    <a href="./?view=sells" class="btn btn-success">Ver Todas las Ventas</a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

</section>
