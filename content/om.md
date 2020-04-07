---
views:
    kursrepo:
        region: sidebar-right
        template: anax/v2/block/default
        data:
            meta:
                type: single
                route: block/om-kursrepo

    redovisa:
        region: sidebar-right
        template: anax/v2/block/default
        data:
            meta:
                type: single
                route: block/om-redovisa
---
Om
=========================

[FIGURE src=image/blommor.jpg?width=380&sharpen class="left" caption="Bild på blommor i en kruka."]

Objektorientering handlar om samla ihop och gruppera varibler och funktioner på olika sätt. Ett objekt har all sin förmåga samlad i metoder och egenskaper (eller medlemsvariabler) som samlas i en klass. Allt som kan göras med objektet exponeras via metoder. All information som objektet behöver spara finns i dess medlemsvariabler.

En blomma kan vara ett objekt av en klass med egenskaper som flerårig och färg. Denna klass har egenskaper och metoder som ärvs av dess barn, som till exempel kan vara pense och primula. Klassen pense kan ärva klassen blomma och har egna egenskaper som namn och odlare. Klassen primula kan också ärva blomma och har egna specifika egenskaper.

Kursen oophp är en förkortning av Objektorienterade webbteknologier och handlar om Objektorienterade programmeringstekniker i språket PHP. Klassiska objektorienterade konstruktioner hanteras tillsammans med objektorienterad programmering i webbaserat ramverk tillsammans med databaser samt enhetstestning.

Målet med kursen är att studenterna självständigt ska kunna programmera objektorienterad PHP i ett webbaserat ramverk där databaser spelar en stor roll. Webbapplikationerna utvecklas i ett ramverk där tekniker såsom webbserver (Apache), PHP, HTML, CSS, och SQL integreras tillsammans med ett webbaserat ramverk. Studenterna får lära sig använda av verktyg och tekniker som lämpar sig för utveckling av webbapplikationer, tex UNIX/Linux, installation på extern webbserver, ssh, ftp/sftp, databasklienter såsom PHPMyAdmin, MySQL Workbench och kommandoklienter.
