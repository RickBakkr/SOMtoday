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
include 'Somtoday.php';

$som = new SOMtodayUser("henk","mypassword","myschool","dembrin");

$som->changeHomeworkStatus("6374673","7364736", true);
```

Footnote
=========
questions? rickbakkr@gmail.com
Only send proper mails. Inappropriate e-mails will be deleted immediately.
