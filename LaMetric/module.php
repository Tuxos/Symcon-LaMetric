<?

	class LaMetric extends IPSModule {
 
	public function Create() {

		parent::Create();

		$this->RegisterPropertyString("ipadress", "");
		$this->RegisterPropertyString("apikey", "");
		$this->RegisterPropertyInteger("intervall", "60");
	
		$ScriptID = IPS_CreateScript(0);
		IPS_SetParent ($ScriptID, $this->InstanceID); 
		IPS_SetName($ScriptID, "setdisplay");
		IPS_SetHidden($ScriptID, true);
		copy("../modules/Symcon-LaMetric/LaMetric/setdisplay.php", "./setdisplay.php");
		IPS_SetScriptFile($ScriptID, "./setdisplay.php");
 
	}

	public function ApplyChanges() {

	parent::ApplyChanges();

	$id = $this->RegisterVariableString("name", "Name", "~String",1);
	$id = $this->RegisterVariableString("osversion", "OS Version", "~String",2);
	$id = $this->RegisterVariableString("ssid", "SSID", "~String",3);
	$id = $this->RegisterVariableInteger("wlanconnection", "WLan Empfang", "~Intensity.100",4);
	$id = $this->RegisterVariableBoolean("bluetooth", "Bluetooth", "~Switch",5);
	$id = $this->RegisterVariableString("bluetoothname", "Bluetooth Name", "~String",6);
	$id = $this->RegisterVariableInteger("volume", "Volume", "~Intensity.100",7);
	$id = $this->RegisterVariableInteger("brightness", "Helligkeit", "~Intensity.100",8);
	$ScriptID = IPS_GetScriptIDByName("setdisplay", $this->InstanceID);	
	IPS_SetVariableCustomAction($id, $ScriptID);
	$id = $this->RegisterVariableBoolean("brightnessautomode", "Helligkeit Auto Modus", "~Switch",9);
	IPS_SetVariableCustomAction($id, $ScriptID);
	$this->RegisterTimer('ReadData', $this->ReadPropertyInteger("intervall"), 'LM_readdata($id)');


	if (($this->ReadPropertyString("ipadress") != "") and ($this->ReadPropertyString("apikey") != ""))
		{
			$this->SetStatus(102);
		}

	}
 

	// Erstelle Events

	protected function RegisterTimer($ident, $interval, $script) {

		$id = @IPS_GetObjectIDByIdent($ident, $this->InstanceID);

		if ($id && IPS_GetEvent($id)['EventType'] <> 1) {
			IPS_DeleteEvent($id);
			$id = 0;
			}

		if (!$id) {
		$id = IPS_CreateEvent(1);
		IPS_SetParent($id, $this->InstanceID);
		IPS_SetIdent($id, $ident);
		}
		
		IPS_SetName($id, $ident);
		IPS_SetHidden($id, true);
		IPS_SetEventScript($id, "\$id = \$_IPS['TARGET'];\n$script;");
		if (!IPS_EventExists($id)) throw new Exception("Ident with name $ident is used for wrong object type");
		
		if (!($interval > 0)) {
			IPS_SetEventCyclic($id, 0, 0, 0, 0, 1, 1);
			IPS_SetEventActive($id, false);
		} else {
			IPS_SetEventCyclic($id, 0, 0, 0, 0, 1, $interval);
			IPS_SetEventActive($id, true);
		}
	}

	// Lese alle Configurationsdaten aus

        public function readdata() {

		$ip = $this->ReadPropertyString("ipadress");	
		$apikey = $this->ReadPropertyString("apikey");
		$key = base64_encode("dev:".$apikey);

		$url = "http://".$ip.":8080/api/v2/device";

		$curl = curl_init();

		$headers = array(
			"Accept: application/json",
			"Content-Type: application/json",
			"Authorization: Basic ".$key,
			"Cache-Control: no-cache"
			);

		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		$response = curl_exec($curl);
		curl_close($curl);

		$data = json_decode($response);

		if ($data->display->brightness_mode == "auto") { $mode=true; } else { $mode=false; };
		 
		SetValue(IPS_GetObjectIDByName("Volume", $this->InstanceID), $data->audio->volume);
		SetValue(IPS_GetObjectIDByName("Helligkeit", $this->InstanceID),$data->display->brightness);
		SetValueBoolean(IPS_GetObjectIDByName("Helligkeit Auto Modus", $this->InstanceID),$mode);
		SetValueBoolean(IPS_GetObjectIDByName("Bluetooth", $this->InstanceID),$data->bluetooth->active);
		SetValue(IPS_GetObjectIDByName("Bluetooth Name", $this->InstanceID),$data->bluetooth->name);
		SetValue(IPS_GetObjectIDByName("Name", $this->InstanceID),$data->name);
		SetValue(IPS_GetObjectIDByName("OS Version", $this->InstanceID),$data->os_version);
		SetValue(IPS_GetObjectIDByName("SSID", $this->InstanceID),$data->wifi->essid);
		SetValue(IPS_GetObjectIDByName("WLan Empfang", $this->InstanceID),$data->wifi->strength);
	}


	// Gibt eine Nachricht auf LaMetric aus

	public function notification($notification, $icon, $sound) {

		$ip = $this->ReadPropertyString("ipadress");
		$apikey = $this->ReadPropertyString("apikey");
		$key = base64_encode("dev:".$apikey);

		$url = "http://".$ip.":8080/api/v2/device/notifications";

		if ( $sound != "") {
			$frames = array(
			"priority" => "info",
			"icon_type" => "info",
			"model" => array(
			"cycles" => 1,
			"frames" => array(
				array(
					"icon" => $icon,
					"text" => $notification
						)
					),
			"sound" => array(
			"category" => "notifications",
			"id" => $sound,
			"repeat" => 1
					)
				));
			}
			else
			{
			$frames = array(
				"priority" => "info",
				"icon_type" => "info",
				"model" => array(
				"cycles" => 1,
				"frames" => array(
				array(
					"icon" => $icon,
					"text" => $notification
					)
				),
			));
			}

		$curl = curl_init();

		$headers = array(
			"Accept: application/json",
			"Content-Type: application/json",
			"Authorization: Basic ".$key,
			"Cache-Control: no-cache",
			);

		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($frames));
		
		$response = curl_exec($curl);

		curl_close($curl);

	}

	// Display Konfiguration

	public function display(integer $helligkeit,boolean $modus) {
	
		$ip = $this->ReadPropertyString("ipadress");	
		$apikey = $this->ReadPropertyString("apikey");
		$key = base64_encode("dev:".$apikey);
		$url = "http://".$ip.":8080/api/v2/device/display";

		if ($modus == 1) {
			$modus = "auto";
			}
			else
			{
			$modus = "manual";
			}

		$frames = array(
			"brightness" => $helligkeit,
			"brightness_mode" => $modus,
				);

		$curl = curl_init();

		$headers = array(
			"Accept: application/json",
			"Content-Type: application/json",
			"Authorization: Basic ".$key,
			"Cache-Control: no-cache",
				);

		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($frames));

		$response = curl_exec($curl);

		curl_close($curl);	

	}

    }
?>
