# LaMetric Time Modul für IP-Symcon

Mit dem LaMetric Modul kann die LaMetric Time von Smart Atoms einfach in IP-Symcon eingebunden werden.
Sobald das Modul in IP-Symcon installiert ist, kann man eine neue LaMetric Instanz hinzufügen.

Für die erstmalige Konfiguration wird die IP Adresse der LaMetric Time und der API Key benötigt.

Die IP Adresse findet man in der LaMetric Smartphone/Tablet App unter EINSTELLUNGEN --> KONNEKTIVITÄT.
Den API Key bekommt man unter: https://developer.lametric.com/user/devices.

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

Beispiel:
```
<?
  LM_notification(49941 /*[Devices\LaMetric\LaMetric Büro]*/, "Hallo Welt!", "i43", "car");
?>
```
