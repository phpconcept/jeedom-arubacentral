
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */


$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
/*
 * Fonction pour l'ajout de commande, appellé automatiquement par plugin.template
 */
function addCmdToTable_OLD(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }
    var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
    tr += '<td>';
    tr += '<span class="cmdAttr" data-l1key="id" style="display:none;"></span>';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 140px;" placeholder="{{Nom}}">';
    tr += '</td>';
    tr += '<td>';
    tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
    tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
    tr += '</td>';
    tr += '<td>';
    if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fa fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
        tr += '<span class="cmdAttr" data-l1key="htmlstate"></span>';
    }
    tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
    tr += '</td>';
    tr += '</tr>';
    $('#table_cmd tbody').append(tr);
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    if (isset(_cmd.type)) {
        $('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
    }
    jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
}

/* Fonction permettant l'affichage des commandes dans l'équipement */
function addCmdToTable(_cmd) {
  if (!isset(_cmd)) {
    var _cmd = {configuration: {}};
  }
  if (!isset(_cmd.configuration)) {
    _cmd.configuration = {};
  }
  var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
  tr += '<td>';
  tr += '<div class="row">';
  tr += '<div class="col-sm-6">';
  tr += '<a class="cmdAction btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fa fa-flag"></i> Icône</a>';
  tr += '<span class="cmdAttr" data-l1key="display" data-l2key="icon" style="margin-left : 10px;"></span>';
  tr += '</div>';
  tr += '<div class="col-sm-6">';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="name">';
  tr += '</div>';
  tr += '</div>';
  tr += '<select class="cmdAttr form-control input-sm" data-l1key="value" style="display : none;margin-top : 5px;" title="La valeur de la commande vaut par défaut la commande">';
  tr += '<option value="">Aucune</option>';
  tr += '</select>';
  tr += '</td>';
  tr += '<td>';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="id" style="display : none;">';
  tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
  tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
  tr += '</td>';
  tr += '<td><input class="cmdAttr form-control input-sm" data-l1key="logicalId" value="0" style="width : 70%; display : inline-block;" placeholder="{{Commande}}"><br/>';
  tr += '</td>';
  
  tr += '<td>';
  
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="returnStateValue" placeholder="{{Valeur retour d\'état}}" style="width:48%;display:inline-block;">';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="returnStateTime" placeholder="{{Durée avant retour d\'état (min)}}" style="width:48%;display:inline-block;margin-left:2px;">';
  tr += '<select class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="updateCmdId" style="display : none;" title="Commande d\'information à mettre à jour">';
  tr += '<option value="">Aucune</option>';
  tr += '</select>';
  tr += '</td>';
  tr += '<td>';
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="width:30%;display:inline-block;">';
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="width:30%;display:inline-block;">';
  //tr += '<input class="cmdAttr form-control input-sm" data-l1key="unite" placeholder="Unité" title="{{Unité}}" style="width:30%;display:inline-block;margin-left:2px;">';
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="listValue" placeholder="{{Liste de valeur|texte séparé par ;}}" title="{{Liste}}">';
  tr += '<br>';
  tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label></span> ';
  tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
  tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="invertBinary"/>{{Inverser}}</label></span> ';
  tr += '</td>';
  tr += '<td>';
  tr += '<span class="cmdAttr" data-l1key="htmlstate"></span>'; 
  tr += '</td>';
  tr += '<td>';
  if (is_numeric(_cmd.id)) {
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
  }
  tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
  tr += '</td>';
  tr += '</tr>';
  $('#table_cmd tbody').append(tr);
  var tr = $('#table_cmd tbody tr').last();
  jeedom.eqLogic.buildSelectCmd({
    id: $('.eqLogicAttr[data-l1key=id]').value(),
    filter: {type: 'info'},
    error: function (error) {
      $('#div_alert').showAlert({message: '1'+error.message, level: 'danger'});
    },
    success: function (result) {
      tr.find('.cmdAttr[data-l1key=value]').append(result);
      tr.setValues(_cmd, '.cmdAttr');
      jeedom.cmd.changeType(tr, init(_cmd.subType));
    }
  });
}

/*
 * Display  list of the equipements in the plugin dashboard list
 */
var refresh_timeout;

function refreshDeviceList() {
  $('#device_list').load('index.php?v=d&plugin=arubacentral&modal=modal.device_list');
}

function cp_add_gateway() {}
$('.eqLogicAction[data-action=cp_add_gateway]').off('click').on('click', function () {
  bootbox.prompt("{{Nom de la gateway ?}}", function (result) {
    if (result !== null) {
      jeedom.eqLogic.save({
        type: eqType,
        eqLogics: [{name: result,configuration: '{"type":"gateway"}'}],
        error: function (error) {
          $('#div_alert').showAlert({message: '2'+error.message, level: 'danger'});
        },
        success: function (_data) {
          var vars = getUrlVars();
          var url = 'index.php?';
          for (var i in vars) {
            if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
              url += i + '=' + vars[i].replace('#', '') + '&';
            }
          }
          modifyWithoutSave = false;
          url += 'id=' + _data.id + '&saveSuccessFull=1';
          loadPage(url);
        }
      });
    }
  });
});

function cp_add_device() {}
$('.eqLogicAction[data-action=cp_add_device]').off('click').on('click', function () {
  bootbox.prompt("{{Nom du device ?}}", function (result) {
    if (result !== null) {
      jeedom.eqLogic.save({
        type: eqType,
        eqLogics: [{name: result,configuration: '{"type":"device"}'}],
        error: function (error) {
          $('#div_alert').showAlert({message: '3'+error.message, level: 'danger'});
        },
        success: function (_data) {
          var vars = getUrlVars();
          var url = 'index.php?';
          for (var i in vars) {
            if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
              url += i + '=' + vars[i].replace('#', '') + '&';
            }
          }
          modifyWithoutSave = false;
          url += 'id=' + _data.id + '&saveSuccessFull=1';
          loadPage(url);
        }
      });
    }
  });
});

function cp_add_client() {}
$('.eqLogicAction[data-action=cp_add_client]').off('click').on('click', function () {
  bootbox.prompt("{{Nom du client ?}}", function (result) {
    if (result !== null) {
      jeedom.eqLogic.save({
        type: eqType,
        eqLogics: [{name: result,configuration: '{"type":"client"}'}],
        error: function (error) {
          $('#div_alert').showAlert({message: '3'+error.message, level: 'danger'});
        },
        success: function (_data) {
          var vars = getUrlVars();
          var url = 'index.php?';
          for (var i in vars) {
            if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
              url += i + '=' + vars[i].replace('#', '') + '&';
            }
          }
          modifyWithoutSave = false;
          url += 'id=' + _data.id + '&saveSuccessFull=1';
          loadPage(url);
        }
      });
    }
  });
});

$("#omg_device_delete_all").off('click').on('click', function () {
  bootbox.confirm("{{Supprimer tous les objets ?}}", function(result){
    if (result) {    
        bootbox.confirm("{{Vous êtes vraiment - vraiment sûr ?}}", function(result2){
        if (result2) {
          /* your callback code */ 
          omg_device_remove_all();
        }
        });
    }
  });
});


$("#device_missing_detection").off('click').on('click', function () {
  if (this.checked) {
    $("#device_missing_detection_div").show();
  }
  else {
    $("#device_missing_detection_div").hide();
  }
});





/*
 * Display modal d'inclusion
 */
function omg_modal_inclusion_display() {
  $('#md_modal').load('index.php?v=d&plugin=arubacentral&modal=modal.inclusion').dialog('open');
}

function omg_device_remove_all() {

  $.ajax({
    type: "POST",
    url: "plugins/arubacentral/core/ajax/arubacentral.ajax.php",
    data: {
      action: "omgDeviceRemoveAll"
    },
    dataType: 'json',
    error: function (request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function (data) {
      if (data.state != 'ok') {
        $('#div_alert').showAlert({message: '4'+data.result, level: 'danger'});
        return;
      }
      /*
      v_val = data.result;
      v_data = JSON.parse(v_val);
      */
      //$('#cp_debug_value').html('Result : '+v_val+'');
      
      //cp_prog_list_load();
      refreshDeviceList();

    }
  });

}

/*
 * Fonction qui permet d'intercepter l'ouverture des propriétés d'un équipement
 * On va alors lancer l'appel d'une fonction d'initialisation qui va attendre
 * l'affichage de l'objet et en fonction du type de l'objet va pouvoir initialiser 
 * le display.
 * Upadte :
 *   using change() fonction is better. Each time the 'type' is changed the display is updated.
 *   Notice that 'type' is hidden so only the jeedom core is chnaging it when loading an object
 */

$("#cp_type").change( function() {
  //console.log('change type :'+$('#cp_type').value());
  if ($('#cp_type').value() != '') {
    //cp_equipement_display_init();
    // Need some delay for all the attributes of the objects to be loaded
    setTimeout(cp_equipement_display_init, 100); // 100msec
  }
});

var g_cp_count_selector = 0;
$(".li_eqLogic,.eqLogicDisplayCard").off('click').on('click', function () {
  //placeholder pour faire une action lors de l'ouverture, mais pb de délai pour mise à jour des valeurs de l'équipement
  
  //console.log('click display card');
  

  
  /*
  console.log('val : '+$('.eqLogicAttr[data-l1key=configuration][data-l2key=type]').value());
  
  // ----- Reset de la propriété de référence qui permet de s'assurer que le 
  // load de l'équipement est terminé par le core jeedom
  $('.eqLogicAttr[data-l1key=configuration][data-l2key=type]').val('');
  
  // ----- Lancement de l'application d'init décalée
  setTimeout(cp_equipement_display_init, 100); // 100msec
  */
});

function cp_equipement_display_init() {
  var v_type = $('.eqLogicAttr[data-l1key=configuration][data-l2key=type]').value();
  
  // ----- Si la valeur de référence n'est toujours pas chargée, attendre encore un peu ...
  if (v_type == '') {
    //console.log('type is empty increment count');

    g_cp_count_selector++;
    if (g_cp_count_selector > 100) {
      // TBC : error impossible d'afficher ...
      return;
    }
    setTimeout(cp_equipement_display_init, 100);
    // TBC : faut-il mettre un stop à une boucle potentiellement infinie ....
    return;
  }
  
  //console.log('type is '+v_type);
    
  // ----- Initialisation du display
  if (v_type == 'gateway') {
    cp_gateway_display_init();
  }
  else if (v_type == 'device') {
    cp_device_display_init();
  }
  else if (v_type == 'client') {
    cp_client_display_init();
  }
  else {
    // TBC : valeur non supportée
    //console.log('Unexpected type is "'+v_type+'"');
  }

  // ----- Cacher l'icone d'attente
  $('.cp_panel_waiting').hide();

  //console.log('id:'+$('#cp_id').val()+'(count:'+g_cp_count_selector+')');
}
 

/*
 * Fonction d'initialisation du display d'un gateway
 */
function cp_gateway_display_init() {
  
  // ----- Affichage du panel pour les devices
  $('.cp_panel_device').hide();
  $('.cp_panel_client').hide();
  $('.cp_panel_gateway').show();
  

}

/*
 * Fonction d'initialisation du display d'un device
 */
function cp_device_display_init() {

  // ----- Some cleaning
  $("#device_missing_detection_div").hide();
  $('#device_missing_detection').each(function() {  
    if (this.checked) {
      $("#device_missing_detection_div").show();
    }
  });
  
  // ----- Affichage du panel pour les devices
  $('.cp_panel_device').show();
  $('.cp_panel_gateway').hide();  
  $('.cp_panel_client').hide();  
  
  
      
}


/*
 * Fonction d'initialisation du display d'un client
 */
function cp_client_display_init() {

  // ----- Some cleaning
  $("#device_missing_detection_div").hide();
  $('#device_missing_detection').each(function() {  
    if (this.checked) {
      $("#device_missing_detection_div").show();
    }
  });
  
  // ----- Affichage du panel pour les devices
  $('.cp_panel_device').hide();
  $('.cp_panel_gateway').hide();  
  $('.cp_panel_client').show();  
  
  
      
}


function omg_mqtt_info_change() {

  var v_value = $('#omg_mqtt_info').value();
  //console.log('mqtt_info :'+v_value);
  
  if (v_value != '') {
    v_data = JSON.parse(v_value);
    
    v_html = '<br>';
    for (var i in v_data) {
      //v_html += i+' : '+v_data[i]+'<br>';
      
      v_html += '<div class="row form-group">';
      v_html += '  <label class="col-sm-2 control-label">'+i+' :</label>';
      v_html += '  <span class="col-sm-7">'+v_data[i]+'</span>';
      v_html += '</div>';

      
    }
    
    $('#omg_mqtt_info_table').html(v_html);
    $('#omg_mqtt_info_table').show();
  }
  else {
    $('#omg_mqtt_info_table').html('');
    $('#omg_mqtt_info_table').hide();
  }


}

function omg_last_rcv_mqtt_change() {
  var v_value = $('#last_rcv_mqtt').value();
  
  if (v_value == 0) {
    var time = '';
  }
  else {
    var a = new Date(v_value * 1000);
    var months = ['Janvier','Fevrier','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Decembre'];
    var year = a.getFullYear();
    var month = months[a.getMonth()];
    var date = a.getDate();
    var hour = a.getHours();
    var min = a.getMinutes();
    var sec = a.getSeconds();
    var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
  }
  
  $('#last_rcv_mqtt_display').html(time);
}


/*
$("#omg_mqtt_info").change( function() {

  var v_value = $('#omg_mqtt_info').value();
  console.log('mqtt_info :'+v_value);
  
  if (v_value != '') {
    //cp_equipement_display_init();
    // Need some delay for all the attributes of the objects to be loaded
    console.log('pas vide');
    //$('#omg_mqtt_info_table').show();
  }
  else {
    $('#omg_mqtt_info_table').html('');
    //$('#omg_mqtt_info_table').hide();
    console.log(' vide');
  }

});
*/


/*
 * saveEqLogic callback called by plugin.template before saving an eqLogic
 * 
 */
function saveEqLogic(_eqLogic) {
  // ----- Temporary table to store the new list of configuration attributes/values
  var v_new_conf = {};

  var v_att_to_save = { "device" : 
                          {"device_type":1,
                           "device_mqtt_topic":1,
                           "cmd_auto_discover":1,
                           "device_missing_detection":1,
                           "device_missing_timeout":1,
                           "notes":1,
                           "____________":1
                          }
                        ,"client" : 
                          {"client_type":1,
                           "client_mqtt_topic":1,
                           "client_cmd_auto_discover":1,
                           "client_missing_detection":1,
                           "client_missing_timeout":1,
                           "notes":1,
                           "____________":1
                          }
                        ,"gateway" : 
                          {"gateway_mqtt_topic":1,
                           "prop_auto_discover":1,
                           "notes":1,
                           "____________":1
                          }
                        };


  for (v_item in _eqLogic.configuration ) {
    
    // ----- eqLogic type, should be 'device', 'zone' or 'gateway'
    var v_type = _eqLogic.configuration.type;
    if ((v_type != 'device') && (v_type != 'client') && (v_type != 'gateway')) {
      $('#div_alert').showAlert({message: 'saveEqLogic() : Type inconnu : '+v_type, level: 'warning'});
      return _eqLogic;
    }
    
    // ----- Look if this is an attribute to save for this object type
    if ((v_att_to_save[v_type][v_item]) || (v_item == 'type')) {
      //console.log('item to save : '+v_item);
      v_new_conf[v_item] = _eqLogic.configuration[v_item];
    }
    
    else {
      //console.log('item not to save : '+v_item);
    }

  }

  // ----- Change for filtered list
  _eqLogic.configuration = v_new_conf;
  
  return _eqLogic;
}


