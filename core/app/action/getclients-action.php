        <?php 
$clients = PersonData::getClients();
    ?>


    <option value="">-- NINGUNO --</option>
    <?php foreach($clients as $client):?>
      <option value="<?php echo $client->id;?>"><?php echo $client->name." ".$client->lastname;?></option>
    <?php endforeach;?>
