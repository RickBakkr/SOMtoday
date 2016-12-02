# SOMtoday
PHP wrapper for SOMtoday api

Installation
============
1. Download the file
2. Put it in the same folder as the script you would like to use it in
3. ``include "Somtoday.php"``
4. Make a neat gun for the people who are still alive!

Examples
===========
```PHP
include 'somtoday.php';

$som = new SOMtodayUser('123456','MySafePassword',"MySchoolAbbreviation", 'pupil', 'BRIN');
or
$som = new SOMtodayUser('123456','MySafePassword',"MySchoolAbbreviation");

To set homework done:
$som->changeHomeworkStatus("1234567","1234567", 1);

To set homework undone:
$som->changeHomeworkStatus("1234567","1234567", 0);
```

Footnote
=========
questions? rickbakkr@gmail.com
Only send proper mails. Inappropriate e-mails will be deleted immediately.
