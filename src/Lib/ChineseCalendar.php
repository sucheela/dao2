<?php
namespace App\Lib;
	/**
	 * Calculate Chinese date based on Gregorian date. 
	 * Call getStem() or getBranch() to get heavenly stems and branches
	 * associated with the date given at the constructor.
	 */
class ChineseCalendar {

	/**
	 * @const
	 */
	const FORMAT_NUMBER = 1;
	const FORMAT_STRING = 2;

	const TYPE_YEAR  = 1;
	const TYPE_MONTH = 2;
	const TYPE_DATE  = 3;
	const TYPE_HOUR  = 4;

   public static $DaysInGregorianMonth = 
		 array(31,28,31,30,31,30,31,31,30,31,30,31);
   public static $MonthNames =
		 array("January","February","March","April","May","June",
					 "July","August","September","October","November","December");
   public static $StemNames =
		 array("Yang Wood","Yin Wood","Yang Fire","Yin Fire","Yang Earth","Yin Earth",
					 "Yang Metal","Yin Metal","Yang Water","Yin Water");
   public static $BranchNames =
		 array("Rat","Ox","Tiger","Rabbit","Dragon","Snake",
					 "Horse","Ram","Monkey","Rooster","Dog","Boar");
	 private static $ChineseMonths = 
		 array( 
   //Chinese month map, 2 bytes per year, from 1900 to 2100, 402 bytes.
   //The first 4 bits represents the leap month of the year.
   //The rest 12 bits are flags indicate if the corresponding month
   //is a 29-day month. 2 bytes are stored in low-high order.
   0x00,0x04,0xad,0x08,0x5a,0x01,0xd5,0x54,0xb4,0x09,0x64,0x05,0x59,0x45,
   0x95,0x0a,0xa6,0x04,0x55,0x24,0xad,0x08,0x5a,0x62,0xda,0x04,0xb4,0x05,
   0xb4,0x55,0x52,0x0d,0x94,0x0a,0x4a,0x2a,0x56,0x02,0x6d,0x71,0x6d,0x01,
   0xda,0x02,0xd2,0x52,0xa9,0x05,0x49,0x0d,0x2a,0x45,0x2b,0x09,0x56,0x01,
   0xb5,0x20,0x6d,0x01,0x59,0x69,0xd4,0x0a,0xa8,0x05,0xa9,0x56,0xa5,0x04,
   0x2b,0x09,0x9e,0x38,0xb6,0x08,0xec,0x74,0x6c,0x05,0xd4,0x0a,0xe4,0x6a,
   0x52,0x05,0x95,0x0a,0x5a,0x42,0x5b,0x04,0xb6,0x04,0xb4,0x22,0x6a,0x05,
   0x52,0x75,0xc9,0x0a,0x52,0x05,0x35,0x55,0x4d,0x0a,0x5a,0x02,0x5d,0x31,
   0xb5,0x02,0x6a,0x8a,0x68,0x05,0xa9,0x0a,0x8a,0x6a,0x2a,0x05,0x2d,0x09,
   0xaa,0x48,0x5a,0x01,0xb5,0x09,0xb0,0x39,0x64,0x05,0x25,0x75,0x95,0x0a,
   0x96,0x04,0x4d,0x54,0xad,0x04,0xda,0x04,0xd4,0x44,0xb4,0x05,0x54,0x85,
   0x52,0x0d,0x92,0x0a,0x56,0x6a,0x56,0x02,0x6d,0x02,0x6a,0x41,0xda,0x02,
   0xb2,0xa1,0xa9,0x05,0x49,0x0d,0x0a,0x6d,0x2a,0x09,0x56,0x01,0xad,0x50,
   0x6d,0x01,0xd9,0x02,0xd1,0x3a,0xa8,0x05,0x29,0x85,0xa5,0x0c,0x2a,0x09,
   0x96,0x54,0xb6,0x08,0x6c,0x09,0x64,0x45,0xd4,0x0a,0xa4,0x05,0x51,0x25,
   0x95,0x0a,0x2a,0x72,0x5b,0x04,0xb6,0x04,0xac,0x52,0x6a,0x05,0xd2,0x0a,
   0xa2,0x4a,0x4a,0x05,0x55,0x94,0x2d,0x0a,0x5a,0x02,0x75,0x61,0xb5,0x02,
   0x6a,0x03,0x61,0x45,0xa9,0x0a,0x4a,0x05,0x25,0x25,0x2d,0x09,0x9a,0x68,
   0xda,0x08,0xb4,0x09,0xa8,0x59,0x54,0x03,0xa5,0x0a,0x91,0x3a,0x96,0x04,
   0xad,0xb0,0xad,0x04,0xda,0x04,0xf4,0x62,0xb4,0x05,0x54,0x0b,0x44,0x5d,
   0x52,0x0a,0x95,0x04,0x55,0x22,0x6d,0x02,0x5a,0x71,0xda,0x02,0xaa,0x05,
   0xb2,0x55,0x49,0x0b,0x4a,0x0a,0x2d,0x39,0x36,0x01,0x6d,0x80,0x6d,0x01,
   0xd9,0x02,0xe9,0x6a,0xa8,0x05,0x29,0x0b,0x9a,0x4c,0xaa,0x08,0xb6,0x08,
   0xb4,0x38,0x6c,0x09,0x54,0x75,0xd4,0x0a,0xa4,0x05,0x45,0x55,0x95,0x0a,
   0x9a,0x04,0x55,0x44,0xb5,0x04,0x6a,0x82,0x6a,0x05,0xd2,0x0a,0x92,0x6a,
   0x4a,0x05,0x55,0x0a,0x2a,0x4a,0x5a,0x02,0xb5,0x02,0xb2,0x31,0x69,0x03,
   0x31,0x73,0xa9,0x0a,0x4a,0x05,0x2d,0x55,0x2d,0x09,0x5a,0x01,0xd5,0x48,
   0xb4,0x09,0x68,0x89,0x54,0x0b,0xa4,0x0a,0xa5,0x6a,0x95,0x04,0xad,0x08,
   0x6a,0x44,0xda,0x04,0x74,0x05,0xb0,0x25,0x54,0x03
						);
   // Base date: 01-Jan-1901, 4598/11/11 in Chinese calendar
   const BaseYear = 1901;
   const BaseMonth = 1;
   const BaseDate = 1;
   const BaseIndex = 0;
   const BaseChineseYear = 4597;
   const BaseChineseMonth = 11;
   const BaseChineseDate = 11;

   private static $BigLeapMonthYears = 
		 array(
      // The leap months in the following years have 30 days
      6, 14, 19, 25, 33, 36, 38, 41, 44, 52, 
			55, 79,117,136,147,150,155,158,185,193);

   private static $SectionalTermMap = 
		 array(
					 array(7,6,6,6,6,6,6,6,6,5,6,6,6,5,5,6,6,5,5,5,5,5,5,5,5,4,5,5),   // Jan
					 array(5,4,5,5,5,4,4,5,5,4,4,4,4,4,4,4,4,3,4,4,4,3,3,4,4,3,3,3),   // Feb
					 array(6,6,6,7,6,6,6,6,5,6,6,6,5,5,6,6,5,5,5,6,5,5,5,5,4,5,5,5,5), // Mar
					 array(5,5,6,6,5,5,5,6,5,5,5,5,4,5,5,5,4,4,5,5,4,4,4,5,4,4,4,4,5), // Apr
					 array(6,6,6,7,6,6,6,6,5,6,6,6,5,5,6,6,5,5,5,6,5,5,5,5,4,5,5,5,5), // May
					 array(6,6,7,7,6,6,6,7,6,6,6,6,5,6,6,6,5,5,6,6,5,5,5,6,5,5,5,5,4,5,5,5,5),
					 array(7,8,8,8,7,7,8,8,7,7,7,8,7,7,7,7,6,7,7,7,6,6,7,7,6,6,6,7,7), // Jul
					 array(8,8,8,9,8,8,8,8,7,8,8,8,7,7,8,8,7,7,7,8,7,7,7,7,6,7,7,7,6,6,7,7,7),
					 array(8,8,8,9,8,8,8,8,7,8,8,8,7,7,8,8,7,7,7,8,7,7,7,7,6,7,7,7,7), // Sep
					 array(9,9,9,9,8,9,9,9,8,8,9,9,8,8,8,9,8,8,8,8,7,8,8,8,7,7,8,8,8), // Oct
					 array(8,8,8,8,7,8,8,8,7,7,8,8,7,7,7,8,7,7,7,7,6,7,7,7,6,6,7,7,7), // Nov
					 array(7,8,8,8,7,7,8,8,7,7,7,8,7,7,7,7,6,7,7,7,6,6,7,7,6,6,6,7,7)  // Dec
					 ); 

   private static $SectionalTermYear = 
		 array(
					 array(13,49,85,117,149,185,201,250,250), // Jan
					 array(13,45,81,117,149,185,201,250,250), // Feb
					 array(13,48,84,112,148,184,200,201,250), // Mar
					 array(13,45,76,108,140,172,200,201,250), // Apr
					 array(13,44,72,104,132,168,200,201,250), // May
					 array(5 ,33,68,96 ,124,152,188,200,201), // Jun
					 array(29,57,85,120,148,176,200,201,250), // Jul
					 array(13,48,76,104,132,168,196,200,201), // Aug
					 array(25,60,88,120,148,184,200,201,250), // Sep
					 array(16,44,76,108,144,172,200,201,250), // Oct
					 array(28,60,92,124,160,192,200,201,250), // Nov
					 array(17,53,85,124,156,188,200,201,250)  // Dec
					 );

   private static $PrincipleTermMap = 
		 array(
					 array(21,21,21,21,21,20,21,21,21,20,20,21,21,20,20,20,20,20,20,20,20,19,
								 20,20,20,19,19,20),
					 array(20,19,19,20,20,19,19,19,19,19,19,19,19,18,19,19,19,18,18,19,19,18,
								 18,18,18,18,18,18),
					 array(21,21,21,22,21,21,21,21,20,21,21,21,20,20,21,21,20,20,20,21,20,20,
								 20,20,19,20,20,20,20),
					 array(20,21,21,21,20,20,21,21,20,20,20,21,20,20,20,20,19,20,20,20,19,19,
								 20,20,19,19,19,20,20),
					 array(21,22,22,22,21,21,22,22,21,21,21,22,21,21,21,21,20,21,21,21,20,20,
								 21,21,20,20,20,21,21),
					 array(22,22,22,22,21,22,22,22,21,21,22,22,21,21,21,22,21,21,21,21,20,21,
								 21,21,20,20,21,21,21),
					 array(23,23,24,24,23,23,23,24,23,23,23,23,22,23,23,23,22,22,23,23,22,22,
								 22,23,22,22,22,22,23),
					 array(23,24,24,24,23,23,24,24,23,23,23,24,23,23,23,23,22,23,23,23,22,22,
								 23,23,22,22,22,23,23),
					 array(23,24,24,24,23,23,24,24,23,23,23,24,23,23,23,23,22,23,23,23,22,22,
								 23,23,22,22,22,23,23),
					 array(24,24,24,24,23,24,24,24,23,23,24,24,23,23,23,24,23,23,23,23,22,23,
								 23,23,22,22,23,23,23),
					 array(23,23,23,23,22,23,23,23,22,22,23,23,22,22,22,23,22,22,22,22,21,22,
								 22,22,21,21,22,22,22),
					 array(22,22,23,23,22,22,22,23,22,22,22,22,21,22,22,22,21,21,22,22,21,21,
								 21,22,21,21,21,21,22)
					 );

   private static $PrincipleTermYear = 
		 array(
					 array(13,45,81,113,149,185,201),     // Jan
					 array(21,57,93,125,161,193,201),     // Feb
					 array(21,56,88,120,152,188,200,201), // Mar
					 array(21,49,81,116,144,176,200,201), // Apr
					 array(17,49,77,112,140,168,200,201), // May
					 array(28,60,88,116,148,180,200,201), // Jun
					 array(25,53,84,112,144,172,200,201), // Jul
					 array(29,57,89,120,148,180,200,201), // Aug
					 array(17,45,73,108,140,168,200,201), // Sep
					 array(28,60,92,124,160,192,200,201), // Oct
					 array(16,44,80,112,148,180,200,201), // Nov
					 array(17,53,88,120,156,188,200,201)  // Dec
					 );

	 private static $HourBranchMap = array(array(23, 0),
																				 array(1, 2),
																				 array(3, 4),
																				 array(5, 6),
																				 array(7, 8),
																				 array(7, 10),
																				 array(11, 12),
																				 array(13, 14),
																				 array(15, 16),
																				 array(17, 18),
																				 array(19, 20),
																				 array(21, 22));

	 /**
		* @var 
		*/
   private $gregorianYear;
   private $gregorianMonth;
   private $gregorianDate;
   private $gregorianHour;
   private $isGregorianLeap;
   private $dayOfYear;
   private $dayOfWeek; // Sunday is the first day
   private $chineseYear;
   private $chineseMonth; // -n is a leap month
   private $chineseDate;
   private $sectionalTerm;
   private $principleTerm;
	 private $monthStem;
	 private $monthBranch;
	 private $dateStem;
	 private $dateBranch;
	 private $hourStem;
	 private $hourBranch;


	 /**
		* @param Integer $year from 1901 to 2100
		* @param Integer $month from 1 to 12
		* @param Integer $day from 1 to 31
		* @param Integer $hour from 0 to 23
		*/
	 public function __construct($year,
															 $month,
															 $day,
															 $hour=null){
		 $y = is_numeric($year)  ? $year : 1901;
		 $m = is_numeric($month) ? $month : 1;
		 $d = is_numeric($day)   ? $day : 1;
		 $h = is_numeric($hour)  ? $hour : null;

		 $this->setGregorian($y, $m, $d, $h);
		 $this->computeChineseFields();
		 $this->computeSolarTerms();
		 
		 $this->computeMonthStemBranch();
		 $this->computeDateStemBranch();
		 $this->computeHourStemBranch();
		 
	 }

	 public function toString(){
		 print '<pre>';
		 print 'Debugging info: ' . "\n";
		 print 'Gregorian Year : ' . $this->gregorianYear . "\n";
		 print 'Month : ' . $this->gregorianMonth . "\n";
		 print 'Date : ' . $this->gregorianDate . "\n";
		 print 'Chinese Year : ' . $this->chineseYear . "\n";
		 print 'Month : ' . $this->chineseMonth . "\n";
		 print 'Date : ' . $this->chineseDate . "\n";
		 print '</pre>';
	 }

	 /**
		* @param Integer $type See class constant TYPE_*
		* @param Integer $format See class constant FORMAT_*
		* @return String|Integer
		*/
	 public function getStem($type=self::TYPE_YEAR, 
													 $format=self::FORMAT_STRING){
		 switch ($type){
		 case self::TYPE_YEAR:
			 $stem = ($this->chineseYear-1)%10;
			 break;
		 case self::TYPE_MONTH:
			 $stem = $this->monthStem;
			 break;
		 case self::TYPE_DATE:
			 $stem = $this->dateStem;
			 break;
		 case self::TYPE_HOUR:
			 $stem = $this->hourStem;
		 }

		 if ($format == self::FORMAT_NUMBER){
			 return $stem+1;
		 } else {
			 return self::$StemNames[$stem];
		 }
	 }

	 /**
		* @param Integer $type See class constant TYPE_*
		* @param Integer $format See class constant FORMAT_*
		* @return String|Integer
		*/
	 public function getBranch($type=self::TYPE_YEAR,
														 $format=self::FORMAT_STRING){
		 switch ($type){
		 case self::TYPE_YEAR:
			 $branch = ($this->chineseYear-1) % 12;
			 break;
		 case self::TYPE_MONTH:
			 $branch = $this->monthBranch;
			 break;
		 case self::TYPE_DATE:
			 $branch = $this->dateBranch;
			 break;
		 case self::TYPE_HOUR:
			 $branch = $this->hourBranch;
			 break;
		 }

		 if ($format == self::FORMAT_NUMBER){
			 return $branch+1;
		 } else {
			 return self::$BranchNames[$branch];
		 }
	 }
	 
	 private function setGregorian($y, $m, $d, $h) {
		$this->gregorianYear = $y;
		$this->gregorianMonth = $m;
		$this->gregorianDate = $d;
		$this->gregorianHour = $h;
		$this->isGregorianLeap = $this->isGregorianLeapYear($y);
		$this->dayOfYear = $this->dayOfYear($y,$m,$d);
		$this->dayOfWeek = $this->dayOfWeek($y,$m,$d);
		$this->chineseYear = 0;
		$this->chineseMonth = 0;
		$this->chineseDate = 0;
		$this->sectionalTerm = 0;
		$this->principleTerm = 0;
	}
  public function isGregorianLeapYear($year) {
		$isLeap = false;
		if ($year%4==0) $isLeap = true;
		if ($year%100==0) $isLeap = false;
		if ($year%400==0) $isLeap = true;
		return $isLeap;
	}
  public function daysInGregorianMonth($y, $m) {
		$d = self::$DaysInGregorianMonth[$m-1];
		if ($m==2 && $this->isGregorianLeapYear($y)) $d++; // Leap year adjustment
		return $d;      
	}
  public function dayOfYear($y, $m, $d) {
		$c = 0;
		for ($i=1; $i<$m; $i++) { // Number of months passed
			$c = $c + $this->daysInGregorianMonth($y,$i);
		}
		$c = $c + $d;
		return $c;      
  }
  public function dayOfWeek($y, $m, $d) {
    $w = 1; // 01-Jan-0001 is Monday, so base is Sunday
		$y = ($y-1)%400 + 1; // Gregorian calendar cycle is 400 years
		$ly = ($y-1)/4; // Leap years passed
		$ly = $ly - ($y-1)/100; // Adjustment
		$ly = $ly + ($y-1)/400; // Adjustment
		$ry = $y - 1 - $ly; // Regular years passed
		$w = $w + $ry; // Regular year has one extra week day
		$w = $w + 2*$ly; // Leap year has two extra week days
		$w = $w + $this->dayOfYear($y,$m,$d); 
		$w = ($w-1)%7 + 1;
		return $w;
   }
   private function computeChineseFields() {
		 // Gregorian year out of the computation range
		 if ($this->gregorianYear<1901 || $this->gregorianYear>2100) return 1;

		 $startYear = self::BaseYear;
		 $startMonth = self::BaseMonth;
		 $startDate = self::BaseDate;      
		 $this->chineseYear = self::BaseChineseYear; 
		 $this->chineseMonth = self::BaseChineseMonth;
		 $this->chineseDate = self::BaseChineseDate;
		 // Switching to the second base to reduce the calculation process
		 // Second base date: 01-Jan-2000, 4697/11/25 in Chinese calendar
		 if ($this->gregorianYear >= 2000) {
			 $startYear = self::BaseYear + 99;
			 $startMonth = 1;
			 $startDate = 1;
			 $this->chineseYear = self::BaseChineseYear + 99;
			 $this->chineseMonth = 11;
			 $this->chineseDate = 25;
      }
      // Calculating the number of days 
      //    between the start date and the current date
      // The following algorithm only works 
      //    for startMonth = 1 and startDate = 1
      $daysDiff = 0;
      for ($i=$startYear; $i<$this->gregorianYear; $i++) {
				$daysDiff += 365;
				if ($this->isGregorianLeapYear($i)) $daysDiff += 1; // leap year
      }
      for ($i=$startMonth; $i<$this->gregorianMonth; $i++) {
				$daysDiff += $this->daysInGregorianMonth($this->gregorianYear,$i);
      }
      $daysDiff += $this->gregorianDate - $startDate;
      
      // Adding that number of days to the Chinese date
      // Then bring Chinese date into the correct range.
      //    one Chinese month at a time
      $this->chineseDate += $daysDiff;
      $lastDate = $this->daysInChineseMonth($this->chineseYear, 
																						$this->chineseMonth);
      $nextMonth = $this->nextChineseMonth($this->chineseYear, 
																					 $this->chineseMonth);
      while ($this->chineseDate > $lastDate) {
         if (abs($nextMonth) < abs($this->chineseMonth)) $this->chineseYear++;
         $this->chineseMonth = $nextMonth;
         $this->chineseDate -= $lastDate;
         $lastDate = $this->daysInChineseMonth($this->chineseYear, 
																							 $this->chineseMonth);
         $nextMonth = $this->nextChineseMonth($this->chineseYear, 
																							$this->chineseMonth);
      }
      return 0;
   }
   public function daysInChineseMonth($y, $m) {
      // Regular month: m > 0
      // Leap month: m < 0
		 $index = $y - self::BaseChineseYear + self::BaseIndex;
		 $v = 0;
     $l = 0;
     $d = 30; 
		 if (1<=$m && $m<=8) { // normal month
			 $v = self::$ChineseMonths[2*$index];
			 $l = $m - 1;
			 if ( (($v>>$l)&0x01)==1 ) $d = 29;
		 } else if (9<=$m && $m<=12) {
			 $v = self::$ChineseMonths[2*$index+1];
			 $l = $m - 9;
			 if ( (($v>>$l)&0x01)==1 ) $d = 29;
      } else { // leap month
			 $v = self::$ChineseMonths[2*$index+1];
			 $v = ($v>>4)&0x0F;
			 if ($v!=abs($m)) {
				 $d = 0; // wrong m specified
			 } else {
				 $d = 29; 
				 for ($i=0; $i<count(self::$BigLeapMonthYears); $i++) {
					 if (self::$BigLeapMonthYears[$i]==$index) {
						 $d = 30;
						 break;
					 }
				 }
			 }
		 }
		 return $d;
   }
   public function nextChineseMonth($y, $m) {
		 $n = abs($m) + 1; // normal behavior
		 if ($m>0) {
			 // need to find out if we are in a leap year or not
			 $index = $y - self::BaseChineseYear + self::BaseIndex;
			 $v = self::$ChineseMonths[2*$index+1];
			 $v = ($v>>4)&0x0F;
			 if ($v==$m) $n = -$m; // next month is a leap month
      }
      if ($n==13) $n = 1; //roll into next year
      return $n;
   }
   private function computeSolarTerms() {
      // Gregorian year out of the computation range
      if ($this->gregorianYear<1901 || $this->gregorianYear>2100) return 1;
      $this->sectionalTerm = $this->sectionalTerm($this->gregorianYear, 
																									$this->gregorianMonth);
      $this->principleTerm = $this->principleTerm($this->gregorianYear, 
																									$this->gregorianMonth);
      return 0;
   }
   public function sectionalTerm($y, $m) {
      if ($y<1901 || $y>2100) return 0;
      $index = 0;
      $ry = $y-self::BaseYear+1;
      while ($ry>=self::$SectionalTermYear[$m-1][$index]) $index++;
      $term = self::$SectionalTermMap[$m-1][4*$index+$ry%4];
      if (($ry == 121)&&($m == 4)) $term = 5;
      if (($ry == 132)&&($m == 4)) $term = 5;
      if (($ry == 194)&&($m == 6)) $term = 6;
      return $term;
   }
   public function principleTerm($y, $m) {
      if ($y<1901 || $y>2100) return 0;
      $index = 0;
      $ry = $y-self::BaseYear+1;
      while ($ry>=self::$PrincipleTermYear[$m-1][$index]) $index++;
      $term = self::$PrincipleTermMap[$m-1][4*$index+$ry%4];
      if (($ry == 171)&&($m == 3)) $term = 21;
      if (($ry == 181)&&($m == 5)) $term = 21;
      return $term;
   }
	 private function computeMonthStemBranch(){
		 // -1 to adjust to base 0 index
		 // +2 the first month is tiger
		 $this->monthBranch = ($this->chineseMonth-1+2) % 12;

		 $yearStem  = ($this->chineseYear-1) % 10;
		 $firstStem = (($yearStem*2) + 2) % 10;
		 $this->monthStem = ($firstStem - 1 + $this->chineseMonth) % 10;
	 }	 
	 /**
		* Calculation based on http://www.math.nus.edu.sg/aslaksen/gem-projects/hm/0203-1-44-heavenly_secrets/heavenly_secrets.pdf
		*/
	 private function computeDateStemBranch(){
		 $d = $this->dayOfYear($this->gregorianYear,
													 $this->gregorianMonth,
													 $this->gregorianDate);
		 // Jan 1 1944 was Yang Wood (0) Rat (0)
		 if ($this->gregorianYear == 1944){
			 $c = ($d - 1) % 60;
		 } else if ($this->gregorianYear > 1944){ // years after 1944
			 // get the number of leap year
			 $nextLeap = 1944+4;
			 $l = 1; // 1944 is a leap year
			 while ($nextLeap < $this->gregorianYear){
				 if ($this->isGregorianLeapYear($nextLeap)){
					 $l++;
				 }
				 $nextLeap += 4;
			 }
			 $c = (($this->gregorianYear-1944)*365 + $l + $d-1) % 60;
		 } else { // years before 1944
			 // get the number of leap year
			 $nextLeap = 1940;
			 $l = 0;
			 while ($nextLeap > $this->gregorianYear){
				 if ($this->isGregorianLeapYear($nextLeap)){
					 $l++;
				 }
				 $nextLeap -= 4;
			 }
			 // Jan 1 stem
			 $c = 60 - ((($this->gregorianYear - 1944)*365 - $l) % 60);
			 $c = ($c + $d - 1) % 60;
		 }
		 $this->dateStem = $c % 10;
		 $this->dateBranch = $c % 12;
	 }
	 private function computeDateStemBranchSaved(){
		 $c = 1;
		 if ($this->gregorianYear >= 1901 &&
				 $this->gregorianYear < 2000){
			 $n = $this->gregorianYear - 1900;
			 $d = $this->dayOfYear($this->gregorianYear,
														 $this->gregorianMonth,
														 $this->gregorianDate);
			 $a = $this->isGregorianLeap ? 11 : 10;
			 $c = (($n*5) + round($n/4) + ($d) + $a) % 60;
		 }
		 if ($this->gregorianYear >= 2000 &&
				 $this->gregorianYear < 2100){
			 $n = $this->gregorianYear - 2000;
			 $d = $this->dayOfYear($this->gregorianYear,
														 $this->gregorianMonth,
														 $this->gregorianDate);
			 $a = $this->isGregorianLeap ? 6 : 5;
			 $c = (($n+5) + round($n/4) + ($d) - $a) % 60;
		 }
		 // use 0 based index
		 $this->dateStem = ($c-1) % 10;
		 $this->dateBranch = ($c-1) % 12;

		 return 0;
	 }
	 private function computeHourStemBranch(){
		 if ($this->gregorianHour < 0 ||
				 $this->gregorianHour > 23){
			 // out of range
			 return 1;
		 }
		 foreach (self::$HourBranchMap as $i=>$hours){
			 if (in_array($this->gregorianHour, $hours)){
				 $this->hourBranch = $i;
				 break;
			 }
		 }
		 // calculate from datestem
		 $ratStem = ($this->dateStem * 2) % 10;
		 $this->hourStem = ($ratStem + $this->hourBranch) % 10;
		 
		 return 0;
	 }

  /**
   * @
   */
  public static function getBranchName($branch_num){
    $branch_num = $branch_num-1;
    if (isset(ChineseCalendar::$BranchNames[$branch_num])){
      return ChineseCalendar::$BranchNames[$branch_num];
    }
    return null;
  }
}
?>
