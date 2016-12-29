# Symcon-LaMetric
IP-Symcon module for LaMetric


Notifications an eine LaMetric Time senden
------------------------------------------

Befehl: LM_notification(instanz-id, notification, icon, sound);

[instanz-id]
Die Objekt-ID der LaMetric Time.

[notification]
Die Nachricht die angezeigt werden soll.

[icon]
Die Nummer des Icons mit führendem "i".
Die zur Verfügung stehenden Icons können unter https://developer.lametric.com/icons eingesehen werden.
Wenn kein Icon angezeigt werden soll kann das entsprechende Feld leer gelassen werden. "" sind notwendig.

[sound]
Unter http://lametric-documentation.readthedocs.io/en/latest/reference-docs/device-notifications.html findet sich eine Liste an Sounds. Nur die der notification id funktionieren.
Wenn kein Sound abgespielt werden soll kann das entsprechende Feld leer gelassen werden. "" sind notwendig.

Beispiel:
----------------

LM_notification(49941 /*[Devices\LaMetric\LaMetric Büro]*/, "Hallo Welt!", "i43", "car");

----------------
