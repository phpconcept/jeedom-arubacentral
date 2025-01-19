<?php

/*
* Ce fichier est automatiquement inclu dans le fichier arubacentral.php
* 
* Il contient la partie configuration de l'équipement de type "device"
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
      <input type="text" class="cp_attr_device eqLogicAttr form-control" style="width: 100%;" data-l1key="configuration" data-l2key="device_mqtt_topic" placeholder="{{Nom du topic pour cet objet}}"/>      
    </div>
  </div>

  <input type="hidden" id="omg_mqtt_info" class="cp_attr_device eqLogicAttr "  data-l1key="status" data-l2key="mqtt_info" onChange="omg_mqtt_info_change();"></input>

  <div id="omg_mqtt_info_table">
    <!-- Sera remplie par javascript en fonction des mqtt_info -->
    <div class="row form-group">
      <label class="col-sm-2 control-label">One :</label>
      <span class="col-sm-7">Two</span>
    </div>
  </div>

  <br>
  <input type="hidden" id="device_last_rcv_mqtt" class="cp_attr_device eqLogicAttr "  data-l1key="status" data-l2key="device_last_rcv_mqtt" onChange="omg_last_rcv_mqtt_change('device');"></input>

    <div class="row form-group">
      <label class="col-sm-2 control-label">{{Dernier message reçu : }}</label>
      <span id="device_last_rcv_mqtt_display" class="col-sm-7"></span>
    </div>


