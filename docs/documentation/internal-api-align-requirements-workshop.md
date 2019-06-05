# "Requirements Acquisition Workshop" Internal API Alignment

- Date: 27.05.2019
- Start of Meeting: 13:00
- End of Meeting: 15:00
- Participants: 
Alexander Killing, Maximilian Becker, Michael Jansen, 
Björn Heyser, Guido Vollbach, Niels Theen, Ralph Dittrich, 
Stefan Schneider, Richard Klees, Fabian Schmid, Martin Studer, 
Cüneyt Sandal

> Raw notes from the meeting below.

### Community feedback distilled:
- Worries that a large scale, landslide development may be enforced that could overwhelm maintainers with larger components.
- Wish for transparency in the process.
- Worries that a full-scale API-framework may lead to a second "ILIAS in ILIAS" *or* that a too "low key" development may miss important pain points or benefits of a grander schemed framework.
- Worries that the domain level API may cover "too much ILIAS process" (e.g. "Making a course membership being impossible *without* sending an email")
- The documentation must manage expectations properly.
- Proposal of a full scale architectural model was shown, it would lead to a general transformation of the whole codebase to an API.
- Influences and practices from DDD may help us transit from a CRUD-App to a modernized core in general where the aligned API may lead a development.
- Policies, Security as centralized concepts are considered important by many.
- The definition of processes as consequences of high level actions should be transparent.
- Goals should be to get rid of wrappers and instead use interfaces.
- Use of the API should be easily testable.
- Integration of the newly aligned API standard should be incrementally.

---

### Protocol in fulltext:

#### "Requirements Acquisition Workshop" Internal API Alignment

*Date:*  27.05.2019

*Start of Meeting:* 13:00

*End of Meeting:* 15:00

*Participants:*
 - Alexander Killing, 
 - Maximilian Becker, 
 - Michael Jansen, 
 - Björn Heyser, 
 - Guido Vollbach, 
 - Niels Theen, 
 - Ralph Dittrich, 
 - Stefan Schneider, 
 - Richard Klees, 
 - Fabian Schmid, 
 - Martin Studer, 
 - Cüneyt Sandal


#### Protocol:

- Max stellt die Idee "API Alignment (fka. Service Disco)" vor. Fragen gab es keine

##### Ideen/Input/Anforderungserhebung
* Björn Heyser: 2 Dimensionen im Blick / Hat "Ängste", dass alles in einem Rutsch zur Vergügung gestellt werden muss / Wunsch von Konkretisierung, Beispiele

* Alex: Erster Workshop zur Erhebung der Anforderungen / Was sind die aktuellen Pain-Points der Entwickler? Aktuell denken wir noch nicht in Lösungen.

* Fabian Schmid: Das "Ziel" passt / Gefahren: Parallelsystem, ggf. eine Herausforderung bei neuen Entwicklungen / Mehrere Varianten vorstellbar (Definition, technische Lösungen Command-Receiver-Pattern wie Home-Automation), aber es sei noch alles offen
    * Max: Was ist genau mit Parallelsystem gement?
    * Fabian: Bestimmte Aktionen sind gekapselt nach außen, andere aber nicht / ggf. vergisst man, es auf die "neue Weise" zu machen ...
    * Max: Versteht als API ein Interface, mit dem auf die Applikation (oder Teile derer) zugegriffen werden kann / sieht eine "Facade" im Mittelpunkt
    * Fabian: Hat bei Konventionen mehr Bedenken als bei technischer Lösung / Sieht bei der "Facade" ähnliche Probleme wie jene, die wir aktuell schon haben

* Stefan Schneider: Sei aus historischen Gründen hier / Ursprung war die "Zusammenfassung der Endpoints" / Wo ist Unterschied zwischen "Service Disco" und "API Alignment"? Frage der Granularität: Logik oder nur Endunkte definieren?
    * Max: Der Begriff "Discovery" kann gestrichen werden / Auch damals haben wir schon über APIs gesprochn / Es ging bei der Verwertbarkeit darum, dass dies u.a. auch für Maschinen verwert bar ist / Heute: Entwickler werden "in Mission" aufgenommen / API sei Angebot, die angenommen werden kann für Maschinen UND(!) Entwickler, die auf einen zentralen Punkt von Komponenten zugreifen / "Facade" wird sich semantisch "nach außen" nicht verändern
    * Stefan: Es geht also auch um eine Verlässlichkeit für "intern"?
    * Max: Ja!

* Richard: Es gebe an ganz vielen Stellen den eigentlichen Layer nicht. / Es gehe nicht nur darum diese Services zu finden, sondern auch diesen fehlenden Layer zu schaffen.
    * Max: Mehrfachimplementierungen loslegen (loswerden)

* Stefan: In bestimmten Kontexten granularere Zugänge zu schaffen werde vermutlich dennoch notwendig sein

* Ralph Dittrich: Verkettung von Prozessen: Kurs buchen -> E-Mail versenden / nicht zu viel verketten, eher die Möglichkeiten geben, einzelne Commands selber zu delegieren, Message-Chaining / Man könne nicht alle Fälle abdecken, es sollten nicht zu viele Prozesse an einer Funktion hängen
    * Max: Es gebe kein "zu viel" oder "zu wenig" an der Konsequenz eines API-Aufrufs / Sieht im Beispiel "Kursbuchung" die Domain-Level-Aktionen von "RBAC" und dem "Schreibtisch".
    * Ralph: Besseres Beispiel: Workflow Engine -> 1 User soll 1000 Kursen zugewiesen werden, aber ohne E-Mail-Versand, unter Nutzung der API

*Alex: Dokumentation! Die Erwartung solle dokumentiert sein. / "Was ist ein sinnvolles Paket"?

* Martin Studer: PR https://github.com/studer-raimann/ILIAS/tree/feature/trunk/ilias_with_cqrs_components/vendor/ilias/IliasComponentCourse / CQRS Pattern
    - Vision: In den verschiedenen ILIAS-Komponenten werden überall andere Patterns angewendet -> Verständlichkeit / Aktualisierung einzelner Module innerhalb des Jahres, ohne auf Releases zu warten -> Mirco-Services
    - Lösungsansatz: DDD (CQRS-Ansatz)
    - Query- und Command-Seite
    - Kreis-/Zwiebel-Modell:
        1. Entities sind im Zentrum (z.B. der ILIAS-Kurs)
            - Anfrage: Gib mir alle Mitglieder am Kurs
            - root-Entities sind um einiges mächtiger als es aktuell die ILIAS-Objekte sind
            - Value-Objekte
        2. Use-Cases
            - removeMemberFromCourse / addMemberToCourse
            - Keine klassichen Methoden mit Gettern/Settern, sondern eher: Was sind die üblichen Aktionen?
        3. Schreib-Seite:
            - Commands und Command-Handler, 1:1-Beziehung
            - Events: 1:N-Events könnten unübersichtlich sein / Gibt es nur Objekte, die in einer Event-Map stehen (Array oder Definitionsdatei)?
            - Aufruf eines Message-Bus, der Bus hat eine Middleware (Use-Case: z.B. Logging jeder Aktion), die man einfach anhängen kann
        4. Lese-Seite:
            - Queries für Ansichten
    - Max: Glaube nicht, dass es gerade UNSER Projekt ist / Der Architektur-Vorschlag von Martin sei schon weiter / Sieht es als eine Art "Weg-Migration" / Wertvoller Input, sieht aber ggf. eine Herausforderung, dies in den Projekt-Scope zu bringen
    - Björn: Findet es nicht "groß" / Man sollte es versuchen
    - Richard: Aus dem DDD kamen viele Impulse / Aktuell CRUD / DDD sei ganz anders / Wie können wir die ersten Schritte in eine andere Richtung machen? / DDD sei ein Übertheme des Projekt-Scopes
    - Ralph: Frage Martin nach Aufwead für das gezeigte Beispiel-Szenario

- Niels: Findet Martins Ansatz gut, alles was Richtung "DDD" geht sei gut / Sei ein wichtiges Thema, könne das in jeglicher Form unterstützen

- Ralph: Wir sind auf dem richtigen Weg, fein granularere API

- Michael: Merkt sehr heterogene Herangehensweise an, Wichtige Aspekte seien Policies, Security, zentraler Ansatz, auch mit aktuellem Bezug zu Security-Issues -> Policy Enforcement, wie spielt die Refinery rein

- Richard: Leidet! ;-) "Was muss ich tun, um jenanden zum Mitglied eines Kurses zu machen?" Solche Fragen müssen einfach zu beantworten sein! / Brauchen den Layer auch für die Security

- Timon Amstutz: Schreibt aktuell viele Wrapper / Definition von Interfaces / Man will schnell sehen was man mit einer Komponente machen kann / Will einfach testen könnenn / Empfiehlt einen Weg, mit kleineren Änderungen zu starten, um dann iterativ Release zu Release fortzufahren

- Alex Killing: Dokumentation, Anwender-bezogene Sprache / Security / Schließt sich Richard an: Das machen, was technisch möglich ist / Wiederverwendbarkeit von Unterkomponenten wie bei den Memberships angesprochen 

- Guido Vollbach: Keine "harte" Meinung zu dem Thema

- Stefan Schneider: Client-seitiges Rendering z.B. für die App / Layer, über den man eine Beschreibung erhält, die auch Client-seitig zu lesen ist / Weist auf GraphQL hin
