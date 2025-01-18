<?php
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

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

require_once dirname(__FILE__) . "/../../../../plugins/arubacentral/core/php/arubacentral.inc.php";


?>

<div class="col-sm-12">
    <div class="panel panel-primary">
        <div class="panel-heading" style="background-color: #039be5;">
            <h3 class="panel-title">{{Scaning & Filtrage}}

       <span class="dropdown pull-right" style="top: -2px !important; right: -6px !important;">
       <i class="fa fa-ellipsis-v dropdown-toggle" data-toggle="dropdown"></i>      
       <ul class="dropdown-menu">
         <li><label style="padding: 0px 5px;"><a onClick="$('#md_modal').dialog('close');">{{Quitter}}</a></label></li>
       </ul>
       </span>

            </h3>
        </div>
        <div class="panel-body">
        
        
  <div class="row">
    <label class="col-sm-3 control-label" style="margin-left: 20px;"><input type="checkbox"  checked/> {{Recherche des objets BLE}}</label>
    <div class="col-sm-7">
    </div>
  </div>
  <br>


  <div class="row text-center">
 <a class="btn btn-success omg_modal_inclusion_scanoff" onClick="omg_modal_inclusion_scan_start();">
 <i class="fa fa-check-circle icon-white"></i>&nbsp;&nbsp;{{Lancer Scan}}</a>
  &nbsp; 
 <a class="btn btn omg_modal_inclusion_scanon" onClick="omg_modal_inclusion_scan_stop();" style="display:none;">
 <i class="fa fa-ban  icon-white"></i>&nbsp;&nbsp;{{Arrêter Scan}}</a>
 
 
  </div>
 
   <br>

        </div>
    </div>
</div>


  
<div class="col-sm-12" id="omg_modal_inclusion_object_div" style="display:none;">
    <div class="panel panel-primary">
    
    
        <div class="panel-heading" style="background-color: #039be5;">
            <h3 class="panel-title">{{Objets détectés}}
            </h3>
        </div>


        <div class="panel-body">


  <div class="col-sm-12" id="cp_prog_table_horaire" style="overflow-x:scroll;">

  <div class="row omg_modal_inclusion_scanon" style="display:none;">
    <div class="col-sm-1">
    </div>
    <div class="col-sm-10 text-center">

      <label class="control-label" ><i class="fa fa-spinner fa-spin"></i> {{Scaning en cours ...}}  
      <span id="omg_modal_inclusion_spinner_txt" style="display:none;">&nbsp;&nbsp;{{arrêter pour pouvoir ajouter.}}</span>
      </label>
                
    </div>
  </div>

  <div class="row">
    <div class="col-sm-1">
    </div>
    <div class="col-sm-10">
      <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">{{Objets}}
      </div>
    </div>
  </div>
  
  <div class="row" >
    <div class="col-sm-1">
    </div>
    <div class="col-sm-10">

    <table class="table table-bordered">
    <tbody id="omg_modal_inclusion_tbody">
     <tr>
     <td>Id</td>
     <td>Name</td>
     </tr>
    </tbody>    
  </table>
            
    </div>
  </div>
  
  </div>

  </div>
  
    </div>
  </div>
</div>


<div id="cp_debug_value" style="display:none;"></div>


<?php include_file('desktop', 'modal_inclusion', 'js', 'arubacentral'); ?>

<script type="text/javascript">

  omg_modal_inclusion_init();

</script>

