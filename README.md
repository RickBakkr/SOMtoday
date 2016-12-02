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
// Include the SomToday class
include 'somtoday.php';

// Create an instance of the SOMtoday class
$som = new SOMtoday('123456','MySafePassword',"MySchoolAbbreviation", 'pupil', 'BRIN');
// or
$som = new SOMtoday('123456','MySafePassword',"MySchoolAbbreviation");

//To get your grades:
$som->getGrades();

//To get your schedule:
$som->getSchedule(); // or getSchedule(NUMBER OF DAYS);

//To get your homework:
$som->getHomework(); // or getHomework(NUMBER OF DAYS);

//To set homework done:
$som->changeHomeworkStatus("1234567","1234567", 1);

//To set homework undone:
$som->changeHomeworkStatus("1234567","1234567", 0);
```

Footnote
=========
If you have any questions, don't hesitate to send a mail to rickbakkr@gmail.com
Please only send proper mails. Inappropriate e-mails will be deleted immediately.
