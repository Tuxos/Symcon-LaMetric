# LaMetric Time Modul für IPSymcon

Mit dem LaMetric Modul kann die LaMetric Time von Smart Atoms einfach in IPSymcon eingebunden werden.
Sobald das Modul in IPSymcon installiert ist, kann man eine neue LaMetric Instanz hinzufügen.

Für die erstmalige Konfiguration wird die IP Adresse der LaMetric Time, der API Key und ein Aktualisierungs Intervall benötigt.

Die IP Adresse findet man in der LaMetric Smartphone/Tablet App unter EINSTELLUNGEN --> KONNEKTIVITÄT.
Den API Key bekommt man unter: https://developer.lametric.com/user/devices.
Das Aktualisierungs Intervall legt fest alle wieviel Sekunden die Konfigurations Daten aus der LaMetric Time ausgelesen und in die IPSymcon Variablen geschrieben werden (mittels `LM_readdata();`).

Die LaMetric Instanz lässt sich einfach per Link ins WebFront einbinden. Alle Konfigurierbaren Parameter können direkt im WebFront eingestellt werden.

Ein bekannter Bug ist es, dass wenn man direkt an der LaMetric die Lautstärke per Taste ändert, der Wert in der App als auch in der API (sprich auch in IPSymcon) nicht aktualisiert angezeigt wird. Es wird der letzte bekannte Wert angezeigt. Wenn die Lautstärke per App oder API geändert wird, zeigt er die richtige Lautstärke an. Der Bug ist Smart Atoms gemeldet worden.

##Notifications an eine LaMetric Time senden
Befehl: `LM_notification(instanz-id, notification, icon, sound);`

###instanz-id
Die Objekt-ID der LaMetric Time.

###notification
Die Nachricht die angezeigt werden soll.

###icon
Die Nummer des Icons mit führendem "i".
Die zur Verfügung stehenden Icons können unter https://developer.lametric.com/icons eingesehen werden.
Wenn kein Icon angezeigt werden soll kann das entsprechende Feld leer gelassen werden. "" sind notwendig.

###sound
Unter http://lametric-documentation.readthedocs.io/en/latest/reference-docs/device-notifications.html findet sich eine Liste an Sounds. Nur die der notification id funktionieren.
Wenn kein Sound abgespielt werden soll kann das entsprechende Feld leer gelassen werden. "" sind notwendig.

###Beispiel
```
<?
  LM_notification(49941 /*[Devices\LaMetric\LaMetric Büro]*/, "Hallo Welt!", "i43", "car");
?>
```

##Display konfigurieren
Befehl: `LM_display(instanz-id, brightness, mode);`

###instanz-id
Die Objekt-ID der LaMetric Time.

###brightness
Helligkeit von 0-100 (0=aus,100=volle Helligkeit).

###mode
Helligkeits Sensor Steuerung an oder aus (true, false).

###Beispiel
```
<?
  LM_display(49941 /*[Devices\LaMetric\LaMetric Büro]*/, 50, false);
?>
```

##Bluetooth konfigurieren
Befehl: `LM_bluetooth(instanz-id, bluetooth-name, aktiv);`

###instanz-id
Die Objekt-ID der LaMetric Time.

###bluetooth-name
Unter diesen Namen erscheid die LaMetric Time bei anderen Bluetooth Geräten.

###aktiv
Bluetooth an oder aus (true, false).

###Beispiel
```
<?
  LM_bluetooth(49941 /*[Devices\LaMetric\LaMetric Büro]*/, "LaMetric-Büro", false);
?>
```

##Lese Konfiguration der LaMetric Time ein und schreibe sie in die vorgesehenen IPSymcon Variablen
Befehl: `LM_readdata(instanz-id);`

###instanz-id
Die Objekt-ID der LaMetric Time.

###Beispiel
```
<?
  LM_readdata(49941 /*[Devices\LaMetric\LaMetric Büro]*/ );
?>
```
