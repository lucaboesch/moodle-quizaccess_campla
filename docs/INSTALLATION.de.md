> **Important**
> Diese Anleitung ist speziell an die folgenden Rollen gerichtet: **Administratoren**

# CAMPLA Moodle Plugin

Das Learning Management System (LMS) Moodle kann für Prüfungen zusammen mit CAMPLA eingesetzt werden.  
Damit die Verwaltung der Prüfungen vereinfacht werden kann, wurden von der Berner Fachhochschule BFH ein Plugin für CAMPLA entwickelt.  
Dieses Plugin erlaubt es, für Moodle Quizes dazugehörige CAMPLA Prüfungen automatisiert zu erstellen.  
Dies vereinfacht die Erstellung von CAMPLA Prüfungen im Zusammenspiel mit Moodle.

## Plugin installieren

In der folgenden Anleitung wird beschrieben, wie das CAMPLA Moodle Plugin installiert und konfiguriert wird.

<img src="images/campla_moodle_plugin_001.png" width="500" alt="Bild 1">

> **Beschreibung**
> - https://github.com/lucaboesch/moodle-quizaccess_campla herunterladen
> - Die https://docs.moodle.org/en/Installing_plugins#Installing_via_uploaded_ZIP_file wird im verlinkten Artikel beschrieben

## Plugin konfigurieren

Damit Moodle via CAMPLA Schnittstelle Prüfungen erstellen kann, muss ein Zugang für Moodle generiert werden.

<img src="images/campla_moodle_plugin_002.png" width="500" alt="Bild 2">

> **Beschreibung**
> - Unter dem Menüpunkt **Organisation** im Tab **Applikationen** können Zugänge für Applikationen erfasst werden
> - Mit einem Klick auf das **+** Symbol Erfassungsdialog öffnen

<img src="images/campla_moodle_plugin_003.png" width="500" alt="Bild 3">

> **Beschreibung**
> - **Name**: Name der Applikationen
> - **Ablaufdatum**: Zeitpunkt, an welchem der Zugang erneuert werden muss. Zugang muss spätestens nach drei Jahren erneuert werden
> - **Departement**: Moodle erstellt automatisch neue Module. Diese werden dem konfigurierten **Departement** zugewiesen
> - **Prüfungsvorlage**: Zu verwendende **Prüfungsvorlage** für die Erstellung von Prüfungen

<img src="images/campla_moodle_plugin_004.png" width="500" alt="Bild 4">

> **Beschreibung**
> - **ACHTUNG**: Das **Secret** wird nur einmalig angezeigt! Speichern Sie dieses an einem sicheren Ort ab.
> - **Applikation ID**: Identifiziert die Applikation
> - **RESTful API URL**: URL zur RESTful API von CAMPLA

> **Important**
> - Folgende Informationen werden für die Plugin Konfiguration benötigt: **Applikation ID**, **Secret** und **RESTful API URL**
> - Das Secret läuft spätestens nach drei Jahren ab und muss möglichst vor Ablauf erneuert werden

<img src="images/campla_moodle_plugin_005.png" width="500" alt="Bild 5">

> **Beschreibung**
> - Navigieren Sie zur Moodle Plattform, auf welcher Sie das CAMPLA Moodle Plugin installiert haben
> - Sie benötigen **Administratoren**-Berechtigungen
> - Im Tab **Plugins** sollten Sie im Abschnitt **Aktivitäten** im Unterbereich **Test** das Plugin mit dem Namen **CAMPLA exam configuration** finden
> - **CAMPLA REST API URL** → **RESTful API URL**
> - **CAMPLA Application secret** → **Secret**
> - **CAMPLA Application ID** → **Applikation ID**
> - **CAMPLA default security level**: Absicherungstool, welches beim Erstellen einer neuen Prüfung vorgeschlagen werden soll.  
    >   Hier kann zwischen **SafeExamBrowser** oder **Lernstick** gewählt werden
