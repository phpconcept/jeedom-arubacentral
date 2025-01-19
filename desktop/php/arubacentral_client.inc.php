<?php

/*
* Ce fichier est automatiquement inclu dans le fichier arubacentral.php
* 
* Il contient la partie configuration de l'équipement de type "client"
*/
?>

  <div class="row">
    <div class="col-sm-12">
      <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">{{Propriétés MQTT de l'objet}}</div>
    </div>
  </div>




  <div class="row"><div class="col-sm-12">&nbsp;</div></div>



  <div class="row form-group">
    <label class="col-sm-2 control-label">{{MQTT Topic :}}</label>
    <div class="col-sm-7">
      <input type="text" class="cp_attr_client eqLogicAttr form-control" style="width: 100%;" data-l1key="configuration" data-l2key="client_mqtt_topic" placeholder="{{Nom du topic pour cet objet}}"/>      
    </div>
  </div>

  <br>
  <input type="hidden" id="last_rcv_mqtt" class="cp_attr_client eqLogicAttr "  data-l1key="status" data-l2key="last_rcv_mqtt" onChange="omg_last_rcv_mqtt_change();"></input>

    <div class="row form-group">
      <label class="col-sm-2 control-label">{{Dernier message reçu : }}</label>
      <span id="last_rcv_mqtt_display" class="col-sm-7"></span>
    </div>


