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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . '/../../../../plugins/arubacentral/core/php/arubacentral.inc.php';



class arubacentral extends eqLogic {

    /*     * *************************Attributs****************************** */
    /*
    * Attributs de configuration :
    */
    var $_pre_save_cache;
    
    /*     * ***********************Methode static*************************** */

    // -------------------------------------------------------------------------
    // Function : agv($p_item, $p_key)
    // Description :
    //  agv : array get value
    // -------------------------------------------------------------------------
    public static function agv($p_item, $p_key)
    {
      if (is_array($p_item) && isset($p_item[$p_key])) {
        return($p_item[$p_key]);
      }
      return('');
    }
    // -------------------------------------------------------------------------
  
    /*
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
     */
	public static function cron() {
      $v_list = arubacentral::acGatewayList(['_isEnable'=>true]);
      foreach ($v_list as $v_item) {
        $v_item->omgGatewayCheckOnline();
      }
    
      $v_list = arubacentral::acDeviceList(['_isEnable'=>true]);
      foreach ($v_list as $v_item) {
        $v_item->omgDeviceCheckOnline();
      }
    
    }
     
/*     
    public static function cron5() {
        
      // ----- Recalculate mode for each device
      $v_list = arubacentral::acDeviceList(['_isEnable'=>true]);
      foreach ($v_list as $v_device) {
        // TBC
      }

	}
*/

    /*
     * Fonction exécutée automatiquement toutes les 5,10,15 minutes par Jeedom
      public static function cron10() {}
      public static function cron15() {
      }
     */

    /*
     * Fonction exécutée automatiquement toutes les heures par Jeedom
      public static function cronHourly() {

      }
     */

    /*
     * Fonction exécutée automatiquement tous les jours par Jeedom
      public static function cronDaily() {

      }
     */

    /*
    public static function deamon_info()
    {
    }
     */

    /*
    public static function deamon_start($_debug = false)
    {
    }
     */

    /*
    public static function deamon_stop()
    {
    }
     */

    /*
	public static function dependancy_info() {
	}
     */

    /*
	public static function dependancy_install() {
	}
     */


    /*     * ***********************Methodes specifiques arubacentral*************************** */


    /**---------------------------------------------------------------------------
     * Method : acEqList()
     * Description :
     *   arubacentral::acEqList('device', ['zone'=>'', 'ddd'=>'vvv'])
     *   arubacentral::acEqList('device', ['_isEnable'=>true]) : pour checker le getIsEnable de jeedom pour l'objet
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function acEqList($p_type, $p_filter_list=array()) {
      $v_result = array();
      $eqLogics = eqLogic::byType('arubacentral');
      foreach ($eqLogics as $v_eq) {
        if ($v_eq->acGetConf('type') != $p_type) {
          continue;
        }
        
        // ----- Look for filtering
        if (is_array($p_filter_list)) {
          $v_filter_ok = true;
          foreach ($p_filter_list as $v_key => $v_value) {
            if ($v_key == '_isEnable') {
              if ($v_eq->getIsEnable() != $v_value) {
                $v_filter_ok = false;
                break;
              }
            }
            else if ($v_eq->acGetConf($v_key) != $v_value) {
              $v_filter_ok = false;
              break;
            }
          }
          if ($v_filter_ok) {
            $v_result[] = $v_eq;          
          }
        }
        else {
          $v_result[] = $v_eq;
        }
      }
      return($v_result);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : acClientList()
     * Description :
     *   arubacentral::acClientList(['zone'=>'', 'ddd'=>'vvv'])
     *   arubacentral::acClientList(['_isEnable'=>true]) : pour checker le getIsEnable de jeedom pour l'objet
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function acClientList($p_filter_list=array()) {
      return(arubacentral::acEqList('client', $p_filter_list));
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : acClientGetByTopic()
     * Description :
     *   arubacentral::acClientGetByTopic('ZZZZZ')
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function acClientGetByTopic($p_topic) {
      return(arubacentral::acEqGetByTopic($p_topic, 'client'));
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : acDeviceList()
     * Description :
     *   arubacentral::acDeviceList(['zone'=>'', 'ddd'=>'vvv'])
     *   arubacentral::acDeviceList(['_isEnable'=>true]) : pour checker le getIsEnable de jeedom pour l'objet
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function acDeviceList($p_filter_list=array()) {
      return(arubacentral::acEqList('device', $p_filter_list));
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : acEqGetByTopic()
     * Description :
     *   arubacentral::acEqGetByTopic('ZZZZZ', 'device')
     *   arubacentral::acEqGetByTopic('RRRRR', 'gateway')
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function acEqGetByTopic($p_topic, $p_type) {
      $eqLogics = eqLogic::byType('arubacentral');
      foreach ($eqLogics as $v_eq) {
        if ($v_eq->acGetConf('type') != $p_type) {
          continue;
        }
        
        if ($v_eq->acGetConf($p_type.'_mqtt_topic') == $p_topic) {
          return($v_eq);
        }
      }
      return(null);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : acDeviceGetByTopic()
     * Description :
     *   arubacentral::acDeviceGetByTopic('ZZZZZ')
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function acDeviceGetByTopic($p_topic) {
      return(arubacentral::acEqGetByTopic($p_topic, 'device'));
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : acDeviceTypeList()
     * Description :
     *   arubacentral::acDeviceTypeList()
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function acDeviceTypeList($p_id_only=true) {

      $v_type_list = ['ap'   => ['name'=> __('AP', __FILE__)],
                      'switch' => ['name'=> __('Switch', __FILE__)],
                      'gateway' => ['name'=> __('Gateway', __FILE__)]
                     ];
      
      if ($p_id_only) {
        $v_short_list = array();
        foreach ($v_result_list as $v_key => $v_data) {
          $v_short_list[] = $v_key;
        }
        return($v_short_list);
      }
      
      return($v_type_list);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : acClientTypeList()
     * Description :
     *   arubacentral::acClientTypeList()
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function acClientTypeList($p_id_only=true) {

      $v_type_list = ['unknown'   => ['name'=> __('Unknown', __FILE__)],
                      'ethernet'   => ['name'=> __('Ethernet', __FILE__)],
                      'wifi' => ['name'=> __('WiFi', __FILE__)]
                     ];
      
      if ($p_id_only) {
        $v_short_list = array();
        foreach ($v_result_list as $v_key => $v_data) {
          $v_short_list[] = $v_key;
        }
        return($v_short_list);
      }
      
      return($v_type_list);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : acGatewayList()
     * Description :
     *   arubacentral::acGatewayList(['zone'=>'', 'ddd'=>'vvv'])
     *   arubacentral::acGatewayList(['_isEnable'=>true]) : pour checker le getIsEnable de jeedom pour l'objet
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function acGatewayList($p_filter_list=array()) {
      return(arubacentral::acEqList('gateway', $p_filter_list));
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : acGatewayGetByTopic()
     * Description :
     *   arubacentral::acGatewayGetByTopic('ZZZZZ')
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function acGatewayGetByTopic($p_topic) {
      return(arubacentral::acEqGetByTopic($p_topic, 'gateway'));
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : acGatewayAutoDiscover()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function acGatewayAutoDiscover() {
      $v_auto_discover = config::byKey('gateway_auto_discover', 'arubacentral', '');
      return ($v_auto_discover==1?true:false);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : acGatewayCreate()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function acGatewayCreate($p_mqtt_topic, $p_properties) {
      arubacentral::log('debug', "acGatewayCreate('".$p_mqtt_topic."')");
      arubacentral::log('debug', "  Properties : ".json_encode($p_properties));
      
      $v_jeedom_device = new arubacentral();
      
      $v_jeedom_device->setName($p_mqtt_topic);
      $v_jeedom_device->setEqType_name('arubacentral');

      $v_jeedom_device->setConfiguration('type', 'gateway');
      $v_jeedom_device->setConfiguration('gateway_mqtt_topic', $p_mqtt_topic);
      $v_jeedom_device->setConfiguration('prop_auto_discover', 1);

      $v_jeedom_device->setIsEnable(1);
      $v_jeedom_device->save();
      
      // ----- Create default online status command
      $v_cmd = $v_jeedom_device->omgCmdCreate('online_status', ['name'=> __('Présent', __FILE__),
                                  'type'=>'info',
                                  'subtype'=>'binary', 
                                  'isHistorized'=>1, 
                                  'isVisible'=>1]);
      $v_jeedom_device->checkAndUpdateCmd('online_status', 0);

      return($v_jeedom_device);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgInclusionIsOn()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgInclusionIsOn() {
      if (config::byKey('scan_on', 'arubacentral', '') == 1) return(true);
      
      return(false);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgInclusionStart()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgInclusionStart() {
      arubacentral::log('debug', 'omgInclusionStart()');
      
      config::save('scan_on', 1, 'arubacentral');
      
      // ----- Reset and empty list
      config::save('scan_list', array(), 'arubacentral');
      
      return(null);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgInclusionStop()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgInclusionStop() {
      arubacentral::log('debug', 'omgInclusionStop()');

      config::save('scan_on', 0, 'arubacentral');

      // ----- Get current scan list
      $v_scan_list = arubacentral::omgInclusionGetList();

      return($v_scan_list);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgInclusionFinish()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgInclusionFinish() {
      arubacentral::log('debug', 'omgInclusionFinish()');

      config::save('scan_on', 0, 'arubacentral');

      // ----- Reset and empty list
      config::save('scan_list', array(), 'arubacentral');

      return(null);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgInclusionGetList()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgInclusionGetList() {
      arubacentral::log('debug', 'omgInclusionGetList()');

      // ----- Get current scan list
      $v_scan_list = config::byKey('scan_list', 'arubacentral', '');
      if (!is_array($v_scan_list)) {
        $v_scan_list = array();
      }
            
      arubacentral::log('debug', 'omgInclusionGetList() : '.json_encode($v_scan_list, true));

      return($v_scan_list);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgInclusionAddDevice()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgInclusionAddDevice($p_id) {
      arubacentral::log('debug', 'omgInclusionAddDevice('.$p_id.')');

      // ----- Get current scan list
      $v_scan_list = arubacentral::omgInclusionGetList();
      
      $v_obj_att = null;
      
      foreach ($v_scan_list as $v_id => $v_att) {
        if ($v_id == $p_id) {
          $v_obj_att = $v_att;
          break;
        }
      }
      
      if ($v_obj_att !== null) {
        // ----- Récupération de la gateway
        $v_gateway = arubacentral::acGatewayGetByTopic($v_obj_att['gateway_topic']);
        
        // ----- Add device
        arubacentral::omgDeviceCreate($p_id, $v_obj_att['properties'], $v_gateway);
        
        // ----- Update scan list be removing added device
        unset($v_scan_list[$p_id]);
        config::save('scan_list', $v_scan_list, 'arubacentral');
        arubacentral::log('debug', 'omgInclusionAddDevice() save ok');
      }
      else {
        arubacentral::log('debug', 'omgInclusionAddDevice('.$p_id.') : no device in list');
      }

      arubacentral::log('debug', 'omgInclusionAddDevice() done : '.json_encode($v_scan_list, true));
      return($v_scan_list);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgInclusionLearn()
     * Description :
     * Parameters :
     *   
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgInclusionLearn($p_mqtt_id, $p_properties, $p_gateway) {
    
      if (!arubacentral::omgInclusionIsOn()) {
        arubacentral::log('debug', "Scaning is off");
        return;
      }
      
      arubacentral::log('debug', "Scaning / learn object ".$p_mqtt_id);
      
      // ----- Get current scan list
      $v_scan_list = arubacentral::omgInclusionGetList();
      
      arubacentral::log('debug', 'current list : '.json_encode($v_scan_list));
      
      // ----- Update scan list
      //if (!isset($v_scan_list[$p_mqtt_id])) {
      if (!array_key_exists($p_mqtt_id, $v_scan_list)) {
        arubacentral::log('debug', "Scaning / adding object ".$p_mqtt_id);
        
        $v_item = array();
        $v_item['id'] = $p_mqtt_id;
        $v_item['properties'] = $p_properties;
        $v_item['gateway_topic'] = $p_gateway->acGetConf('gateway_mqtt_topic');
        
        $v_scan_list[$p_mqtt_id] = $v_item;
        
        arubacentral::log('debug', 'modif list : '.json_encode($v_scan_list, true));

        config::save('scan_list', $v_scan_list, 'arubacentral');
      }
 
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : log()
     * Description :
     *   A placeholder to encapsulate log message, and be able do some
     *   troubleshooting locally.
     * ---------------------------------------------------------------------------
     */
    public static function log($p_level, $p_message) {
      
      log::add('arubacentral', $p_level, $p_message);

    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgDeviceCreate()
     * Description :
     * Parameters :
     *   
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgDeviceCreate($p_mqtt_id, $p_properties, $p_gateway=null) {
      arubacentral::log('debug', "Create a Jeedom Object for '".$p_mqtt_id."'");
      arubacentral::log('debug', "  Properties : ".json_encode($p_properties));
      
      $v_jeedom_device = new arubacentral();
      
      $v_name = $p_mqtt_id;
      if (isset($p_properties['name'])) {
        $v_name = $p_properties['name'];
      }
      
      $v_jeedom_device->setName($v_name);
      $v_jeedom_device->setEqType_name('arubacentral');

      $v_jeedom_device->setConfiguration('type', 'device');
      $v_jeedom_device->setConfiguration('device_mqtt_topic', $p_mqtt_id);
      
      $v_jeedom_device->setIsEnable(1);
      
      $v_jeedom_device->save();
      
      $v_jeedom_device->acDeviceUpdateAttributes($p_properties, $p_gateway);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgDeviceRemoveAll()
     * Description :
     * Parameters :
     *   
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgDeviceRemoveAll() {
      arubacentral::log('debug', 'Removing all the objects');
      $v_list = arubacentral::acDeviceList();
      foreach ($v_list as $v_objet) {
        $v_objet->remove();
      }
    }
    /* -------------------------------------------------------------------------*/


    /*     * *********************Méthodes d'instance************************* */
    
    /*
      preInsert ⇒ Méthode appellée avant la création de votre objet
      postInsert ⇒ Méthode appellée après la création de votre objet
      preUpdate ⇒ Méthode appellée avant la mise à jour de votre objet
      postUpdate ⇒ Méthode appellée après la mise à jour de votre objet
      preSave ⇒ Méthode appellée avant la sauvegarde (creation et mise à jour donc) de votre objet
      postSave ⇒ Méthode appellée après la sauvegarde de votre objet
      preRemove ⇒ Méthode appellée avant la supression de votre objet
      postRemove ⇒ Méthode appellée après la supression de votre objet    
      
      Lorsque l'on créé un equipement, il demande son nom, puis il fait les fonctions suivantes :
        preSave()
        preInsert()
        postInsert()
        postSave()
      il propose ensuite l'écran des paramètres de configuration :
    */

    public function preInsert() {
      arubacentral::log('debug', "preInsert()");
    }

    public function postInsert() {
      arubacentral::log('debug', "postInsert()");

      // ----- Vérifier qu'il y a bien un type d'identifié, sinon forcer device
      $v_type = $this->getConfiguration('type', '');
      if (($v_type != 'gateway') && ($v_type != 'device') && ($v_type != 'client')) {
        arubacentral::log('error', "Equipement avec un type non reconnu : '".$v_type."', type 'device' forcé.");
        $this->setConfiguration('type', 'device');
      }
      
      if ($v_type == 'device') {
      
        $v_cmd_order=1;
        // ----- Création des commandes par défaut
        //$this->omgCmdCreate('last_seen', ['name'=>'Dernière Communication', 'type'=>'info', 'subtype'=>'string', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>$v_cmd_order++]);
      }

      else if ($v_type == 'gateway') {

        $v_cmd_order=1;
        // ----- Création des commandes par défaut
        //$this->omgCmdCreate('last_seen', ['name'=>'Dernière Communication', 'type'=>'info', 'subtype'=>'string', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>$v_cmd_order++]);
      }

      else if ($v_type == 'client') {

        $v_cmd_order=1;
        // ----- Création des commandes par défaut
        //$this->omgCmdCreate('last_seen', ['name'=>'Dernière Communication', 'type'=>'info', 'subtype'=>'string', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>$v_cmd_order++]);
      }

      else {
        // TBC : error
      }

    }

    public function preSave() {
      //arubacentral::log('debug', "preSave()");
      $v_type = $this->getConfiguration('type', '');
      if ($v_type == 'device') {
        $this->preSaveDevice();
      }
      else if ($v_type == 'client') {
        $this->preSaveClient();
      }
      else if ($v_type == 'gateway') {
        $this->preSaveGateway();
      }
    }

    public function preSaveDevice() {
      arubacentral::log('debug', "preSaveDevice()");
      
      // It's time to gather informations that will be used in postSave
      
      // ----- Look for new device
      // The trick is that before the first save the eq is not in the DB so it has not yet a deviceId
      // In my plugin I need to remember I first save the device in javscript with the sub-type 'device', 'gateway' or 'zone'
      if ($this->getId() == '') {
        arubacentral::log('debug', "preSaveDevice() : new device, init properties");
        
        // ----- Set default values
        $this->setConfiguration('cmd_auto_discover', 1);
        
        $this->setConfiguration('device_missing_detection', 0);
        $this->setConfiguration('device_missing_timeout', 10);
        

        $this->setStatus('mqtt_info', '');
        $this->setStatus('device_last_rcv_mqtt', 0);
        
        // ----- No data to store for postSave() tasks
        $this->_pre_save_cache = null; // New eqpt => Nothing to collect        
      }
      
      // ----- Look for existing device
      else {
        arubacentral::log('debug', "preSaveDevice() : existing device.");
        
        if ($this->acGetConf('device_missing_detection') == 0) {
          $this->omgDeviceChangeToOnline();
        }
        
        $v_missing_timeout = $this->acGetConf('device_missing_timeout');
        if (!is_numeric($v_missing_timeout) || ($v_missing_timeout<1) || ($v_missing_timeout>1440)) {
          $this->setConfiguration('device_missing_timeout', 10);
        }

        // ----- Load device (eqLogic) from DB
        // These values will be erased with the save in DB, so keep what is needed to be kept
        // $this : contient donc l'objet PHP avec les nouvelles valeurs, avant leur sauvegarde dans la DB
        // $eqLogic : contient les valeurs dans la DB qui vont être remplacées par la sauvegarde de $this dans la DB
      	$eqLogic = self::byId($this->getId());
        
        $this->_pre_save_cache = array(
          'name'                  => $eqLogic->getName(),
          'isEnable'              => $eqLogic->getIsEnable()
        );
                
      }

      arubacentral::log('debug', "preSaveDevice() done");
    }

    public function preSaveClient() {
      arubacentral::log('debug', "preSaveClient()");
      
      // It's time to gather informations that will be used in postSave
      
      // ----- Look for new device
      // The trick is that before the first save the eq is not in the DB so it has not yet a deviceId
      // In my plugin I need to remember I first save the device in javscript with the sub-type 'device', 'gateway' or 'zone'
      if ($this->getId() == '') {
        arubacentral::log('debug', "preSaveClient() : new device, init properties");
        
        // ----- Set default values
        $this->setConfiguration('client_cmd_auto_discover', 1);        
        $this->setConfiguration('device_missing_detection', 0);
        $this->setConfiguration('device_missing_timeout', 10);
        

        $this->setStatus('mqtt_info', '');
        $this->setStatus('client_last_rcv_mqtt', 0);
        
        // ----- No data to store for postSave() tasks
        $this->_pre_save_cache = null; // New eqpt => Nothing to collect        
      }
      
      // ----- Look for existing device
      else {
        arubacentral::log('debug', "preSaveDevice() : existing device.");
        
        if ($this->acGetConf('device_missing_detection') == 0) {
          $this->omgDeviceChangeToOnline();
        }
        
        $v_missing_timeout = $this->acGetConf('device_missing_timeout');
        if (!is_numeric($v_missing_timeout) || ($v_missing_timeout<1) || ($v_missing_timeout>1440)) {
          $this->setConfiguration('device_missing_timeout', 10);
        }

        // ----- Load device (eqLogic) from DB
        // These values will be erased with the save in DB, so keep what is needed to be kept
        // $this : contient donc l'objet PHP avec les nouvelles valeurs, avant leur sauvegarde dans la DB
        // $eqLogic : contient les valeurs dans la DB qui vont être remplacées par la sauvegarde de $this dans la DB
      	$eqLogic = self::byId($this->getId());
        
        $this->_pre_save_cache = array(
          'name'                  => $eqLogic->getName(),
          'isEnable'              => $eqLogic->getIsEnable()
        );
                
        arubacentral::log('debug', "_pre_save_cache=".json_encode($this->_pre_save_cache));
        
      }

      arubacentral::log('debug', "preSaveClient() done");
    }

    public function preSaveGateway() {
      //arubacentral::log('debug', "preSave() : gateway ...");
      
      // It's time to gather informations that will be used in postSave
      
      // ----- Look for new device
      // The trick is that before the first save the eq is not in the DB so it has not yet a deviceId
      // In my plugin I need to remember I first save the device in javscript with the sub-type 'device', 'gateway' or 'zone'
      if ($this->getId() == '') {
        arubacentral::log('debug', "preSaveGateway() : new gateway, init properties");
        
        // ----- Set default values
        $this->setConfiguration('prop_auto_discover', 1);
        $this->setConfiguration('online_timeout', 2);

        // ----- No data to store for postSave() tasks
        $this->_pre_save_cache = null; // New eqpt => Nothing to collect        
      }
      
      // ----- Look for existing device
      else {
        arubacentral::log('debug', "preSaveGateway() : existing gateway.");
        // ----- Load device (eqLogic) from DB
        // These values will be erased with the save in DB, so keep what is needed to be kept
      	$eqLogic = self::byId($this->getId());

        $this->_pre_save_cache = array(
          'name'                  => $eqLogic->getName(),
          'isEnable'              => $eqLogic->getIsEnable()
        );
      }

      arubacentral::log('debug', "preSave() end");
    }

    public function postSave() {
      //arubacentral::log('debug', "postSave()");
      $v_type = $this->getConfiguration('type', '');
      if ($v_type == 'device') {
        $this->postSaveDevice();
      }
      else if ($v_type == 'client') {
        $this->postSaveClient();
      }
      else if ($v_type == 'gateway') {
        $this->postSaveGateway();
      }
    }

    public function postSaveDevice() {

      //arubacentral::log('debug', "postSave() device");

      // ----- Look for new device
      if (is_null($this->_pre_save_cache)) {
        arubacentral::log('debug', "postSaveDevice() : new device saved in DB.");
        
        // ----- Create default online status command
        $this->omgCmdCreateMandatory('present');
        /*
        $v_cmd = $this->omgCmdCreate('present', ['name'=> __('Présent', __FILE__),
                                     'type'=>'info',
                                     'subtype'=>'binary', 
                                     'isHistorized'=>0, 
                                     'isVisible'=>0]);
        $this->checkAndUpdateCmd('present', 0);
        */
      }
      
      // ----- Look for existing device
      else {
        arubacentral::log('debug', "postSaveDevice() : device saved in DB.");

        // ----- Look if device enable is changed
        if ($this->_pre_save_cache['isEnable'] != $this->getIsEnable()) {
        
          // Code à dérouler si changement enable
        }
          
      }
      
      arubacentral::log('debug', "postSaveDevice() done");
    }

    public function postSaveClient() {

      //arubacentral::log('debug', "postSave() device");

      // ----- Look for new device
      if (is_null($this->_pre_save_cache)) {
        arubacentral::log('debug', "postSaveClient() : new device saved in DB.");
        
        // ----- Create default online status command
        $this->omgCmdCreateMandatory('present');
        /*
        $v_cmd = $this->omgCmdCreate('present', ['name'=> __('Présent', __FILE__),
                                     'type'=>'info',
                                     'subtype'=>'binary', 
                                     'isHistorized'=>0, 
                                     'isVisible'=>0]);
        $this->checkAndUpdateCmd('present', 0);
        */

        // ----- Create default online status command
        $this->omgCmdCreateMandatory('rssi');
        /*
        $v_cmd = $this->omgCmdCreate('rssi', ['name'=>'rssi',
                                    'type'=>'info',
                                    'subtype'=>'numeric', 
                                    'isHistorized'=>0, 
                                    'isVisible'=>0]);
        $this->checkAndUpdateCmd('rssi', -199);
        */

      }
      
      // ----- Look for existing device
      else {
        arubacentral::log('debug', "postSaveClient() : device saved in DB.");
          
        // ----- Look if device enable is changed
        if ($this->_pre_save_cache['isEnable'] != $this->getIsEnable()) {
        
          // Code à dérouler si changement enable
        }
          
      }
      
      arubacentral::log('debug', "postSaveClient() done");
    }

    public function postSaveGateway() {

      //arubacentral::log('debug', "postSave() : gateway");

      // ----- Look for new device
      if (is_null($this->_pre_save_cache)) {
        arubacentral::log('debug', "postSaveGateway() : new gateway saved in DB.");

        
      }
      
      // ----- Look for existing device
      else {
        arubacentral::log('debug', "postSaveGateway() : gateway saved in DB.");

        // ----- Look if device enable is changed
        if ($this->_pre_save_cache['isEnable'] != $this->getIsEnable()) {
        
        }
  
      }
      
      arubacentral::log('debug', "postSaveGateway() : end");
    }

/*
    public function start() {
      arubacentral::log('info', "Start plugin OpenMQTTGateway");
            
      // ----- Look for clean start
      if (config::byKey('clean_stop', 'arubacentral') != '') {
        arubacentral::log('debug', "PlugIn was clean stopped at : ".config::byKey('clean_stop', 'arubacentral'));

        // ----- Reset clean stop flag
        config::save('clean_stop', '', 'arubacentral');

        return;
      }

      // ----- Not clean
      arubacentral::log('debug', "PlugIn was not clean stopped");
      
      
    }

    public function stop() {
      arubacentral::log('info', "Stop plugin OpenMQTTGateway");

      // ----- Set clean stop flag
      config::save('clean_stop', date("d-m-Y H:i"), 'arubacentral');
    }
    */

    public function preUpdate() {
    }

    public function postUpdate() {
    }

    public function preRemove() {
    }

    public function postRemove() {
    }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
     */
     /*
    public function toHtml($_version = 'dashboard') {
    
      // ----- Look for use of standard widget or not
      if (config::byKey('standard_widget', 'arubacentral') == 1) {
        return parent::toHtml($_version);
      }
      
      if ($this->acIsType('device')) {
      //$_version = 'mobile'; // dev trick
        if ($_version == 'dashboard') {
          return $this->toHtml_device($_version);
        }
        else if ($_version == 'mobile') {
//          return $this->toHtml_mobile_device($_version);
          return $this->toHtml_device($_version);
        }
        else {
          return parent::toHtml($_version);
        }
      }
      else {
        return $this->toHtml_gateway($_version);
      }


      
    }
*/

    /**---------------------------------------------------------------------------
     * Method : toHtml_gateway()
     * Description :
     *   
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
/*     
    public function toHtml_gateway($_version = 'dashboard') {
      //arubacentral::log('debug',  "Call toHtml_gateway()");

      $replace = $this->preToHtml($_version);

      if (!is_array($replace)) {
        return $replace;
      }      
      $version = jeedom::versionAlias($_version);
  

      //$replace['#name_display#'] = 'La Gateway Pilote';
     


      // postToHtml() : fait en fait le remplacement dans template + le cache du widget
      return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'arubacentral-gateway.template', __CLASS__)));  
    }
*/    
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : toHtml_device()
     * Description :
     *   
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
/*     
    public function toHtml_device($_version = 'dashboard') {
      //arubacentral::log('debug',  "Call toHtml_device()");

      $replace = $this->preToHtml($_version);

      if (!is_array($replace)) {
        return $replace;
      }      
      $version = jeedom::versionAlias($_version);
      
      //$replace['#cmd_confort_style#'] = '';

      // postToHtml() : fait en fait le remplacement dans template + le cache du widget
      return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'arubacentral-device.template', __CLASS__)));  
    }
*/    
    /* -------------------------------------------------------------------------*/


    /*
     * Non obligatoire permet d'associer une icone custom pour l'objet
     */
	public function getImage() {
      if ($this->acIsType('gateway')) {
        return 'plugins/arubacentral/plugin_info/arubacentral_icon.png';
      }
      
      // TBC
      $v_icon = '';
      
      $file = 'plugins/arubacentral/core/config/devices/'.$v_icon;
      if (($v_icon == '') || (!file_exists(__DIR__.'/../../../../'.$file))) {
        return 'plugins/arubacentral/plugin_info/arubacentral_icon.png';
      }
      return $file;
	}

    /*
     * Non obligatoire mais ca permet de déclencher une action avant modification de variable de configuration
    public static function preConfig_<Variable>($_value) {
      return($_value);
    }
    public static function postConfig_<Variable>($_value) {
    }
     */

    public static function preConfig_mqtt_base_topic($_value = null) {
      arubacentral::log('debug', 'Pre Changement topic : '.config::byKey('mqtt_base_topic', __CLASS__).', value = '.$_value);
      
      if (config::byKey('mqtt_base_topic', __CLASS__) == $_value) {
        arubacentral::log('debug', 'Pas de mise à jour dans plugIn MQTT2 nécessaire');
        return($_value);
      }
      
      arubacentral::log('debug', 'Change topic "'.$_value.'" dans plugIn MQTT2 ');

      if (!class_exists('mqtt2')) {
        throw new Exception(__("Plugin Mqtt Manager (mqtt2) non installé, veuillez l'installer avant de pouvoir continuer", __FILE__));
      }
      if(method_exists('mqtt2','removePluginTopicByPlugin')){
         mqtt2::removePluginTopicByPlugin(__CLASS__);
      }
      mqtt2::addPluginTopic(__CLASS__, $_value);
      
      return($_value);
    }
    
    /*
    public static function postConfig_mqtt_base_topic($_value = null) {
      arubacentral::log('debug', 'Changement topic : '.config::byKey('mqtt_base_topic', __CLASS__).', value = '.$_value);

      if (!class_exists('mqtt2')) {
        throw new Exception(__("Plugin Mqtt Manager (mqtt2) non installé, veuillez l'installer avant de pouvoir continuer", __FILE__));
      }
      if(method_exists('mqtt2','removePluginTopicByPlugin')){
         mqtt2::removePluginTopicByPlugin(__CLASS__);
      }
      mqtt2::addPluginTopic(__CLASS__, config::byKey('mqtt_base_topic', __CLASS__));
      
    }
    */

    



    /**---------------------------------------------------------------------------
     * Method : handleMqttMessage()
     * Description :
     * Parameters :
     *   
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function handleMqttMessage($p_datas) {
      arubacentral::log('debug', json_encode($p_datas));
        
      $v_topic = config::byKey('mqtt_base_topic', __CLASS__);
      if (!isset($p_datas[$v_topic])) {
        arubacentral::log('debug', 'Il manque le topic "'.$v_topic.'" dans les datas !');
        return;
      }
      
      foreach ($p_datas[$v_topic] as $v_key => $v_values) {
        arubacentral::log('debug', 'Received data for gateway "'.$v_key.'"');
        
        $v_gateway = arubacentral::acGatewayGetByTopic($v_key);
        if ($v_gateway === null) {
          arubacentral::log('debug', 'No gateway with this topic "'.$v_key.'"');
          
          if (arubacentral::acGatewayAutoDiscover()) {
            $v_gateway = arubacentral::acGatewayCreate($v_key, $v_values);
          }
          
          // TBC : Create new gateway en attendant next
          if ($v_gateway === null) continue;
        }
        
        $v_gateway->omgGatewayFlagRcvMqttMsg();
        
        if (isset($v_values['devices'])) {
          foreach ($v_values['devices'] as $v_id => $v_properties) {
            if (is_array($v_properties)) {
              arubacentral::log('debug', 'Device Id "'.$v_id.'"');
              
              if (($v_object = arubacentral::acDeviceGetByTopic($v_id)) !== null) {
                //arubacentral::log('debug', 'Is in the list -----------');
                $v_object->acDeviceUpdateAttributes($v_properties, $v_gateway);
              }
              else {
                //arubacentral::log('debug', 'Not in the list !');
                //arubacentral::omgDeviceCreate($v_id, $v_properties, $v_gateway);
                
                //if (arubacentral::omgInclusionIsOn()) {
                  arubacentral::omgInclusionLearn($v_id, $v_properties, $v_gateway);
                //}
              }
              
            }
            else {
              arubacentral::log('debug', 'Unexpected Gateway Attribut "'.$v_id.'" = "'.$v_properties.'"');
              //$v_gateway->omgGatewayUpdateBleAttribut($v_id, $v_properties);
            }
          }
        }
        
        if (isset($v_values['clients'])) {
          foreach ($v_values['clients'] as $v_id => $v_properties) {
            if (is_array($v_properties)) {
              arubacentral::log('debug', 'Client Id "'.$v_id.'"');
              
              if (($v_object = arubacentral::acClientGetByTopic($v_id)) !== null) {
                //arubacentral::log('debug', 'Is in the list -----------');
                $v_object->acClientUpdateAttributes($v_properties, $v_gateway);
              }
              else {
                 // arubacentral::omgInclusionLearn($v_id, $v_properties, $v_gateway);
              }
              
            }
            else {
              arubacentral::log('debug', 'Unexpected Gateway Attribut "'.$v_id.'" = "'.$v_properties.'"');
              //$v_gateway->omgGatewayUpdateBleAttribut($v_id, $v_properties);
            }
          }
        }
        
        if (isset($v_values['sys'])) {
          if ($v_gateway->acGetConf('prop_auto_discover')) {
            $v_gateway->acGatewayUpdateSysAttribut($v_values['sys']);
          }
        }
        
      }

    }
    /* -------------------------------------------------------------------------*/




    /*     * **********************Getteur Setteur*************************** */


    /**---------------------------------------------------------------------------
     * Method : acGetConf()
     * Description :
     *   Récupère la valeur stockée pour un attribut de configuration.
     *   Ou la valeur par defaut si absent.
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
	function acGetConf($p_key) {
	  return $this->getConfiguration($p_key, '');
	}
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : acGetType()
     * Description :
     *   Retourne l'un des 3 types majeurs d'équipement : 'gateway', 'device' ou 'zone'.
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
	function acGetType() {
	  return $this->getConfiguration('type', '');
	}
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : acIsType()
     * Description :
     *   example : if ($this->acIsType(array('device','zone')))
     *   ou if ($this->acIsType('device'))
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
	function acIsType($p_value) {
      $v_type = $this->acGetType();
      if (is_array($p_value)) {
        foreach ($p_value as $v_item) {
          if ($v_type == $v_item) {
            return(true);
          }
        }
      }
      else if ($v_type == $p_value) {
        return(true);
      }
      return(false);
	}
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgCmdCreateMandatory()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function omgCmdCreateMandatory($p_cmd_id) {
      if (!in_array($p_cmd_id, ['rssi','present'])) {
        return(null);
      }
      
      if ($p_cmd_id == 'present') {
        // ----- Create default online status command
        $v_cmd = $this->omgCmdCreate('present', ['name'=> __('Présent', __FILE__),
                                     'type'=>'info',
                                     'subtype'=>'binary', 
                                     'isHistorized'=>0, 
                                     'isVisible'=>0]);
        $this->checkAndUpdateCmd('present', 0);
      }

      elseif ($p_cmd_id == 'rssi') {
        // ----- Create default online status command
        $v_cmd = $this->omgCmdCreate('rssi', ['name'=>'rssi',
                                    'type'=>'info',
                                    'subtype'=>'numeric', 
                                    'isHistorized'=>0, 
                                    'isVisible'=>0]);
        $this->checkAndUpdateCmd('rssi', -199);
      }

	}
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgCmdCreate()
     * Description :
     *   omgCmdCreate('confort', ['name'=>'Confort', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>1]);
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function omgCmdCreate($p_cmd_id, $p_att_list=array()) {

      // ----- Look for existing command
      $v_cmd = $this->getCmd(null, $p_cmd_id);

      // ----- Look if command already exists in device
      if (is_object($v_cmd)) {
        arubacentral::log('debug', "Command '".$p_cmd_id."' already defined in device.");
        return($v_cmd);
      }

      // ----- Create Command

      arubacentral::log('debug', "Create Cmd '".$p_cmd_id."' for device '".$this->getName()."'.");
      $v_cmd = new arubacentralCmd();
      $v_cmd->setLogicalId($p_cmd_id);
      $v_cmd->setEqLogic_id($this->getId());
      
      foreach ($p_att_list as $v_key => $v_value) {
        if ($v_key == 'name') {
          $v_cmd->setName(__($v_value, __FILE__));
        }
        else if ($v_key == 'type') {
          $v_cmd->setType($v_value);
        }
        else if ($v_key == 'subtype') {
          $v_cmd->setSubType($v_value);
        }
        else if ($v_key == 'isHistorized') {
          $v_cmd->setIsHistorized($v_value);
        }
        else if ($v_key == 'isVisible') {
          $v_cmd->setIsVisible($v_value);
        }
        else if ($v_key == 'order') {
          $v_cmd->setOrder($v_value);
        }
        else if ($v_key == 'Unite') {
          $v_cmd->setUnite($v_value);
        }
        else if ($v_key == 'icon') {
          //$v_cmd->setDisplay('icon', '<i class="'.$v_value.'"></i>');
          $v_cmd->setDisplay('icon', $v_value);
          $v_cmd->setDisplay('showIconAndNamedashboard', "1");          
        }
      }


      /* Parametres de display des commandes :
      {"showStatsOnmobile":0,"showStatsOndashboard":0,"icon":"<i class=\"fab fa-hotjar \"><\/i>","showNameOndashboard":"1","showNameOnmobile":"1","showIconAndNamedashboard":"1","showIconAndNamemobile":"1","forceReturnLineBefore":"0","forceReturnLineAfter":"0","parameters":[]}
      fas fa-power-off
      fas fa-leaf
      far fa-snowflake
      */

/*
        if (isset($v_cmd_info['max_value'])) {
          $v_cmd->setConfiguration('maxValue', $v_cmd_info['max_value']);
        }
        if (isset($v_cmd_info['min_value'])) {
          $v_cmd->setConfiguration('minValue', $v_cmd_info['min_value']);
        }

        if (isset($v_cmd_info['generic_type']) && ($v_cmd_info['generic_type'] != '')) {
          $v_cmd->setGeneric_type($v_cmd_info['generic_type']);
        }
*/

      $v_cmd->save();
      
      return($v_cmd);
    }
    /* -------------------------------------------------------------------------*/




    /**---------------------------------------------------------------------------
     * Method : omgCmdGetValue()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
	public function omgCmdGetValue($p_cmd_logicalId) {
      if (!is_object($v_cmd = $this->getCmd(null, $p_cmd_logicalId))) {
        arubacentral::log('debug',  "Missing command '".$p_cmd_logicalId."' for equipement '".$this->getName()."'.");
        return('');
      }
      $v_value = $v_cmd->execCmd();
      return($v_value);
    }
    /* -------------------------------------------------------------------------*/    




    /**---------------------------------------------------------------------------
     * Method : acGatewayUpdateSysAttribut()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function acGatewayUpdateSysAttribut($p_attributes) {
      arubacentral::log('debug', "Update system properties for '".$this->getName()."' with ".json_encode($p_attributes));

      foreach ($p_attributes as $v_key => $v_value) {
        arubacentral::log('debug', "  Attribute '".$v_key."' = ".$v_value."");
        
        // ----- On regarde les attributs que l'on ne veut pas voir comme des commandes
        if (in_array($v_key, ['___att1','___att2'])) {
          // aucun pour l'intant
        }
        
        else {
          // ----- Look if command exists
          $v_cmd = $this->getCmd(null, $v_key);
          if (!is_object($v_cmd) && $this->acGetConf('prop_auto_discover')) {            
            $v_subtype = 'string';
            if (is_string($v_value)) $v_subtype = 'string';
            if (is_numeric($v_value)) $v_subtype = 'numeric';
            if (is_bool($v_value)) $v_subtype = 'binary';
            if (is_array($v_value)) $v_subtype = 'string';
            $v_is_visible = 0;
            if (in_array($v_key, ['SSID','ip'])) {$v_is_visible = 1;}
            
            $v_cmd = $this->omgCmdCreate($v_key, ['name'=>$v_key,
                                        'type'=>'info',
                                        'subtype'=>$v_subtype, 
                                        'isHistorized'=>0, 
                                        'isVisible'=>$v_is_visible]);
          }
          
          // ----- Update value
          if (is_object($v_cmd)) {
            if (is_array($v_value)) $v_value = json_encode($v_value);
            $this->checkAndUpdateCmd($v_key, $v_value);
          }
        }
      }

    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgGatewayFlagRcvMqttMsg()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function omgGatewayFlagRcvMqttMsg() {    
      $this->setStatus('last_rcv_mqtt', time());
      $this->omgGatewayChangeToOnline();
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgGatewayChangeToOnline()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function omgGatewayChangeToOnline() {
      if (($v_online_status = $this->omgCmdGetValue('online_status')) == 1) {
        return;
      }
      arubacentral::log('info', 'Gateway "'.$this->getName().'" passe en mode connectée.');
      $this->checkAndUpdateCmd('online_status', 1);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgGatewayChangeToOffline()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function omgGatewayChangeToOffline() {
      if (($v_online_status = $this->omgCmdGetValue('online_status')) == 0) {
        return;
      }
      arubacentral::log('warning', 'Gateway "'.$this->getName().'" passe en mode déconnectée.');
      $this->checkAndUpdateCmd('online_status', 0);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgGatewayCheckOnline()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function omgGatewayCheckOnline() {    
      arubacentral::log('debug', 'omgGatewayCheckOnline()');
      if (($v_online_status = $this->omgCmdGetValue('online_status')) == 0) {
        return;
      }
      
      $v_last_ts = $this->getStatus('last_rcv_mqtt');
      $v_timeout = 60*$this->acGetConf('online_timeout');
      
      //arubacentral::log('debug', 'omgGatewayCheckOnline() last_rcv_mqtt : '.$v_last_ts);
      //arubacentral::log('debug', 'omgGatewayCheckOnline() timeout : '.$v_timeout);
      //arubacentral::log('debug', 'omgGatewayCheckOnline() time() : '.time());
      
      if (($v_last_ts + $v_timeout) < time()) {
        $this->omgGatewayChangeToOffline();
      }
    }
    /* -------------------------------------------------------------------------*/


    /**---------------------------------------------------------------------------
     * Method : acDeviceUpdateAttributes()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function acDeviceUpdateAttributes($p_attributes, $p_gateway=null) {
      arubacentral::log('debug', "Update attributs for '".$this->getName()."' with ".json_encode($p_attributes));
      
      $this->omgDeviceFlagRcvMqttMsg();
      
      $v_save_device_flag = false;

      // ----- On regarde chaque categorie d'attribut  
      foreach ($p_attributes as $v_key => $v_value) {
      
        if ($v_key == 'stats') {
          $v_save_device_flag |= $this->acDeviceUpdateAttributesFromStats($v_value);           
        }
          
      }
            
      if ($v_save_device_flag) {
        $this->save();
      }
      
      arubacentral::log('debug', "Update attributs done");
    }
    /* -------------------------------------------------------------------------*/


    /**---------------------------------------------------------------------------
     * Method : acDeviceUpdateAttributesFromStats()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function acDeviceUpdateAttributesFromStats($p_attributes, $p_gateway=null) {
      arubacentral::log('debug', "Update Stats attributs for '".$this->getName()."' with ".json_encode($p_attributes));
      
      $v_save_device_flag = false;
      
      // ----- On regarde chaque attribut 
      foreach ($p_attributes as $v_key => $v_value) {
        arubacentral::log('debug', "  Attribute '".$v_key."' = ".$v_value."");
        
        // TBC : on verra plus tard si on veut filtrer certaines valeurs
        $v_att_type = 'cmd';
        if ($v_att_type == 'cmd') {

          $v_key = 'stat.'.$v_key;
          
          // ----- Look if command exists
          $v_cmd = $this->getCmd(null, $v_key);
          if (!is_object($v_cmd) && $this->acGetConf('cmd_auto_discover')) {           
            $v_subtype = 'string';
            if (is_string($v_value)) $v_subtype = 'string';
            if (is_numeric($v_value)) $v_subtype = 'numeric';
            $v_is_visible = 0;
            
            $v_cmd = $this->omgCmdCreate($v_key, ['name'=>$v_key,
                                        'type'=>'info',
                                        'subtype'=>$v_subtype, 
                                        'isHistorized'=>0, 
                                        'isVisible'=>$v_is_visible]);
          }
          
          // ----- Update value
          if (is_object($v_cmd)) {
            $this->checkAndUpdateCmd($v_key, $v_value);
          }
        }
                
      }
            
      arubacentral::log('debug', "Update attributs done");
      return($v_save_device_flag);
    }
    /* -------------------------------------------------------------------------*/


    /**---------------------------------------------------------------------------
     * Method : omgDeviceFlagRcvMqttMsg()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function omgDeviceFlagRcvMqttMsg() {    
      $this->setStatus($this->acGetType().'_last_rcv_mqtt', time());
      $this->omgDeviceChangeToOnline();
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgDeviceChangeToOnline()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function omgDeviceChangeToOnline() {
      $this->checkAndUpdateCmd('present', 1);
      $this->setStatus('danger', 0);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgDeviceChangeToOffline()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function omgDeviceChangeToOffline() {
      $this->checkAndUpdateCmd('present', 0);
      $this->setStatus('danger', 1);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgDeviceCheckOnline()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function omgDeviceCheckOnline() {    
      arubacentral::log('debug', 'omgDeviceCheckOnline()');
      
      $v_type = $this->acGetType();
      
      if (($v_present = $this->acGetConf($v_type.'_missing_detection')) == 0) {
        return;
      }
      
      if (($v_present = $this->omgCmdGetValue('present')) == 0) {
        return;
      }
      
      $v_last_ts = $this->getStatus($v_type.'_last_rcv_mqtt');
      $v_timeout = 60*$this->acGetConf($v_type.'_missing_timeout');
      
      //arubacentral::log('debug', 'omgDeviceCheckOnline() last_rcv_mqtt : '.$v_last_ts);
      //arubacentral::log('debug', 'omgDeviceCheckOnline() timeout : '.$v_timeout);
      //arubacentral::log('debug', 'omgDeviceCheckOnline() time() : '.time());
      
      if (($v_last_ts + $v_timeout) < time()) {
        $this->omgDeviceChangeToOffline();
      }
    }
    /* -------------------------------------------------------------------------*/


    /**---------------------------------------------------------------------------
     * Method : acClientCmdTransformset()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function acClientCmdTransformset($p_area, $p_key) {
    
      // ----- Valeurs  par defaut
      $v_cmd_prop = array();
      $v_cmd_prop['ignore'] = false;
      $v_cmd_prop['cmd_type'] = 'info';
      $v_cmd_prop['cmd_id'] = $p_area.'.'.$p_key;
      $v_cmd_prop['cmd_name'] = $p_area.'.'.$p_key;
      $v_cmd_prop['cmd_subType'] = '';
      $v_cmd_prop['cmd_isHistorized'] = 0;
      $v_cmd_prop['cmd_isVisible'] = 0;
    
      // ----- Overrides pour certains commandes
      $v_transformset_json = <<<MYTEXT
        {
            "state" : {
            
              "name" : {
                "ignore": false,
                "cmd_id": "name",
                "cmd_name": "name",
                "cmd_subType": "string"
              }

            },
            
            "stats" : {
            
              "speed" : {
                "ignore": false,
                "cmd_id": "speed",
                "cmd_name": "speed",
                "cmd_subType": "numeric"
              },
              "device_id" : {
                "ignore": false,
                "cmd_id": "associated_ap",
                "cmd_name": "associated_ap",
                "cmd_subType": "string"
              },
              "macaddr" : {
                "ignore": true
              }

            }
        }
        MYTEXT;
  
      $v_transformset_list = json_decode($v_transformset_json, true);
      if (json_last_error() != JSON_ERROR_NONE) {
        arubacentral::log('error', "Erreur dans le format json de la variable 'v_transformset_json' ".__FILE__.",".__LINE__." (".json_last_error_msg().")");
      }
      else {
        if (isset($v_transformset_list[$p_area]) && isset($v_transformset_list[$p_area][$p_key])) {
          $v_cmd_prop = array_merge($v_cmd_prop, $v_transformset_list[$p_area][$p_key]);
        }
      }
    
      return($v_cmd_prop);
    }
    /* -------------------------------------------------------------------------*/


    /**---------------------------------------------------------------------------
     * Method : acClientUpdateAttributes()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function acClientUpdateAttributes($p_attributes, $p_gateway=null) {
      arubacentral::log('debug', "Update attributs for '".$this->getName()."' with ".json_encode($p_attributes));
      
      $this->omgDeviceFlagRcvMqttMsg();
      
      $v_save_device_flag = false;

      // ----- On regarde chaque categorie d'attribut  
      foreach ($p_attributes as $v_key => $v_value) {
      
        if ($v_key == 'stats') {
          $v_save_device_flag |= $this->acClientUpdateAttributesFromStats($v_value);           
        }
          
        if ($v_key == 'location') {
          $v_save_device_flag |= $this->acClientUpdateAttributesFromLocation($v_value);           
        }
          
        if ($v_key == 'state') {
          $v_save_device_flag |= $this->acClientUpdateAttributesFromState($v_value);           
        }
          
      }
            
      if ($v_save_device_flag) {
        $this->save();
      }
      
      arubacentral::log('debug', "Update attributs done");
    }
    /* -------------------------------------------------------------------------*/


    /**---------------------------------------------------------------------------
     * Method : acClientUpdateAttributesFromStats()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function acClientUpdateAttributesFromStats($p_attributes, $p_gateway=null) {
      arubacentral::log('debug', "Update Stats attributs for '".$this->getName()."' with ".json_encode($p_attributes));
      
      $v_save_device_flag = false;
      
      // ----- On regarde chaque attribut 
      foreach ($p_attributes as $v_key => $v_value) {
        arubacentral::log('debug', "  Attribute '".$v_key."' = ".$v_value."");
        
        $v_cmd_prop = arubacentral::acClientCmdTransformset('stats', $v_key);
        if ($v_cmd_prop['cmd_subType'] == '') {
          $v_cmd_prop['cmd_subType'] = 'string';
          if (is_string($v_value)) $v_cmd_prop['cmd_subType'] = 'string';
          if (is_numeric($v_value)) $v_cmd_prop['cmd_subType'] = 'numeric';
        }

/*
        if (() == null) {
          $v_cmd_prop = array();
          $v_cmd_prop['ignore'] = false;
          $v_cmd_prop['cmd_id'] = 'stats_'.$v_key;
          $v_cmd_prop['cmd_name'] = $v_key;
          $v_cmd_prop['cmd_subType'] = 'string';
          if (is_string($v_value)) $v_cmd_prop['cmd_subType'] = 'string';
          if (is_numeric($v_value)) $v_cmd_prop['cmd_subType'] = 'numeric';
          $v_cmd_prop['cmd_isHistorized'] = 0;
          $v_cmd_prop['cmd_isVisible'] = 0;
        }
*/
                
        if ((!$v_cmd_prop['ignore']) && ($v_cmd_prop['cmd_type'] == 'info')) {

          // ----- Look if command exists
          $v_cmd = $this->getCmd(null, $v_cmd_prop['cmd_id']);
          if (!is_object($v_cmd) && $this->acGetConf('client_cmd_auto_discover')) {    
          /*       
            $v_subtype = 'string';
            if (is_string($v_value)) $v_subtype = 'string';
            if (is_numeric($v_value)) $v_subtype = 'numeric';
            $v_is_visible = 0;
            */
            
            $v_cmd = $this->omgCmdCreate($v_cmd_prop['cmd_id'], ['name'=>$v_cmd_prop['cmd_name'],
                                        'type'=>'info',
                                        'subtype'=>$v_cmd_prop['cmd_subType'], 
                                        'isHistorized'=>$v_cmd_prop['cmd_isHistorized'], 
                                        'isVisible'=>$v_cmd_prop['cmd_isVisible']]);
          }
          
          // ----- Update value
          if (is_object($v_cmd)) {
            $this->checkAndUpdateCmd($v_cmd_prop['cmd_id'], $v_value);
          }
        }
                
      }
            
      arubacentral::log('debug', "Update attributs done");
      return($v_save_device_flag);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : acClientUpdateAttributesFromLocation()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function acClientUpdateAttributesFromLocation($p_attributes, $p_gateway=null) {
      arubacentral::log('debug', "Update Location attributs for '".$this->getName()."' with ".json_encode($p_attributes));
      
      $v_save_device_flag = false;
      
      // ----- On regarde chaque attribut 
      foreach ($p_attributes as $v_key => $v_value) {
        arubacentral::log('debug', "  Attribute '".$v_key."' = ".$v_value."");
        
        $v_cmd_prop = arubacentral::acClientCmdTransformset('location', $v_key);
        
        // TBC : on verra plus tard si on veut filtrer certaines valeurs
        $v_att_type = 'cmd';
        if ($v_att_type == 'cmd') {

          $v_key = 'location.'.$v_key;
          
          // ----- Look if command exists
          $v_cmd = $this->getCmd(null, $v_key);
          if (!is_object($v_cmd) && $this->acGetConf('client_cmd_auto_discover')) {           
            $v_subtype = 'string';
            if (is_string($v_value)) $v_subtype = 'string';
            if (is_numeric($v_value)) $v_subtype = 'numeric';
            $v_is_visible = 0;
            
            $v_cmd = $this->omgCmdCreate($v_key, ['name'=>$v_key,
                                        'type'=>'info',
                                        'subtype'=>$v_subtype, 
                                        'isHistorized'=>0, 
                                        'isVisible'=>$v_is_visible]);
          }
          
          // ----- Update value
          if (is_object($v_cmd)) {
            $this->checkAndUpdateCmd($v_key, $v_value);
          }
        }
                
      }
            
      arubacentral::log('debug', "Update attributs done");
      return($v_save_device_flag);
    }
    /* -------------------------------------------------------------------------*/


    /**---------------------------------------------------------------------------
     * Method : acClientUpdateAttributesFromState()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function acClientUpdateAttributesFromState($p_attributes, $p_gateway=null) {
      arubacentral::log('debug', "Update State attributs for '".$this->getName()."' with ".json_encode($p_attributes));
      
      $v_save_device_flag = false;
      
      // ----- On regarde chaque attribut 
      foreach ($p_attributes as $v_key => $v_value) {
        arubacentral::log('debug', "  Attribute '".$v_key."' = ".$v_value."");
        
        // ----- On regarde si la nature du client est unknown
        if ((in_array($this->acGetConf('client_type'), ['unknown',''], true)) 
            && ($v_key == 'client_type')) {
          // TBC
          if ($v_value == 'wireless')
            $this->setConfiguration('client_type', 'wifi');
          $v_save_device_flag = true;
        }

        $v_cmd_prop = arubacentral::acClientCmdTransformset('state', $v_key);
        if ($v_cmd_prop['cmd_subType'] == '') {
          $v_cmd_prop['cmd_subType'] = 'string';
          if (is_string($v_value)) $v_cmd_prop['cmd_subType'] = 'string';
          if (is_numeric($v_value)) $v_cmd_prop['cmd_subType'] = 'numeric';
        }

/*
        if (($v_cmd_prop = arubacentral::acClientCmdTransformset('state', $v_key)) == null) {
          $v_cmd_prop = array();
          $v_cmd_prop['ignore'] = false;
          $v_cmd_prop['cmd_id'] = 'state_'.$v_key;
          $v_cmd_prop['cmd_name'] = $v_key;
          $v_cmd_prop['cmd_subType'] = 'string';
          if (is_string($v_value)) $v_cmd_prop['cmd_subType'] = 'string';
          if (is_numeric($v_value)) $v_cmd_prop['cmd_subType'] = 'numeric';
          $v_cmd_prop['cmd_isHistorized'] = 0;
          $v_cmd_prop['cmd_isVisible'] = 0;
        }
*/
                
        if ((!$v_cmd_prop['ignore']) && ($v_cmd_prop['cmd_type'] == 'info')) {

          // ----- Look if command exists
          $v_cmd = $this->getCmd(null, $v_cmd_prop['cmd_id']);
          if (!is_object($v_cmd) && $this->acGetConf('client_cmd_auto_discover')) {    
          /*       
            $v_subtype = 'string';
            if (is_string($v_value)) $v_subtype = 'string';
            if (is_numeric($v_value)) $v_subtype = 'numeric';
            $v_is_visible = 0;
            */
            
            $v_cmd = $this->omgCmdCreate($v_cmd_prop['cmd_id'], ['name'=>$v_cmd_prop['cmd_name'],
                                        'type'=>'info',
                                        'subtype'=>$v_cmd_prop['cmd_subType'], 
                                        'isHistorized'=>$v_cmd_prop['cmd_isHistorized'], 
                                        'isVisible'=>$v_cmd_prop['cmd_isVisible']]);
          }
          
          // ----- Update value
          if (is_object($v_cmd)) {
            $this->checkAndUpdateCmd($v_cmd_prop['cmd_id'], $v_value);
          }
        }
        
      }
            
      arubacentral::log('debug', "Update attributs done");
      return($v_save_device_flag);
    }
    /* -------------------------------------------------------------------------*/




}

class arubacentralCmd extends cmd {
    /*     * *************************Attributs****************************** */
    /* Commandes pour un device :
    *  De type'info' :
    *  mode : 'Confort', ...
    *  pilotage : 'manuel' ou 'prog'
    *  
    *  De type 'action' :
    *  
    */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {
        if ($this->getType() != 'action') {
			return;
		}                
        
        // ----- Get associated equipment
		$eqLogic = $this->getEqlogic();
                
        // ----- Get command logical id
        $v_logical_id = $this->getLogicalId();
        
        // ---- Look fos specific cmds per object type
        if ($eqLogic->acIsType('gateway')) {
          return($this->execute_gateway($eqLogic, $v_logical_id, $_options));
        }
        

		if ($v_logical_id == 'auto') {        
          //$eqLogic->cpPilotageChangeTo($v_logical_id);
		  return;
		}

        arubacentral::log('error', 'Unknown command '.$v_logical_id.' !');        
    }
    
    public function execute_gateway($p_gateway, $p_logical_id, $_options) {


     // $p_gateway->refreshWidget();
      

    }

    /*     * **********************Getteur Setteur*************************** */
}


