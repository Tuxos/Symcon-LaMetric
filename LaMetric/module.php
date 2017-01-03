<?

	class LaMetric extends IPSModule {

	public function Create() {

		parent::Create();

		$this->RegisterPropertyString("ipadress", "");
		$this->RegisterPropertyString("apikey", "");
		$this->RegisterPropertyInteger("intervall", "120");

		//erstelle Skript Hülle und kopiere die Daten der setdisplay.php hinein
		copy(IPS_GetKernelDir()."/modules/Symcon-LaMetric/LaMetric/setdisplay.php", IPS_GetKernelDir()."/scripts/LM_setdisplay.php");
		if (@IPS_GetScriptIDByName("setdisplay", $this->InstanceID) == false) {
			$ScriptID = IPS_CreateScript(0);
			IPS_SetParent ($ScriptID, $this->InstanceID);
			IPS_SetName($ScriptID, "setdisplay");
			IPS_SetHidden($ScriptID, true);
			IPS_SetScriptFile($ScriptID, "LM_setdisplay.php");
		}

		//erstelle Skript Hülle und kopiere die Daten der setbluetooth.php hinein
		copy(IPS_GetKernelDir()."/modules/Symcon-LaMetric/LaMetric/setbluetooth.php", IPS_GetKernelDir()."/scripts/LM_setbluetooth.php");
		if (@IPS_GetScriptIDByName("setbluetooth", $this->InstanceID) == false) {
			$ScriptID = IPS_CreateScript(0);
			IPS_SetParent ($ScriptID, $this->InstanceID);
			IPS_SetName($ScriptID, "setbluetooth");
			IPS_SetHidden($ScriptID, true);
			IPS_SetScriptFile($ScriptID, "LM_setbluetooth.php");
		}

		//erstelle Skript Hülle und kopiere die Daten der setvolume.php hinein
		copy(IPS_GetKernelDir()."/modules/Symcon-LaMetric/LaMetric/setvolume.php", IPS_GetKernelDir()."/scripts/LM_setvolume.php");
		if (@IPS_GetScriptIDByName("setvolume", $this->InstanceID) == false) {
			$ScriptID = IPS_CreateScript(0);
			IPS_SetParent ($ScriptID, $this->InstanceID);
			IPS_SetName($ScriptID, "setvolume");
			IPS_SetHidden($ScriptID, true);
			IPS_SetScriptFile($ScriptID, "LM_setvolume.php");
		}

	}

	public function ApplyChanges() {

		parent::ApplyChanges();

		$id = $this->RegisterVariableString("name", "Name", "~String",1);
		$id = $this->RegisterVariableString("osversion", "OS Version", "~String",2);
		$id = $this->RegisterVariableString("ssid", "SSID", "~String",3);
		$id = $this->RegisterVariableInteger("wlanconnection", "WLan Empfang", "~Intensity.100",4);
		$id = $this->RegisterVariableBoolean("bluetooth", "Bluetooth", "~Switch",5);
		$ScriptID = IPS_GetScriptIDByName("setbluetooth", $this->InstanceID);
		IPS_SetVariableCustomAction($id, $ScriptID);
		$id = $this->RegisterVariableString("bluetoothname", "Bluetooth Name", "~String",6);
		IPS_SetVariableCustomAction($id, $ScriptID);
		$id = $this->RegisterVariableInteger("volume", "Volume", "~Intensity.100",7);
		$ScriptID = IPS_GetScriptIDByName("setvolume", $this->InstanceID);
		IPS_SetVariableCustomAction($id, $ScriptID);
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

	//API POST function
	public function callapi(string $url, array $frames, string $putpost) {

		$apikey = $this->ReadPropertyString("apikey");
		$key = base64_encode("dev:".$apikey);

		$curl = curl_init();

		$headers = array(
			"Accept: application/json",
			"Content-Type: application/json",
			"Authorization: Basic ".$key,
			"Cache-Control: no-cache",
			);

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		//curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $putpost);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($frames));

		curl_exec($curl);
		curl_close($curl);

	}

	//API PUT function
	public function callapiput(string $url, array $frames) {

		$apikey = $this->ReadPropertyString("apikey");
		$key = base64_encode("dev:".$apikey);

		$curl = curl_init();

		$headers = array(
			"Accept: application/json",
			"Content-Type: application/json",
			"Authorization: Basic ".$key,
			"Cache-Control: no-cache",
				);

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($frames));

		curl_exec($curl);
		curl_close($curl);
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

		if (file_exists(IPS_GetKernelDir()."/scripts/LM_setdisplay.php") == false) {
				copy(IPS_GetKernelDir()."/modules/Symcon-LaMetric/LaMetric/setdisplay.php", IPS_GetKernelDir()."/scripts/LM_setdisplay.php");
				copy(IPS_GetKernelDir()."/modules/Symcon-LaMetric/LaMetric/setbluetooth.php", IPS_GetKernelDir()."/scripts/LM_setbluetooth.php");
				copy(IPS_GetKernelDir()."/modules/Symcon-LaMetric/LaMetric/setvolume.php", IPS_GetKernelDir()."/scripts/LM_setvolume.php");
			}
	}


	// Gibt eine Nachricht auf LaMetric aus
	public function notification($notification, $icon, $sound) {

		$ip = $this->ReadPropertyString("ipadress");

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

		LM_callapi($this->InstanceID, $url, $frames, "POST");

	}

	// Gibt einen Alarm auf LaMetric aus
	public function alarm($notification, $icon, $sound, $repeat) {

		$ip = $this->ReadPropertyString("ipadress");

		$url = "http://".$ip.":8080/api/v2/device/notifications";

		if ( $sound != "") {
			$frames = array(
			"priority" => "critical",
			"icon_type" => "alert",
			"model" => array(
			"cycles" => 0,
			"frames" => array(
				array(
					"icon" => $icon,
					"text" => $notification
						)
					),
			"sound" => array(
			"category" => "alarms",
			"id" => $sound,
			"repeat" => $repeat
					)
				));
			}
			else
			{
			$frames = array(
				"priority" => "critical",
				"icon_type" => "alert",
				"model" => array(
				"cycles" => 0,
				"frames" => array(
				array(
					"icon" => $icon,
					"text" => $notification
				)
				),
			));
			}

		LM_callapi($this->InstanceID, $url, $frames, "POST");

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

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($frames));

		curl_exec($curl);
		curl_close($curl);

	}


	// Bluetooth Konfiguration

	public function bluetooth(string $btname, boolean $btactive) {

	$ip = $this->ReadPropertyString("ipadress");
	$apikey = $this->ReadPropertyString("apikey");
	$key = base64_encode("dev:".$apikey);

	$url = "http://".$ip.":8080/api/v2/device/bluetooth";

	if ($btactive == 1) {
		$btactive = true;
		}
		else
		{
		$btactive = false;
		}


	$frames = array(
		"active" => $btactive,
		"name" => $btname
			);

	$curl = curl_init();

	$headers = array(
		"Accept: application/json",
		"Content-Type: application/json",
		"Authorization: Basic ".$key,
		"Cache-Control: no-cache",
			);

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($frames));

	curl_exec($curl);
	curl_close($curl);

	}


	// Lautstärke Konfiguration

	public function volume(integer $volume) {

	$ip = $this->ReadPropertyString("ipadress");
	$apikey = $this->ReadPropertyString("apikey");
	$key = base64_encode("dev:".$apikey);

	$url = "http://".$ip.":8080/api/v2/device/audio";

	$frames = array(
		"volume" => $volume
			);

	$curl = curl_init();

	$headers = array(
		"Accept: application/json",
		"Content-Type: application/json",
		"Authorization: Basic ".$key,
		"Cache-Control: no-cache",
			);

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($frames));

	curl_exec($curl);
	curl_close($curl);

	}

    }
?>
