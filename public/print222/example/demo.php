<?php


ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); 


/**
 * This is a demo script for the functions of the PHP ESC/POS print driver,
 * Escpos.php.
 *
 * Most printers implement only a subset of the functionality of the driver, so
 * will not render this output correctly in all cases.
 *
 * @author Michael Billington <michael.billington@gmail.com>
 */
require __DIR__ . '/../vendor/autoload.php';
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
$connector = new NetworkPrintConnector("178.210.129.98", 9100);

$printer = new Printer($connector);
try {
   
    $printer -> text("ааа\n");
    $printer -> cut();
     
} finally {
    $printer -> close();
}