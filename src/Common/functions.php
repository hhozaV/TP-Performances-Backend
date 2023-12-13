<?php

namespace App\Common;

use const App\__PROJECT_ROOT__;

/**
 * Gets the HTML code from a given template. You can pass optional parameters to display them into the template
 *
 * @param string $templatePath
 * @param array  $args
 *
 * @return string
 */
function get_template ( string $templatePath, array $args ) : string {
  extract( $args );
  
  ob_start();
  require $templatePath;
  
  return ob_get_clean();
}



/**
 * Gets the common header HTML code, you can set an optional page meta title
 *
 * @param array{
 *     title:string
 * } $args
 *
 * @return string
 */
function get_header ( array $args ) : string {
  extract( $args );
  
  ob_start();
  require __PROJECT_ROOT__ . "/Views/Fragments/header.php";
  
  return ob_get_clean();
}



/**
 * Gets the common footer HTML Code
 *
 * @return string
 */
function get_footer () : string {
  ob_start();
  require __PROJECT_ROOT__ . "/Views/Fragments/footer.php";
  
  return ob_get_clean();
}



/**
 * Gets the 404 page HTML code
 *
 * @return string
 */
function get_404 () : string {
  http_response_code( 404 );
  
  ob_start();
  require __PROJECT_ROOT__ . "/Views/404.php";
  
  return ob_get_clean();
}



/**
 * Affiche une checkbox
 *
 * @param string      $name
 * @param bool        $checked
 * @param mixed|null  $value
 * @param string|null $label
 *
 * @return string
 */
function render_checkbox ( string $name, bool $checked, ?string $label = null, mixed $value = null ) : string {
  return get_template( __PROJECT_ROOT__ . '/Views/Fragments/Form/input-checkbox.php', [
    'name' => $name,
    'value' => $value,
    'checked' => $checked,
    'label' => $label,
  ] );
}



/**
 * Affiche une liste de checkboxes
 *
 * @param string      $name
 * @param array       $values
 * @param string|null $label
 * @param array       $valuesLabels
 *
 * @return string
 */
function render_checkbox_list ( string $name, array $values, ?string $label = null, array $valuesLabels = [] ) : string {
  return get_template( __PROJECT_ROOT__ . '/Views/Fragments/Form/checkbox-list.php', [
    'name' => $name,
    'values' => $values,
    'valuesLabels' => $valuesLabels,
    'label' => $label,
  ] );
}



/**
 * Affiche un input
 *
 * @param string      $name
 * @param string|null $value
 * @param array{
 *   label:string,
 *   placeholder:string,
 *   type: string,
 *   id: string,
 *   labelAsTitle: string,
 *   hideLabel: boolean,
 *   suffix: string
 * }                  $args
 *
 * @return string
 */
function render_input ( string $name, ?string $value = null, array $args = [] ) : string {
  return get_template(
    __PROJECT_ROOT__ . '/Views/Fragments/Form/input.php', [
    'name' => $name,
    'value' => $value,
    ...$args,
  ] );
}



/**
 * Sécurise l'affichage d'une valeur en empêchant les attaques XSS
 *
 * @param string|null $value
 *
 * @return string|null
 */
function sanitize ( ?string $value ) : ?string {
  if ( ! isset( $value ) )
    return null;
  
  return htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' );
}



/**
 * Convertit une chaîne en slug
 *
 * @param        $text
 * @param string $divider
 *
 * @return string
 */
function slugify ( $text, string $divider = '-' ) : string {
  // replace non letter or digits by divider
  $text = preg_replace( '~[^\pL\d]+~u', $divider, $text );
  
  // remove unwanted characters
  $text = preg_replace( '~[^-\w]+~', '', $text );
  
  // trim
  $text = trim( $text, $divider );
  
  // remove duplicate divider
  $text = preg_replace( '~-+~', $divider, $text );
  
  // lowercase
  $text = strtolower( $text );
  
  if ( empty( $text ) ) {
    return 'n-a';
  }
  
  return $text;
}