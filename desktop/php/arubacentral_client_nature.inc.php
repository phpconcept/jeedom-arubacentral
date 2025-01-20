<?php

/*
* Ce fichier est automatiquement inclu dans le fichier arubacentral.php
* 
* Il contient la partie configuration de l'équipement de type "device"
*/
?>

  <div class="form-group">
      <div class="col-sm-12">
             <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">{{Nature de l'objet}}</div>
      </div>
  </div>

  <div class="row form-group">
    <label class="col-sm-4 control-label">{{Type de Client :}}</label>
    <div class="col-sm-8">

      <select id="ac_device_type" class="cp_attr_device eqLogicAttr form-control" data-l1key="configuration" data-l2key="client_type">
          <?php
            foreach (arubacentral::acClientTypeList(false) as $v_id=>$v_item) {
              echo '<option value="'.$v_id.'" >'.$v_item['name'].'</option>';
            }
          ?>
      </select>

    </div>
  </div>

  <div class="row form-group">
    <label class="col-sm-4 control-label">{{Auto-découverte des commandes :}}</label>
    <div class="col-sm-8">
      <input type="checkbox" class="cp_attr_device eqLogicAttr form-control" data-l1key="configuration" data-l2key="client_cmd_auto_discover" checked/>      
    </div>
  </div>

  <div class="row form-group">
    <label class="col-sm-4 control-label">{{Détection d'absence :}}</label>
    <div class="col-sm-8">
      <input type="checkbox" id="device_missing_detection" class="cp_attr_device eqLogicAttr form-control" data-l1key="configuration" data-l2key="client_missing_detection"/>      
    </div>
  </div>

  <div id="device_missing_detection_div" style="display:none;">
  <div class="row form-group">
    <label class="col-sm-4 control-label">{{Absence max. (minutes) :}}</label>
    <div class="col-sm-8">
      <input type="text" class="cp_attr_device eqLogicAttr form-control" style="width: 100%;" data-l1key="configuration" data-l2key="client_missing_timeout" />      
    </div>
  </div>
  </div>



