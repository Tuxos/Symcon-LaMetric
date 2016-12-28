<?
    class LaMetric extends IPSModule {
 
        public function Create() {
            // Diese Zeile nicht löschen.
            parent::Create();

            $this->RegisterPropertyString("ipadress", "192.168.1.99");
            $this->RegisterPropertyString("apikey", "884d3d4b48b979092f2b0b866efd6a6c6f3412d2dcfd454ce747359ed1fb99c1");
 
        }

        public function ApplyChanges() {
            // Diese Zeile nicht löschen
            parent::ApplyChanges();
		
            // $this->RegisterProfile(1,"WITHINGS_M_Groesse"  ,"Gauge"  ,""," cm");

	    $this->RegisterProfile(0, "LaMetric-brightness-mode", "Light", "", "", Array(
							Array(0, "Manual", "", 0x0000FF),
							Array(1, "Auto", "", 0xFF0000)
						));

            $id = $this->RegisterVariableString("osversion", "OS Version", "~String",0);
            $id = $this->RegisterVariableString("ssid", "SSID", "~String",1);
	    $id = $this->RegisterVariableInteger("wlanconnection", "WLan Empfang", "~Intensity.100",2);
	    $id = $this->RegisterVariableBoolean("bluetooth", "Bluetooth", "~Switch",3);
	    $id = $this->RegisterVariableString("bluetoothname", "Bluetooth Name", "~String",4);
	    $id = $this->RegisterVariableInteger("volume", "Volume", "~Intensity.100",5);
	    $id = $this->RegisterVariableInteger("brightness", "Helligkeit", "~Intensity.100",6);
	    $id = $this->RegisterVariableBoolean("brightnessmode", "Helligkeit Modus", "LaMetric-brightness-mode",7);

        }
 
        /**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        * ABC_MeineErsteEigeneFunktion($id);
        *
        */
        public function MeineErsteEigeneFunktion() {
            // Selbsterstellter Code
        }
	    
    //**************************************************************************
    //  0 - Bool
    //  1 - Integer
    //  2 - Float
    //  3 - String
    //**************************************************************************    
    protected function RegisterProfile($Typ, $Name, $Icon, $Prefix, $Suffix, $MinValue=false, $MaxValue=false, $StepSize=false, $Digits=0) 
      {
      if(!IPS_VariableProfileExists($Name)) 
        {
        IPS_CreateVariableProfile($Name, $Typ);  
        } 
      else 
        {
        $profile = IPS_GetVariableProfile($Name);
        if($profile['ProfileType'] != $Typ)
          throw new Exception("Variable profile type does not match for profile ".$Name);
        }
        
      IPS_SetVariableProfileIcon($Name, $Icon);
      IPS_SetVariableProfileText($Name, $Prefix, $Suffix);
      IPS_SetVariableProfileValues($Name, $MinValue, $MaxValue, $StepSize);
		  if ( $Typ == 2 )
			 IPS_SetVariableProfileDigits($Name, $Digits);
      }
	    
    }
?>
