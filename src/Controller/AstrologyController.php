<?php

/**
 * Darby Solutions Astrology Controller
 *
 * @file
 * Contains \Drupal\dsastro\Controller\AstrologyController.
 */

// Namespace start with Drupal and assume they are in the src folder
// This namespace is in dsastro\src\Controller folder
namespace Drupal\dsastro\Controller;

// Do not use fully-qualified name inside code. Use the use command
// Do not "use" global classes. Global classes should be called using \
// like new \Exception();
// If class name used in string, use full name. Single quoted strings preferred
// like 'Drupal\Context\ContextInterface'
// Only use aliasing to avoid name collisions.
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// use Drupal\dsastro\Model\Astrology;
use Drupal\Core\Controller\ControllerBase;

// Classes named with UpperCamel
class AstrologyController extends ControllerBase
{

  // leave an empty line after class definition
  // leave an empty line at end of property definitions

  // Methods and properties use lowerCamel
  // If acronym is used, make it CamelCase
  // like SampleXmlClass not SampleXMLClass
  // Don't use underscore in class names unless absolutely necessary
  // Interfaces should always have the suffix "Interface"
  // Make interface for classes and put documentation there
  // Test Classes should always have the suffix "Test"
  public function getDetails($month, $day, $year)
  {
    $birthdate = $month.'/'.$day.'/'.$year;
    $astrology = \Drupal::service('dsastro.astrology');
    if ($date = $astrology->setDate($birthdate)) {
      $astro = $astrology->getSummary();
    }
    if ($astro) {
      // Default settings.
      $config = \Drupal::config('dsastro.settings');
      // Page title and source text.
      $pageTitle = $config->get('dsastro.page_title');
      $pageText = $config->get('dsastro.page_text');
      // markup render should be avoided because it limits what themes can do. Using theme or type
      // is preferred.
      $output['chart'] = [
        '#markup' => $this->t("<h2>$pageTitle</h2><p>$pageText</p><h2>Birth Date: $date</h2>$astro[Chart]"),
      ];
      $planets = $astrology->getPlanets();
      $output['table'] = [
        // The value used for #type is the ID of the plugin that implements the
        // element type you want to use. This can be inferred from the annotation
        // for the element.
        // You can also find a list of element types provided by Drupal core here
        // https://api.drupal.org/api/drupal/elements.
        '#type' => 'table',
        '#caption' => $this->t("Emphemeris for $date"),
        '#header' => [
          $this->t('Planet'),
          $this->t('Sign'),
        ],
        '#rows' => [
          [
            $this->t('Sun'),
            $this->t($planets['Sun']),
          ],
          [
            $this->t('Moon'),
            $this->t($planets['Moon']),
          ],
          [
            $this->t('Mercury'),
            $this->t($planets['Mercury']),
          ],
          [
            $this->t('Venus'),
            $this->t($planets['Venus']),
          ],
          [
            $this->t('Mars'),
            $this->t($planets['Mars']),
          ],
          [
            $this->t('Jupiter'),
            $this->t($planets['Jupiter']),
          ],
          [
            $this->t('Saturn'),
            $this->t($planets['Saturn']),
          ],
          [
            $this->t('Uranus'),
            $this->t($planets['Uranus']),
          ],
          [
            $this->t('Neptune'),
            $this->t($planets['Neptune']),
          ],
          [
            $this->t('Pluto'),
            $this->t($planets['Pluto']),
          ],
        ],
        '#description' => $this->t('Example of using #type.'),
      ];
      $emp = $astrology->getEmphemeris();
      $output['header'] = [
        '#markup' => "<p>$emp[Header]</p>",
      ];
      $output['emphemeris'] = [
        '#plain_text' => $emp['Text'],
      ];
      $output['sunSign'] = [
        '#markup' => "<p>".$astrology->getPlanets('Sun')."</p>",
      ];
    } else {
      $output['astro'] = [
        '#markup' => "<em>$birthdate Bad format or not covered year.</em>",
      ];
    }
    return $output;
  }

  // leave an empty line before closing class

}