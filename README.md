# 📸 Image Viewer Tile / Bildbetrachter Kachel

[![Version](https://img.shields.io/badge/Symcon-PHP--Modul-red.svg?style=flat-square)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
[![Product](https://img.shields.io/badge/Symcon%20Version-7.2-blue.svg?style=flat-square)](https://www.symcon.de/produkt/)
[![Version](https://img.shields.io/badge/Modul%20Version-1.0.20250729-orange.svg?style=flat-square)](https://github.com/Wilkware/ImageViewer)
[![License](https://img.shields.io/badge/License-CC%20BY--NC--SA%204.0-green.svg?style=flat-square)](https://creativecommons.org/licenses/by-nc-sa/4.0/)
[![Actions](https://img.shields.io/github/actions/workflow/status/wilkware/ImageViewer/ci.yml?branch=main&label=CI&style=flat-square)](https://github.com/Wilkware/ImageViewer/actions)

Mit diesem Modul können Sie Einzelbilder und Motion-JPEG-Streams direkt in einer vollflächigen Kachel anzeigen.

## Inhaltverzeichnis

1. [Funktionsumfang](#user-content-1-funktionsumfang)
2. [Voraussetzungen](#user-content-2-voraussetzungen)
3. [Installation](#user-content-3-installation)
4. [Einrichten der Instanzen in IP-Symcon](#user-content-4-einrichten-der-instanzen-in-ip-symcon)
5. [Statusvariablen und Profile](#user-content-5-statusvariablen-und-profile)
6. [Visualisierung](#user-content-6-visualisierung)
7. [PHP-Befehlsreferenz](#user-content-7-php-befehlsreferenz)
8. [Versionshistorie](#user-content-8-versionshistorie)

### 1. Funktionsumfang

Durch Nutzung des HTML-SDKs kann dieser Bildbetrachter Inhalte nun kachelfüllend darstellen – was mit der klassischen HTMLBox bisher nicht möglich war. Unterstützt werden sowohl MJPEG-Streams (z. B. von IP-Kameras) als auch statische Bildquellen.

### 2. Voraussetzungen

* IP-Symcon ab Version 7.2

### 3. Installation

* Über den Module Store das 'Bildbetrachter'-Modul installieren.
* Alternativ über das Module Control folgende URL hinzufügen  
`https://github.com/Wilkware/ImageViewer` oder `git://github.com/Wilkware/ImageViewer.git`

### 4. Einrichten der Instanzen in IP-Symcon

* Unter "Instanz hinzufügen" ist das _'Bildbetrachter'_-Modul unter dem Hersteller _'Geräte'_ aufgeführt.
Weitere Informationen zum Hinzufügen von Instanzen in der [Dokumentation der Instanzen](https://www.symcon.de/service/dokumentation/konzepte/instanzen/#Instanz_hinzufügen)

__Konfigurationsseite__:

_Einstellungsbereich:_

```
📸 Bild ...
```

Name                              | Beschreibung
--------------------------------- | -------------------------------------------
URL der Bild-Ressource            | Quell-URL des darzustellenden Bildes/MJPG

```
✨ Design ...
```

Name                              | Beschreibung
--------------------------------- | -------------------------------------------
Hintergrundfarbe                  | Hintergrundfarbe der Kachel

```
⚙️ Erweiterte Einstellungen  ...
```

Name                              | Beschreibung
--------------------------------- | -------------------------------------------
Dynamische Änderung der Bild-URL zulassen! | Erlaubt das dynamiche Austauschen der URL-Konfiguration (IPS_SetProperty/IPS_ApplyChanges).

### 5. Statusvariablen und Profile

Es werden keine zusätzlichen Statusvariablen/Profile benötigt.

### 6. Visualisierung

Das Modul kann direkt als Link in die TileVisu eingehangen werden.  
Als Kachel wird ein vollflächiges Bild bzw. Motion-Stream dargestellt.

### 7. PHP-Befehlsreferenz

Das Modul stellt keine direkten Funktionsaufrufe zur Verfügung.  
Über IPS_RequestAction mit dem Identifier "SetImageUrl" und der URL als Wert, kann dem Viewer mitgeteilt werden das Bild zu wechseln!

```php
IPS_RequestAction(int $InstanzID, 'SetImageUrl', '<neue bild url>');
```

__Beispiel__:
```php
IPS_RequestAction(12345, 'SetImageUrl', 'https://wilkware.de/wp-content/uploads/2025/02/sommer-smart-home.jpeg');
```

### 8. Versionshistorie

v1.0.20250729

* _NEU_: Initialversion

## Entwickler

Seit nunmehr über 10 Jahren fasziniert mich das Thema Haussteuerung. In den letzten Jahren betätige ich mich auch intensiv in der IP-Symcon Community und steuere dort verschiedenste Skript und Module bei. Ihr findet mich dort unter dem Namen @pitti ;-)

[![GitHub](https://img.shields.io/badge/GitHub-@wilkware-181717.svg?style=for-the-badge&logo=github)](https://wilkware.github.io/)

## Spenden

Die Software ist für die nicht kommerzielle Nutzung kostenlos, über eine Spende bei Gefallen des Moduls würde ich mich freuen.

[![PayPal](https://img.shields.io/badge/PayPal-spenden-00457C.svg?style=for-the-badge&logo=paypal)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8816166)

## Lizenz

Namensnennung - Nicht-kommerziell - Weitergabe unter gleichen Bedingungen 4.0 International

[![Licence](https://img.shields.io/badge/License-CC_BY--NC--SA_4.0-EF9421.svg?style=for-the-badge&logo=creativecommons)](https://creativecommons.org/licenses/by-nc-sa/4.0/)
