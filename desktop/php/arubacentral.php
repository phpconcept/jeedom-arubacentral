<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('arubacentral');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());

  // ----- Passage des infos d'icone et de couleur pour la programmation
  // TBC : à configurer dans les propriétés du plugin
  sendVarToJS('g_prog_mode_display', 'icon');   // 'icon' ou 'color'


?>
<script type="text/javascript">

  var g_cache_selected_mode = '';
  var g_cache_last_click = '';
  var g_cache_agenda = '';
  
  $(document).ready(function() {
    // do this stuff when the HTML is all ready
    refreshDeviceList();
  });

</script>



<div class="row row-overflow">
   <div class="col-xs-12 eqLogicThumbnailDisplay">
  <legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
  <div class="eqLogicThumbnailContainer">
  
      <div class="cursor eqLogicAction logoPrimary" data-action="cp_add_gateway">
        <i class="fas fa-plus-circle"></i>
        <br>
        <span>{{Ajouter Gateway}}</span>
      </div>

      <div class="cursor eqLogicAction logoPrimary" data-action="cp_add_device">
        <i class="fas fa-plus-circle"></i>
        <br>
        <span>{{Ajouter Objet}}</span>
      </div>

      <div class="cursor logoSecondary" onclick="omg_modal_inclusion_display();">
        <i class="fas fa-sign-in-alt fa-rotate-90"></i>
        <br>
        <span>{{Scan & Inclusion}}</span>
      </div>

      <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
        <i class="fas fa-wrench"></i>
        <br>
        <span>{{Configuration}}</span>
      </div>
  </div>
<?php
// Here I moved this part of the display to a modal file : modal.device_list.php
// By doing that I can refresh the list automatically, for
// exemple when in inclusion mode

?>
        <div id="device_list"></div>
</div>



<?php
// This part if for displaying individual object (eqlogic)
?>


<div class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
    <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
    <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
  </ul>
  <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">

    <div role="tabpanel" class="tab-pane active cp_panel_waiting">
      <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
          <label class="control-label" ><i class="fa fa-spinner fa-spin"></i> {{Chargement en cours ...}}</label>
        </div>
        <div class="col-sm-4"></div>
      </div>
    </div>

    <div role="tabpanel" class="tab-pane active" id="eqlogictab">
      <form class="form-horizontal">
        <fieldset>
          <input id="cp_id" type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
          <input id="cp_type" type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="type" style="display : none;" />


<!-- Object display container -->
<div class="container-fluid">

  <!-- First Row -->
  <div class="row">
  
    <!-- Left column -->
    <div class="col-sm-6">

      <div class="form-group">
          <div class="col-sm-12">
                 <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">{{Propriétés Jeedom}}</div>
          </div>
      </div>

       <div class="form-group">
            <label class="col-sm-4 control-label">{{Nom}} <span class="cp_panel_device">{{de l'objet}}</span><span class="cp_panel_gateway">{{de la gateway}}</span></label>
            <div class="col-sm-8">
                <input type="text" class="cp_attr_device eqLogicAttr form-control" data-l1key="name" placeholder=""/>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-4 control-label" >{{Objet parent}}</label>
            <div class="col-sm-8">
                <select id="sel_object" class="cp_attr_device eqLogicAttr form-control" data-l1key="object_id">
                    <option value="">{{Aucun}}</option>
                    <?php
                      foreach (jeeObject::all() as $object) {
                      	echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                      }
                    ?>
               </select>
           </div>
       </div>
           
	   <div class="form-group">
                <label class="col-sm-4 control-label">{{Catégorie}}</label>
                <div class="col-sm-8">
                 <?php
                    foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                    echo '<label class="checkbox-inline">';
                    echo '<input type="checkbox" class="cp_attr_device eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                    echo '</label>';
                    }
                  ?>
               </div>
           </div>
           
      	<div class="form-group">
      		<label class="col-sm-4 control-label">{{Activation}} & {{Visibilité}}</label>
      		<div class="col-sm-8">
      			<label class="checkbox-inline"><input type="checkbox" class="cp_attr_device eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
      			<label class="checkbox-inline"><input type="checkbox" class="cp_attr_device eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
      		</div>
      	</div>
            
        <div class="row form-group">
          <label class="col-sm-4 control-label">{{Notes}}</label>
          <div class="col-sm-8">
            <textarea class="cp_attr_device eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="notes" style="height : 33px;" ></textarea>
          </div>
        </div>

    </div>
    <!-- End Left column -->
    
    <!-- Right column -->
    <div class="col-sm-6">
    
  <!-- Additional rows depending on objects type -->
          <div class="cp_panel_device" style="display:none;">
    	  <?php include_file('desktop', 'arubacentral_device_nature.inc', 'php', 'arubacentral'); ?>
          </div>
    

    </div>
    <!-- Right column -->

  </div>
  <!-- End First row -->


  <!-- Additional rows depending on objects type -->
          <div class="cp_panel_device" style="display:none;">
    	  <?php include_file('desktop', 'arubacentral_device.inc', 'php', 'arubacentral'); ?>
          </div>
          <div id="cp_panel_gateway" class="cp_panel_gateway" style="display:none;">
    	  <?php include_file('desktop', 'arubacentral_gateway.inc', 'php', 'arubacentral'); ?>
          </div>
          
</div>
<!-- End of Object display container -->
          
        </fieldset>
      </form>        
    </div>

    <div role="tabpanel" class="tab-pane" id="commandtab">
      <a class="btn btn-success btn-sm cmdAction pull-right" data-action="add" style="margin-top:5px;"><i class="fa fa-plus-circle"></i> {{Commandes}}</a>
      <br/><br/>
      
      <table id="table_cmd" class="table table-bordered table-condensed">
          <thead>
              <tr>
                  <th>{{Nom}}</th><th>{{Type}}</th><th>{{Action}}</th>
              </tr>
          </thead>
          <tbody>
          </tbody>
      </table>
    </div>
</div>

</div>




</div>


<?php include_file('desktop', 'arubacentral', 'js', 'arubacentral');?>
<?php include_file('core', 'plugin.template', 'js');?>
