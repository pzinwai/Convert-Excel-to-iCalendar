<?php
require_once 'vendor/autoload.php';

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

$tempDir = "temp/";
$tempFile = $tempDir . basename($_FILES["fileToUpload"]["name"]);

$MIMEs = array('application/vnd.ms-excel','text/xls','text/xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

if (isset($_POST["submit"])) {
  move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $tempFile);

  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $fileMIME = finfo_file($finfo, $tempFile);
  
  if (in_array($fileMIME, $MIMEs)) {
    $reader = ReaderEntityFactory::createReaderFromFile($tempFile);
    $reader->open($tempFile);

    $successCalendarCount = 0;

    foreach ($reader->getSheetIterator() as $sheet) {
      $rowCount = 0;
      foreach ($sheet->getRowIterator() as $row) {
          $cells = $row->getCells();

          if ($rowCount > 0) {
            $calendarDate = null;
            $calendarStartTime = null;
            $calendarEndTime = null;
            $calendarEventName = null;

            // Calendar date
            if (substr($cells[0], 0, strpos($cells[0], ' '))) {
              $calendarDate = substr($cells[0], 0, strpos($cells[0], ' '));
              $calendarDate = str_replace('/', '-', $calendarDate);
              $calendarDate = date('Y-m-d', strtotime($calendarDate));
            }
            // Calendar time
            if (substr($cells[1], 0, strpos($cells[1], ' - '))) {
              $calendarStartTime = trim(substr($cells[1], 0, strpos($cells[1], ' - ')));
              $calendarEndTime = trim(substr($cells[1], strpos($cells[1], ' - ') + 3, strlen($cells[1])-1));
            }
            // Calendar event name
            $calendarEventName = $cells[2];

            if ($calendarDate!==null && $calendarStartTime!==null && $calendarEndTime!==null && $calendarEventName!==null) {
              $event = Calendar::create($calendarEventName)
              ->event(Event::create($calendarEventName)
                  ->startsAt(new DateTime($calendarDate." ".$calendarStartTime))
                  ->endsAt(new DateTime($calendarDate." ".$calendarEndTime))
              )->withoutTimezone()
              ->get();

              file_put_contents("calendars/".$calendarEventName.".ics", $event);

              $successCalendarCount++;
            }
          }
          $rowCount++;
      }
    }

    $reader->close();
    // delete file
    unlink($tempFile);

    header("Location: index.php?success=".$successCalendarCount);
    die();  
  }
}
