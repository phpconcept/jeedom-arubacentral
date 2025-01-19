<?php

/*
* Ce fichier est automatiquement inclu dans le fichier arubacentral.php
* 
* Il contient la partie configuration de l'équipement de type "device"
*/
?>

  
  <div class="row">
    <div class="col-sm-12">
      <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">{{Configuration de la OpenMQTTGateway}}</div>
    </div>
  </div>

    <div class="row form-group">
      <label class="col-sm-2 control-label">{{Nom du Topic MQTT :}}</label>
      <div class="col-sm-8">
        <input type="text" class="cp_attr_gateway eqLogicAttr form-control" style="width: 100%;" data-l1key="configuration" data-l2key="gateway_mqtt_topic" placeholder="{{Nom du topic pour cette gateway}}"/>
      </div>
    </div>


  <div class="row form-group">
    <label class="col-sm-2 control-label">{{Auto-découverte des propriétés :}}</label>
    <div class="col-sm-7">
      <input type="checkbox" class="cp_attr_gateway eqLogicAttr form-control" data-l1key="configuration" data-l2key="prop_auto_discover" checked/>      
    </div>
  </div>

  <div class="row form-group">
    <label class="col-sm-2 control-label">{{Online timeout (minutes) :}}</label>
    <div class="col-sm-7">
      <input type="text" class="cp_attr_gateway eqLogicAttr form-control" style="width: 100%;" data-l1key="configuration" data-l2key="online_timeout" placeholder="{{temps en minutes}}"/>
    </div>
  </div>


