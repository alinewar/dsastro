DS Astrology
===========

Astrology tools for Drupal.

Introduction
------------
 
The dates that signs change fluctuate a few days each year. This tool
allows the user to verify their sun sign. It addition, it shows compatibility 
and the other planet signs such as Moon, Venus,... The API has calls that
allow programmers to look up the individual elements. These are exposed in the
dsastro.astrology service. See AstrologyInterface.php for API
   
Instructions
------------

Unpack in the *modules* folder (currently in the root of your Drupal 8
installation) and enable in `/admin/modules`. Darby Solutions has a suite 
of tools. This module can act stand alone but it is recommended to put in
DarbySoln/modules in the 'modules' folder of Drupal

Then, visit `/admin/config/development/dsastro` for settings

Last, visit `www.example.com/dsastro/M/D/Y` where:
- *M* is the *month*
- *D* is the *day*
- *Y* is the *year*

There is also a 'Get Astrology' block that you can place anywhere that will
allow you to choose the date.

If you need, there's also a specific *dsastro* permission.

