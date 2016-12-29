<?

	class LaMetric extends IPSModule {
 
	public function Create() {

		parent::Create();

		$this->RegisterPropertyString("ipadress", "");
		$this->RegisterPropertyString("apikey", "");
		$this->RegisterPropertyInteger("intervall", "60");
 
	}

	public function ApplyChanges() {

	parent::ApplyChanges();

	$intervall = $this->ReadPropertyInteger("intervall") * 1000;

	$id = $this->RegisterVariableString("name", "Name", "~String",0);
	$id = $this->RegisterVariableString("osversion", "OS Version", "~String",1);
	$id = $this->RegisterVariableString("ssid", "SSID", "~String",2);
	$id = $this->RegisterVariableInteger("wlanconnection", "WLan Empfang", "~Intensity.100",3);
	$id = $this->RegisterVariableBoolean("bluetooth", "Bluetooth", "~Switch",4);
	$id = $this->RegisterVariableString("bluetoothname", "Bluetooth Name", "~String",5);
	$id = $this->RegisterVariableInteger("volume", "Volume", "~Intensity.100",6);
	$id = $this->RegisterVariableInteger("brightness", "Helligkeit", "~Intensity.100",7);
	$id = $this->RegisterVariableBoolean("brightnessautomode", "Helligkeit Auto Modus", "~Switch",8);
	
	$eventid = $this->RegisterTimer('ReadData', $intervall, 'LM_readdata($id)');
	IPS_SetEventScript($eventid, "LM_readdata($id)");

	if (($this->ReadPropertyString("ipadress") != "") and ($this->ReadPropertyString("apikey") != ""))
		{
			$this->SetStatus(102);
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

    }
?>
