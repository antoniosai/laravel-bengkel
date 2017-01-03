<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LibraryController extends Controller
{

  public static function waktuIndonesia($date){
    $bulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

    $tahun = substr($date, 0, 4);
    $bulan = substr($date, 5, 2);
    $tgl   = substr($date, 8, 2);
    $detik = substr($date, 11, 5);
    $result = $tgl . " " . $bulanIndo[(int)$bulan-1]. " ". $tahun;
    return($result);
  }

  public static function waktuIndonesiaWithSecond($date){
    $bulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

    $tahun = substr($date, 0, 4);
    $bulan = substr($date, 5, 2);
    $tgl   = substr($date, 8, 2);
    $detik = substr($date, 11, 5);
    $result = $tgl . " " . $bulanIndo[(int)$bulan-1]. " ". $tahun . " " . $detik ." WIB ";
    return($result);
  }

  public static function timestampsToHour($date)
  {
    $detik = substr($date, 11, 5);

    return $detik;
  }

  public static function timeStampToDate($timestamp)
  {
    return substr($timestamp, 0, 10);
  }


  public static function stringToMonth($month)
  {
  	$bulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

    return $bulan = substr($date, 5, 2);
  }

  public static function generateBulanIndo()
  {
  	$bulanIndo = [
  		1 => "Januari",
  		2 => "Februari",
  		3 => "Maret",
  		4 => "April",
  		5 => "Mei",
  		6 => "Juni",
  		7 => "Juli",
  		8 => "Agustus",
  		9 => "September",
  		10 => "Oktober",
  		11 => "November",
  		12 => "Desember"
  	];

  	return $bulanIndo;
  }

  public static function generateTahunIndo()
  {
  	$startYear = 2015;
    $thisYear = date('Y');

  	$stringYear = [];

  	for ($i = $startYear; $i <= $thisYear ; $i++) { 
  		array_push($stringYear, $i);
  	}
  
  	return $stringYear;

  }

}
