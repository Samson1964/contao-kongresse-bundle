# Kongresse und Hauptausschüsse

Contao-Erweiterung zur Verwaltung und Ausgabe von Kongressen, Hauptausschüssen
und Kommissionstagungen.

**Frank Hoppe**

## Anforderungen

* PHP 8.1 oder neuer
* Contao 4.13 LTS oder Contao 5

## Installation

```
composer require schachbulle/contao-kongresse-bundle
```

Anschließend im Contao-Manager bzw. über `contao:migrate` die Datenbank
aktualisieren.

### Hinweis zum Update auf 2.0.0

Seit 2.0.0 gibt das Frontend-Modul nur noch veröffentlichte Veranstaltungen
aus. Damit beim Update nichts verschwindet, setzt eine mitgelieferte Migration
alle vorhandenen Datensätze einmalig auf *veröffentlicht*. Sie läuft
automatisch beim ersten `contao:migrate` und danach nicht wieder – später
zurückgezogene Veranstaltungen bleiben also zurückgezogen.

## Backend

Unter *Inhalte → Kongresse & Ausschüsse* werden die Veranstaltungen gepflegt.
Je Veranstaltung lassen sich Art, Jahr, Ort, Zeitraum, eine Bemerkung sowie
Kongressbuch, Protokoll, eine Veranstaltungsseite und beliebig viele
Zusatzlinks hinterlegen. Über das Auge-Symbol in der Liste wird ein Datensatz
veröffentlicht oder zurückgezogen.

## Frontend-Modul

Das Modul *Kongresse & Ausschüsse* gibt die Datensätze als Tabelle aus. In den
Moduleinstellungen lässt sich der Jahresbereich eingrenzen; über
*Veranstaltungen wählen* kann zusätzlich auf einzelne Arten eingeschränkt
werden. Ohne Angaben werden alle veröffentlichten Veranstaltungen ausgegeben,
absteigend nach Jahr und Anfangsdatum.

## Verfügbare Templatevariablen

Das Template `mod_kongresse.html5` erhält das Array `$this->records` mit einem
Eintrag je Veranstaltung und folgenden Feldern:

| Feld         | Inhalt |
|--------------|--------|
| `jahr`       | Jahr der Tagung, bei hinterlegter Veranstaltungsseite als Link (enthält HTML) |
| `typ`        | Kurzform der Art: `oK` = Ordentlicher Kongress, `aK` = Außerordentlicher Kongress, `Ha` = Hauptausschuss, `Ko` = Kommissionstagung |
| `typTitle`   | Ausgeschriebene Bezeichnung zu `typ` |
| `ort`        | Ort der Tagung, bei hinterlegter Veranstaltungsseite als Link (enthält HTML) |
| `datum`      | Datum bzw. Zeitraum, zusammengefasst (etwa `30.03. - 02.04.2020`) |
| `info`       | Bemerkung zur Tagung, Rohwert |
| `online`     | `true`, wenn die Veranstaltung als Online-Veranstaltung gekennzeichnet ist |
| `broschuere` | Fertiger Link zum Kongressbuch, leer wenn keine Datei hinterlegt ist (enthält HTML) |
| `protokoll`  | Fertiger Link zum Protokoll, leer wenn keine Datei hinterlegt ist (enthält HTML) |
| `links`      | Die Zusatzlinks, durch senkrechte Striche getrennt (enthält HTML) |

Die als *enthält HTML* gekennzeichneten Felder sind bereits maskiert und dürfen
im Template ohne weitere Behandlung ausgegeben werden. `typ`, `typTitle` und
`info` sind Rohwerte und werden im mitgelieferten Template mit
`Contao\StringUtil::specialchars()` maskiert.

## CSS-Klassen im Standardtemplate

Jede Tabellenzeile erhält `odd` bzw. `even`, dazu `typ_oK`, `typ_aK`, `typ_Ha`
oder `typ_Ko` und bei Online-Veranstaltungen zusätzlich `online`. Die Spalten
tragen die Klassen `jahr`, `typ`, `ort`, `datum`, `info`, `buch`, `protokoll`
und `links`.

## Sprachen

Die Erweiterung bringt deutsche und englische Sprachdateien mit. In einer
Installation mit einer anderen Sprache greifen die englischen Beschriftungen.
