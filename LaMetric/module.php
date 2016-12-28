<?
    class LaMetric extends IPSModule {
 
        public function Create() {
            // Diese Zeile nicht löschen.
            parent::Create();

            $this->RegisterPropertyString("ipadress", "");
            $this->RegisterPropertyString("apikey", "");
 
        }

        public function ApplyChanges() {
            // Diese Zeile nicht löschen
            parent::ApplyChanges();

	    $id = $this->RegisterVariableString("name", "Name", "~String",0);
            $id = $this->RegisterVariableString("osversion", "OS Version", "~String",1);
            $id = $this->RegisterVariableString("ssid", "SSID", "~String",2);
	    $id = $this->RegisterVariableInteger("wlanconnection", "WLan Empfang", "~Intensity.100",3);
	    $id = $this->RegisterVariableBoolean("bluetooth", "Bluetooth", "~Switch",4);
	    $id = $this->RegisterVariableString("bluetoothname", "Bluetooth Name", "~String",5);
	    $id = $this->RegisterVariableInteger("volume", "Volume", "~Intensity.100",6);
	    $id = $this->RegisterVariableInteger("brightness", "Helligkeit", "~Intensity.100",7);
	    $id = $this->RegisterVariableBoolean("brightnessautomode", "Helligkeit Auto Modus", "~Switch",8);

        }
 
        public function readdata($modulid) {

		$ip = $this->ReadPropertyString("ipadress");	
		$apikey = $this->ReadPropertyString("apikey");
		$key = base64_encode("dev:".$apikey);

		$url = "http://".$ip.":8080/api/v2/device";

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

		 $response = curl_exec($curl);
                 curl_close($curl);

	         $data = json_decode($response);

	         if ($data->display->brightness_mode == "auto") { $mode=true; } else { $mode=false; };


                 $nameid = IPS_GetVariableIDByName("Name", $modulid);

	         // SetValue(24351 /*[Devices\LaMetric\LaMetric Esszimmer\Volume]*/, $data->audio->volume);
	         // SetValue(14287 /*[Devices\LaMetric\LaMetric Esszimmer\Helligkeit]*/,$data->display->brightness);
	         // SetValueBoolean(56327 /*[Devices\LaMetric\LaMetric Esszimmer\Helligkeit Modus]*/,$mode);
	         // SetValueBoolean(22331 /*[Devices\LaMetric\LaMetric Esszimmer\Bluetooth]*/,$data->bluetooth->active);
	         // SetValue(11350 /*[Devices\LaMetric\LaMetric Esszimmer\Bluetooth Name]*/,$data->bluetooth->name);
	         SetValue($nameid,$data->name);
	         // SetValue(29999 /*[Devices\LaMetric\LaMetric Esszimmer\OS Version]*/,$data->os_version);
	         // SetValue(19392 /*[Devices\LaMetric\LaMetric Esszimmer\SSID]*/,$data->wifi->essid);
	         // SetValue(51507 /*[Devices\LaMetric\LaMetric Esszimmer\WLan Empfang]*/,$data->wifi->strength);
	
	}
    }
?>
