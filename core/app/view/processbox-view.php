<?php
$sells = null;
if(Core::$user->kind==1){
$sells = SellData::getSellsUnBoxed();
}else{
$sells = SellData::getSellsUnBoxedByUser(Core::$user->id);

}
$res = SellData::getResUnBoxed();

if(count($sells)){

	$box = new BoxData();
	$box->user_id = Core::$user->id; 
	$box->stock_id = StockData::getPrincipal()->id;
	$b = $box->add();
	foreach($sells as $sell){
		$sell->box_id = $b[1];
		$sell->update_box();
	}
	if(Core::$user->kind==1){
	foreach($res as $sell){
		$sell->box_id = $b[1];
		$sell->update_box();
	}
	$spends = SpendData::getAllUnBoxed();
	foreach($spends as $sell){
		$sell->box_id = $b[1];
		$sell->update_box();
	}
	$depos = SpendData::getDepUnBoxed();
	foreach($depos as $sell){
		$sell->box_id = $b[1];
		$sell->update_box();
	}
}


Core::alert("Corte de caja creado exitosamente!!");

	Core::redir("./index.php?view=b&id=".$b[1]);
}

?>