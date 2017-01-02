# LaMetric Time Modul für IPSymcon

Mit dem LaMetric Modul kann die LaMetric Time von Smart Atoms einfach in IPSymcon eingebunden werden.
Sobald das Modul in IPSymcon installiert ist, kann man eine neue LaMetric Instanz hinzufügen.

Für die erstmalige Konfiguration wird die IP Adresse der LaMetric Time, der API Key und ein Aktualisierungs Intervall benötigt.

Die IP Adresse findet man in der LaMetric Smartphone/Tablet App unter EINSTELLUNGEN --> KONNEKTIVITÄT.
Den API Key bekommt man unter: https://developer.lametric.com/user/devices.
Das Aktualisierungs Intervall legt fest alle wieviel Sekunden die Konfigurations Daten aus der LaMetric Time ausgelesen und in die IPSymcon Variablen geschrieben werden (mittels `LM_readdata();`).

Die LaMetric Instanz lässt sich einfach per Link ins WebFront einbinden. Alle Konfigurierbaren Parameter können direkt im WebFront eingestellt werden.

Jede Kommunikation bleibt im internen Netz und geht direkt auf die LaMetric Time API und nicht über die Cloud API.

Die Variablen dürfen nicht umbenannt werden, da geprüft wird ob diese unter diesem Namen existieren. Wenn sie im Webfront einen anderen Namen haben sollen, bitte einen Link auf die Variablen setzen und diesen den gewünschten Namen geben.

Ein bekannter Bug ist es, dass wenn man direkt an der LaMetric die Lautstärke per Taste ändert, der Wert in der App als auch in der API (sprich auch in IPSymcon) nicht aktualisiert angezeigt wird. Es wird der letzte bekannte Wert angezeigt. Wenn die Lautstärke per App oder API geändert wird, zeigt er die richtige Lautstärke an. Der Bug ist Smart Atoms gemeldet worden.

Da dies mein erstes IPSymcon Modul ist, bitte ich um Nachsicht wenn etwas nicht 100% funktionieren sollte oder der Code schlecht ist. Gerne nehme ich Verbesserungsvorschläge an :-)

Link zum IPSymcon Forum Thread: https://www.symcon.de/forum/threads/33536-LaMetric-Time-Modul

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
##Einen Alarm auf einer LaMetric Time ausgeben
Ein Alarm wird solange angezeigt bis er an der LaMetric bestätigt wird.

Befehl: `LM_alarm(instanz-id, notification, icon, sound, repeat);`

###instanz-id
Die Objekt-ID der LaMetric Time.

###notification
Die Nachricht die angezeigt werden soll.

###icon
Die Nummer des Icons mit führendem "i".
Die zur Verfügung stehenden Icons können unter https://developer.lametric.com/icons eingesehen werden.
Wenn kein Icon angezeigt werden soll kann das entsprechende Feld leer gelassen werden. "" sind notwendig.

###sound
Unter http://lametric-documentation.readthedocs.io/en/latest/reference-docs/device-notifications.html findet sich eine Liste an Sounds. Nur die der alarm id funktionieren.
Wenn kein Sound abgespielt werden soll kann das entsprechende Feld leer gelassen werden. "" sind notwendig.

###repeat
Wie häufig der Sound gespielt werden soll. 0 = bis der Alarm auf der LaMetric bestätigt wird.

###Beispiel
```
<?
  LM_alarm(49941 /*[Devices\LaMetric\LaMetric Büro]*/, "Die Hütte Brennt!!!", "i1003", "alarm6", 0);
?>
```


##Lautstärke konfigurieren
Befehl: `LM_volume(instanz-id, volume);`

###instanz-id
Die Objekt-ID der LaMetric Time.

###volume
Lautstärke von 0-100 (0=aus,100=max. Lautstärke).

###Beispiel
```
<?
  LM_volume(49941 /*[Devices\LaMetric\LaMetric Büro]*/, 50);
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
