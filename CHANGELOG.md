# Kongresse und Hauptausschüsse Changelog

## Version 2.0.0 (2026-07-30)

* Fix: Unknown insert tag flag "PåIB Ú" in {{file::…}} -> die binäre Datei-UUID aus `file_broschuere` und `file_protokoll` wurde roh in einen Insert-Tag geschrieben. Enthielten die Rohbytes ein `|`, deutete Contao den Rest als Insert-Tag-Flag; der Tag blieb unersetzt im `href` stehen und erzeugte bei jedem Seitenaufruf einen Logeintrag und einen 404. Die UUID wird jetzt über `StringUtil::binToUuid()` und `FilesModel` aufgelöst
* Fix: Bei genau einem Datensatz gab das Frontend-Modul nichts aus (`numRows > 1` statt `> 0`), ohne Datensätze lief es in eine undefinierte Variable
* Fix: Die Veranstaltungsarten aus den Moduleinstellungen wurden ungeprüft in den SQL-String gesetzt, jetzt als gebundene Parameter
* Fix: Zeilenwechsel `odd`/`even` im Template war wirkungslos, ab der zweiten Zeile stand immer `even`
* Fix: Bei gleichem Von- und Bis-Datum wurde " - 01.03.2020" ausgegeben
* Fix: Leeres Link-Feld erzeugte `<a href="">` um Jahr und Ort
* Fix: `newWindow` bei den Zusatzlinks wurde nicht ausgewertet
* Fix: Die Bezeichnung der Veranstaltungsart (`typTitle`) blieb leer, weil die Sprachdatei nicht geladen wurde
* Fix: Feld `align` und `space` aus der Modulpalette entfernt, die es seit Contao 4 nicht mehr gibt
* Change: Kompatibilität zu Contao 4.13 und Contao 5, Mindestanforderung PHP 8.1
* Change: `codefog/contao-haste` wird nicht mehr benötigt, der Toggler ist jetzt der Contao-eigene
* Change: `dataContainer` als vollqualifizierter Klassenname, `TL_MODE` durch den Scope-Matcher ersetzt, `unserialize()` durch `StringUtil::deserialize()`, Insert-Tags über den Service `contao.insert_tag.parser`
* Change: Ausgabe von Jahr, Ort, Typ und Bemerkung wird maskiert
* Change: Das Frontend-Modul gibt nur noch veröffentlichte Veranstaltungen aus. Eine mitgelieferte Migration setzt beim Update alle vorhandenen Datensätze einmalig auf veröffentlicht, damit nichts unbeabsichtigt verschwindet
* Add: Englische Sprachdateien als Rückfalllösung für Installationen, die nicht auf Deutsch laufen
* Add: Templatevariable `online` sowie CSS-Klasse `online` für Online-Veranstaltungen
* Add: Felder `headline` und `customTpl` in den Moduleinstellungen
* Add: Unit-Tests für die Datumszusammenfassung, README erweitert

## Version 1.2.2 (2026-07-29)

* Fix: Warning: Undefined array key "deleteConfirm" bei contao:migrate -> Lesezugriffe auf $GLOBALS['TL_LANG'] in den DCA-Dateien mit `?? null` bzw. `?? array()` abgesichert, da der DcaLoader die Sprachdateien noch nicht geladen hat

## Version 1.2.1 (2025-09-12)

* Fix: Warning: Undefined array key \"\" at src/Modules/Kongresse.php:130
* Fix: Warning: Undefined variable $class at src/Resources/contao/templates/mod_kongresse.html5:18
* Fix: Unknown insert tag flag "PåIB Ú" in {{file::„¯€¿æ’|PåIB Ú}} 
* Add: tl_kongresse.online -> Checkbox Online-Veranstaltung
* Change: Ticket "Kongresse & Ausschüsse: Nach Datum sortieren" (ToDo Datensatzliste: Sortierung nach Jahr und Datum)

## Version 1.2.0 (2024-04-18)

* Add: codefog/contao-haste
* Change: Haste-Toggler statt des normalen Togglers
* Add: Kompatibilität PHP 8

## Version 1.1.3 (2023-05-16)

* Add: Ausgabe des Typs in das Template

## Version 1.1.2 (2020-10-01)

* Übersetzung BE-Modul repariert
* Standard jetzt: alle Veranstaltungen im Frontend ausgeben, optional kann auch gefiltert werden

## Version 1.1.1 (2020-09-29)

* Fix: application-Array wurde überschrieben
* Backend: Nach Jahr abwärts sortieren
* Frontend: Icons größer - 24 statt 16
* Frontend: title-Attribut bei den Icons ergänzt
* FE-Modul: Übersetzung in Modulauswahl fehlte

## Version 1.1.0 (2020-09-29)

* Add: TODO.md
* Datensatz bearbeiten: Kongressdatum von-bis statt nur von
* Add: Veranstaltungstyp Kommissionstagung
* Add: Links optional im neuen Fenster öffnen
* Datensatz bearbeiten: MCW-Ansicht korrigieren
* Add: Icons für Broschüre und Protokoll
* Template-Ausgabe: CSS-Klassen bei den Tabellenspalten
* Template: Ort/Jahr verlinken mit Infoseite, dafür Infoseite bei den Links entfernen

## Version 1.0.0 (2020-09-24)

* DCA ausgebaut

## Version 0.0.1 (2020-09-23)

* Initiale Version
