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

	    $this->RegisterProfile("LaMetric-brightness-mode", "Light", "", "", 0 /* Boolean */, Array(
							Array(0, "Manual", "", 16711680),
							Array(1, "Auto", "", 65280)
						));

            $id = $this->RegisterVariableString("osversion", "OS Version", "~String",0);
            $id = $this->RegisterVariableString("ssid", "SSID", "~String",1);
	    $id = $this->RegisterVariableInteger("wlanconnection", "WLan Empfang", "~Intensity.100",2);
	    $id = $this->RegisterVariableBoolean("bluetooth", "Bluetooth", "~Switch",3);
	    $id = $this->RegisterVariableString("bluetoothname", "Bluetooth Name", "~String",4);
	    $id = $this->RegisterVariableInteger("volume", "Volume", "~Intensity.100",5);
	    $id = $this->RegisterVariableInteger("brightness", "Helligkeit", "~Intensity.100",6);
	    $id = $this->RegisterVariableInteger("brightnessmode", "Helligkeit Modus", "LaMetric-brightness-mode",7);

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
