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

            $id = $this->RegisterVariableString("ipadress"       , "IP Adresse"      ,"~String",0);
            $id = $this->RegisterVariableString("apikey"       , "API Key"      ,"~String",1);

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
