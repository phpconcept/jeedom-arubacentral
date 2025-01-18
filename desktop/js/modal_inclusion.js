
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

var g_omg_modal_inclusion_refresh_timeout;
var g_omg_modal_inclusion_scan_on=false;



function omg_modal_inclusion_init() {

  $( "#md_modal" ).dialog({
    title: "{{Recherche & Inclusion d'Objets}}",
    close: function( event, ui ) {omg_modal_inclusion_finish();}
  });

}

function omg_modal_inclusion_scan_start() {

  g_omg_modal_inclusion_scan_on = true;
  $(".omg_modal_inclusion_scanon").show(); 
  $("#omg_modal_inclusion_object_div").show(); 
  $(".omg_modal_inclusion_scanoff").hide(); 
  $(".omg_modal_inclusion_btn").hide(); 
  $('#omg_modal_inclusion_spinner_txt').hide();
  
  $.ajax({
    type: "POST",
    url: "plugins/arubacentral/core/ajax/arubacentral.ajax.php",
    data: {
      action: "omgInclusionStart",
      filters: ''
    },
    dataType: 'json',
    error: function (request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function (data) {
      if (data.state != 'ok') {
        $('#div_alert').showAlert({message: 'omg_modal_inclusion_scan_start()'+data.result, level: 'danger'});
        return;
      }
      
      g_omg_modal_inclusion_refresh_timeout = setInterval(omg_modal_inclusion_scan_get_list, 3000);
      
      /*
      v_val = data.result;
      v_data = JSON.parse(v_val);
      $('#cp_debug_value').html('Result : '+v_val+'');
      */
      
      omg_modal_inclusion_display_list({});
    }
  });

}

function omg_modal_inclusion_scan_stop() {

  g_omg_modal_inclusion_scan_on = false;
  $(".omg_modal_inclusion_scanon").hide(); 
  $(".omg_modal_inclusion_scanoff").show(); 
  $(".omg_modal_inclusion_btn").show(); 
  

  $.ajax({
    type: "POST",
    url: "plugins/arubacentral/core/ajax/arubacentral.ajax.php",
    data: {
      action: "omgInclusionStop",
      filters: ''
    },
    dataType: 'json',
    error: function (request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function (data) {
      if (data.state != 'ok') {
        $('#div_alert').showAlert({message: 'omg_modal_inclusion_scan_stop()'+data.result, level: 'danger'});
        return;
      }
      
      clearInterval(g_omg_modal_inclusion_refresh_timeout);
      
      v_val = data.result;
      v_data = JSON.parse(v_val);
      $('#cp_debug_value').html('Result : '+v_val+'');
      
      omg_modal_inclusion_display_list(v_data);
      
      
    }
  });

}

function omg_modal_inclusion_finish() {

  g_omg_modal_inclusion_scan_on = false;
  $(".omg_modal_inclusion_scanon").hide(); 
  $("#omg_modal_inclusion_object_div").hide(); 
  $(".omg_modal_inclusion_scanoff").show(); 

  $.ajax({
    type: "POST",
    url: "plugins/arubacentral/core/ajax/arubacentral.ajax.php",
    data: {
      action: "omgInclusionFinish",
      filters: ''
    },
    dataType: 'json',
    error: function (request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function (data) {
      if (data.state != 'ok') {
        $('#div_alert').showAlert({message: 'omg_modal_inclusion_finish()'+data.result, level: 'danger'});
        return;
      }
      
      clearInterval(g_omg_modal_inclusion_refresh_timeout);
      
      v_val = data.result;
      v_data = JSON.parse(v_val);
      $('#cp_debug_value').html('Result : '+v_val+'');
            
      
    }
  });
  
  refreshDeviceList();

}



function omg_modal_inclusion_scan_get_list() {

  $.ajax({
    type: "POST",
    url: "plugins/arubacentral/core/ajax/arubacentral.ajax.php",
    data: {
      action: "omgInclusionGetList",
      filters: ''
    },
    dataType: 'json',
    error: function (request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function (data) {
      if (data.state != 'ok') {
        $('#div_alert').showAlert({message: 'omg_modal_inclusion_scan_get_list()'+data.result, level: 'danger'});
        return;
      }      
      
      v_val = data.result;
      v_data = JSON.parse(v_val);
      $('#cp_debug_value').html('Result : '+v_val+'');
      
      omg_modal_inclusion_display_list(v_data);
      
    }
  });

}

function omg_modal_inclusion_scan_add(p_id) {

  $('.omg_modal_inclusion_btn[data-id='+p_id+']').hide();
  $('.omg_modal_inclusion_spinner[data-id='+p_id+']').show();

  $.ajax({
    type: "POST",
    url: "plugins/arubacentral/core/ajax/arubacentral.ajax.php",
    data: {
      action: "omgInclusionAddDevice",
      id: p_id,
      filters: ''
    },
    dataType: 'json',
    error: function (request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function (data) {
      if (data.state != 'ok') {
        $('#div_alert').showAlert({message: 'omg_modal_inclusion_scan_add()'+data.result, level: 'danger'});
        return;
      }      
      
      v_val = data.result;
      v_data = JSON.parse(v_val);
      $('#cp_debug_value').html('Result : '+v_val+'');
      
      omg_modal_inclusion_display_list(v_data);
      
    }
  });

}


function omg_modal_inclusion_display_list(p_list) {

  var v_count = 0;
  var v_html = '';
  //var v_html += '<tr><td>Id</td><td>Name</td></tr>';
  for (var i in p_list) {
    v_html += '<tr class="omg_modal_inclusion_tr" data-id="'+i+'">';
    
    var v_name = '';
    if (p_list[i]['brand_name']) {
      if (v_name != '') v_name += ', ';
      v_name += p_list[i]['brand_name'];
    }
    if (p_list[i]['properties']['name']) {
      if (v_name != '') v_name += ', ';
      v_name += p_list[i]['properties']['name'];
    }
    if (p_list[i]['properties']['brand']) {
      if (v_name != '') v_name += ', ';
      v_name += p_list[i]['properties']['brand'];
    }
    if (p_list[i]['properties']['model']) {
      if (v_name != '') v_name += ', ';
      v_name += p_list[i]['properties']['model'];
    }
    if (p_list[i]['properties']['model_id']) {
      if (v_name != '') v_name += ', ';
      v_name += p_list[i]['properties']['model_id'];
    }
    if (p_list[i]['properties']['type']) {
      if (v_name != '') v_name += ', ';
      v_name += p_list[i]['properties']['type'];
    }
    if (v_name == '') {
      v_name = '-';
    }
    
    var v_style = '';
    if (g_omg_modal_inclusion_scan_on) v_style = 'style="display:none;"';
    
    v_html += '<td>';
    v_html += '<label class="control-label omg_modal_inclusion_spinner" data-id="'+i+'"  style="display:none;"><i class="fa fa-spinner fa-spin" ></i> {{Ajout en cours ...}}</label>';
    v_html += '<a class="btn btn-success btn-xs omg_modal_inclusion_btn" data-id="'+i+'" onClick="omg_modal_inclusion_scan_add(\''+i+'\');" '+v_style+'><i class="fa fa-plus-circle icon-white"></i>&nbsp;&nbsp;{{Ajouter}}</a>';
    v_html += '</td>';
    
    v_html += '<td>'+p_list[i]['id']+'</td><td>'+v_name+'</td>';
    v_html += '</tr>';
    
    v_count++;
  }
  
  if (v_count < 3) v_html += '<tr><td>&nbsp;</td><td></td><td></td></tr>';
  if (v_count < 2) v_html += '<tr><td>&nbsp;</td><td></td><td></td></tr>';
  if (v_count < 1) v_html += '<tr><td>&nbsp;</td><td></td><td></td></tr>';
  
  $('#omg_modal_inclusion_tbody').html(v_html);
  
  if (v_count != 0) $('#omg_modal_inclusion_spinner_txt').show();
  
}


$('.omg_modal_inclusion_refresh').off('click').on('click', function () {
  omg_modal_inclusion_scan_get_list();
});

