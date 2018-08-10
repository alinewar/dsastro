<?php

/**
 * Darby Solutions Astrology
 *
 * @file
 * Contains \Drupal\dsastro\Model\AstrologyInterface.
 */

namespace Drupal\dsastro\Model;

/**
 * Provides an interface for Astrology.
 */
interface AstrologyInterface {

  /**
   * Sets date and loads astrology data
   *
   * @param string $date
   *  Date in any valid date format. XX-XX-XX is treated as YY-MM-DD
   *
   * @return FALSE|string
   *  String in YYYY-MM-DD format or FALSE if not valid format
   */
  public function setDate($date);

  /**
   * Gets date property in YYYY-MM-DD format
   *
   * @return FALSE|string
   *  String in YYYY-MM-DD format or FALSE if not set yet
   */
  public function getDate();

  /**
   * Gets the emphemeris array
   *
   * Getts teh emphemeris array. The array has the following keys
   *  Header - Header showing order of planets in emphemeris text
   *  Text - Text of emphemeris as read from file
   *
   * @return FALSE|string
   */
  public function getEmphemeris();

  /**
   * @param string $planet
   *  Proper name of planet to return. Set to 'All" for whole array
   *
   * @return FALSE|string|array
   *  String with sign for one planet. Array for All
   */
  public function getPlanets($planet = 'All');

  /**
   * Converts two digit abbreviation to full astrology name
   *
   * @param string $signInitials
   *  initials of astrology sign
   *
   * @return bool|FALSE|string
   *  Full astrology name or FALSE
   */
  public function getFullName($signInitials);

  /**
   * Converts sign name into two digit abbreviation
   *
   * @param string $sign
   *  astrology sign
   *
   * @return bool|FALSE|string
   *  Abbreviation of astrology sign or FALSE
   */
  public function getAbbrev($sign);

  /**
   * Get the primary astrology, categories, and compatibility.
   *
   * @return FALSE|array
   *  Array including HTML chart of astrology for birth date
   */
  public function getSummary();

}

