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
    }
?>
