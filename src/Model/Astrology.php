<?php

/**
 * Darby Solutions Astrology
 *
 * @file
 * Contains \Drupal\dsastro\Model\Astrology.
 */

namespace Drupal\dsastro\Model;

//Astrology.php

class Astrology implements AstrologyInterface {

  private $errorMsg;
  private $date;
  private $emphemeris = FALSE;
  private $planets = FALSE;

  public function __construct($date = FALSE) {
    if ($date) {
      $this->setDate($date);
    }
  }

  public function __destruct() {
  }

  /**
   * Converts date string to YYYY-MM-DD format
   *
   * Takes a string and makes sure it is a valid date
   * and then returns a string formatted like YYYY-MM-DD
   * that is acceptable to MySQL and HTML
   *
   * @param string $date
   *  Date in any valid date format. XX-XX-XX treated as YY-MM-DD
   *
   * @return FALSE|string
   *  YYYY-MM-DD or FALSE if not valid format.
   */
  public function formatDate ($date) {
    $dateString = FALSE;
    $dateTime = strtotime($date);
    if (!$dateTime) {
      // strtotime treats XX-XX-XX as YY-MM-DD
      // so 3-7-1969 will return FALSE
      // 3/7/1969 is read properly
      $date = str_replace('-', '/', $date);
      $dateTime = strtotime($date);
    }
    if ($dateTime) {
      $dateString = date("Y-m-d", $dateTime); // Today YYYY-MM-DD
    }
    else {
      $this->errorMsg[] = 'Not a valid date';
    }
    return $dateString;
  }

  /**
   * {@inheritdoc}
   */
  public function setDate($date) {
    $result = FALSE;
    $newDate = $this->formatDate($date);
    if ($newDate != $this->date) {
      $this->date = $newDate;
      if ($this->setEmphemeris()) {
        $result = $newDate;
      }
      else {
        $this->date = FALSE;
      }
    }
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function getDate() {
    return $this->date;
  }

  /**
   * Assigns the emphemeris to the emphemeris and planets array.
   *
   * @return bool
   */
  private function setEmphemeris() {
    // Dates in ephemeris files have format Mo/Dy/Yr
    // each part is two digits with no leading zeros like
    //  2/ 1/ 5 for Feb 1, 2005

    $dateArray = date_parse($this->date);
    $results = FALSE;
    if ($dateArray) {
      if ($dateArray['year'] >= 1900 || $dateArray['year'] <= 2099) {
        // Format date to look up
        if ($dateArray['month'] < 10) {
          $dateArray['month'] = " " . $dateArray['month'];
        }
        if ($dateArray['day'] < 10) {
          $dateArray['day'] = " " . $dateArray['day'];
        }
        // Search for date in proper file
        $searchString = "$dateArray[month]/$dateArray[day]";
        if ($dateArray['year'] < 2000) {
          // 20th Century
          $fileName = __DIR__ . "/ephemeris/eph20th/$dateArray[year].N";
        } else {
          // 21st Century
          $fileName = __DIR__ . "/ephemeris/eph21st/EPH$dateArray[year].TXT";
        }
        $myFile = fopen("$fileName", "r") or die("Unable to open file $fileName");
        do {
          $fileLine = fgets($myFile);
        } while (!feof($myFile) && substr($fileLine, 0, 5) != $searchString);
        fclose($myFile);
        if (substr($fileLine, 0, 5) == $searchString) {
          $this->emphemeris['Header'] = 'Mo/Dy/Yr  Sun    Moon   Merc   Venu   Mars   Jupi   Satu   Uran   Nept   Plut  CHIRON  NODE';
          $this->emphemeris['Text'] = $fileLine;
          $this->planets['Date'] = substr($fileLine, 0, 8);
          $this->planets['Sun'] = $this->getFullName(substr($fileLine, 11, 2));
          $this->planets['Moon'] = $this->getFullName(substr($fileLine, 18, 2));
          $this->planets['Mercury'] = $this->getFullName(substr($fileLine, 25, 2));
          $this->planets['Venus'] = $this->getFullName(substr($fileLine, 32, 2));
          $this->planets['Mars'] = $this->getFullName(substr($fileLine, 39, 2));
          $this->planets['Jupiter'] = $this->getFullName(substr($fileLine, 46, 2));
          $this->planets['Saturn'] = $this->getFullName(substr($fileLine, 53, 2));
          $this->planets['Uranus'] = $this->getFullName(substr($fileLine, 60, 2));
          $this->planets['Neptune'] = $this->getFullName(substr($fileLine, 67, 2));
          $this->planets['Pluto'] = $this->getFullName(substr($fileLine, 74, 2));
          $results = TRUE;
        }
        else {
          $this->emphemeris = FALSE;
          $this->planets = FALSE;
        }
      }
    }
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmphemeris() {
    return $this->emphemeris;
  }

  /**
   * {@inheritdoc}
   */
  public function getPlanets($planet = 'All') {
    $planet = ucwords(strtolower($planet));
    if ($planet == 'All') {
      return $this->planets;
    }
    else {
      if ($this->planets && array_key_exists($planet, $this->planets)) {
        return $this->planets[$planet];
      }
      else {
        $this->errorMsg[] = 'Could not find planet.';
        return FALSE;
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getFullName($signInitials) {
    switch ($signInitials) {
      case "Ar":
        $fullName = "Aries";
        break;
      case "Ta":
        $fullName = "Taurus";
        break;
      case "Ge":
        $fullName = "Gemini";
        break;
      case "Cn":
        $fullName = "Cancer";
        break;
      case "Le":
        $fullName = "Leo";
        break;
      case "Vi":
        $fullName = "Virgo";
        break;
      case "Li":
        $fullName = "Libra";
        break;
      case "Sc":
        $fullName = "Scorpio";
        break;
      case "Sg":
        $fullName = "Sagittarius";
        break;
      case "Cp":
        $fullName = "Capricorn";
        break;
      case "Aq":
        $fullName = "Aquarius";
        break;
      case "Pi":
        $fullName = "Pisces";
        break;
      default:
        $fullName = FALSE;
    }
    return $fullName;
  }

  /**
   * {@inheritdoc}
   */
  public function getAbbrev($sign) {
    switch ($sign) {
      case "Aries":
        $abbrev = "Ar";
        break;
      case "Taurus":
        $abbrev = "Ta";
        break;
      case "Gemini":
        $abbrev = "Ge";
        break;
      case "Cancer":
        $abbrev = "Cn";
        break;
      case "Leo":
        $abbrev = "Le";
        break;
      case "Virgo":
        $abbrev = "Vi";
        break;
      case "Libra":
        $abbrev = "Li";
        break;
      case "Scorpio":
        $abbrev = "Sc";
        break;
      case "Sagittarius":
        $abbrev = "Sg";
        break;
      case "Capricorn":
        $abbrev = "Cp";
        break;
      case "Aquarius":
        $abbrev = "Aq";
        break;
      case "Pisces":
        $abbrev = "Pi";
        break;
      default:
        $abbrev = FALSE;
    }
    return $abbrev;
  }

  /**
   * {@inheritdoc}
   */
  public function getSummary() {
    // Dates in ephemeris files have format Mo/Dy/Yr
    // each part is two digits with no leading zeros like
    //  2/ 1/ 5 for Feb 1, 2005

    $results = FALSE;

    if ($this->date) {
      switch ($this->getAbbrev($this->planets['Sun'])) {
        case "Ar":
          $SunSign = "Aries";
          $Element = "Fire";
          $ElementDescription = "Passionate";
          $Quality = "Cardinal";
          $QualityDescription = "Personable";
          $BestMatch = "Leo, Sagittarius";
          $SecondBestMatch = "Cancer, Libra, Capricorn";
          break;
        case "Ta":
          $SunSign = "Taurus";
          $Element = "Earth";
          $ElementDescription = "Frugal";
          $Quality = "Fixed";
          $QualityDescription = "Determined";
          $BestMatch = "Virgo, Capricorn";
          $SecondBestMatch = "Leo, Scorpio, Aquarius";
          break;
        case "Ge":
          $SunSign = "Gemini";
          $Element = "Air";
          $ElementDescription = "Independent";
          $Quality = "Mutable";
          $QualityDescription = "Analytical";
          $BestMatch = "Libra, Aquarius";
          $SecondBestMatch = "Virgo, Sagittarius, Pisces";
          break;
        case "Cn":
          $SunSign = "Cancer";
          $Element = "Water";
          $ElementDescription = "Maternal";
          $Quality = "Cardinal";
          $QualityDescription = "Personable";
          $BestMatch = "Scorpio, Pisces";
          $SecondBestMatch = "Libra, Capricorn, Aries";
          break;
        case "Le":
          $SunSign = "Leo";
          $Element = "Fire";
          $ElementDescription = "Passionate";
          $Quality = "Fixed";
          $QualityDescription = "Determined";
          $BestMatch = "Sagittarius, Aries";
          $SecondBestMatch = "Scorpio, Aquarius, Taurus";
          break;
        case "Vi":
          $SunSign = "Virgo";
          $Element = "Earth";
          $ElementDescription = "Frugal";
          $Quality = "Mutable";
          $QualityDescription = "Analytical";
          $BestMatch = "Capricorn, Taurus";
          $SecondBestMatch = "Sagittarius, Pisces, Gemini";
          break;
        case "Li":
          $SunSign = "Libra";
          $Element = "Air";
          $ElementDescription = "Independent";
          $Quality = "Cardinal";
          $QualityDescription = "Personable";
          $BestMatch = "Aquarius, Gemini";
          $SecondBestMatch = "Capricorn, Aries, Cancer";
          break;
        case "Sc":
          $SunSign = "Scorpio";
          $Element = "Water";
          $ElementDescription = "Maternal";
          $Quality = "Fixed";
          $QualityDescription = "Determined";
          $BestMatch = "Pisces, Cancer";
          $SecondBestMatch = "Aquarius, Taurus, Leo";
          break;
        case "Sg":
          $SunSign = "Sagittarius";
          $Element = "Fire";
          $ElementDescription = "Passionate";
          $Quality = "Mutable";
          $QualityDescription = "Analytical";
          $BestMatch = "Leo, Aries";
          $SecondBestMatch = "Pisces, Gemini, Virgo";
          break;
        case "Cp":
          $SunSign = "Capricorn";
          $Element = "Earth";
          $ElementDescription = "Frugal";
          $Quality = "Cardinal";
          $QualityDescription = "Personable";
          $BestMatch = "Taurus, Virgo";
          $SecondBestMatch = "Aries, Cancer, Libra";
          break;
        case "Aq":
          $SunSign = "Aquarius";
          $Element = "Air";
          $ElementDescription = "Independent";
          $Quality = "Fixed";
          $QualityDescription = "Determined";
          $BestMatch = "Gemini, Libra";
          $SecondBestMatch = "Taurus, Leo, Scorpio";
          break;
        case "Pi":
          $SunSign = "Pisces";
          $Element = "Water";
          $ElementDescription = "Maternal";
          $Quality = "Mutable";
          $QualityDescription = "Analytical";
          $BestMatch = "Cancer, Scorpio";
          $SecondBestMatch = "Gemini, Virgo, Sagittarius";
          break;
        default:
          $SunSign = NULL;
          $Element = NULL;
          $ElementDescription = NULL;
          $Quality = NULL;
          $QualityDescription = NULL;
          $BestMatch = NULL;
          $SecondBestMatch = NULL;
      }
      $results['SunSign'] = $SunSign;
      $results['Element'] = $Element;
      $results['Quality'] = $Quality;
      $results['BestMatch'] = $BestMatch;
      $results['SecondBestMatch'] = $SecondBestMatch;
      $results['ElementDescription'] = $ElementDescription;
      $results['QualityDescription'] = $QualityDescription;
      $results['Chart'] = "
        <table>
          <thead>
          <tr>
            <th>Sign</th>
            <th>Element</th>
            <th>Quality</th>
          </tr>
          </thead>
          <tr>
          <td>$SunSign</td>
          <td>$Element<br/>($ElementDescription)</td>
          <td>$Quality<br/>($QualityDescription)</td>
          </tr>
        </table>
        <br/>
        <table>
          <thead>
          <tr>
            <th>Best Matches</th>
            <th>Second Best</th>
          </tr>
          </thead>
          <tr>
          <td>$BestMatch</td>
          <td>$SecondBestMatch</td>
          </tr>
        </table>
      ";
    }
    return $results;
  }

}
