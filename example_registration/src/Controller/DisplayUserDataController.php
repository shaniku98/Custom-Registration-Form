<?php

namespace Drupal\example_registration\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

/**
 * Class DisplayUserDataController.
 *
 * @package Drupal\example_registration\Controller
 */
class DisplayUserDataController extends ControllerBase {

  /**
   * dataDisplay.
   *
   * @return string
   *   Return Hello string.
   */
  public function dataDisplay() {
    //create table header
    $header_table = [
      'id'=> t('SrNo'),
      'user_name' => t('User Name'),
      'first_name' => t('First Name'),
      'last_name'=>t('Last Name'),
      'user_gender' => t('Gender'),
      'user_country' => t('Country'),
      'user_state' => t('State'),
      'user_city' => t('City'),
    ];

    //select records from table
    $query = \Drupal::database()->select('example_registration', 'm');
    $query->fields('m', ['id','user_name','first_name','last_name','user_gender','user_country','user_state', 'user_city']);
    $results = $query->execute()->fetchAll();
    $rows=[];
    foreach($results as $data){
      //print the data from table
      $rows[] = [
        'id'=> $data->id,
        'user_name' => $data->user_name,
        'first_name' => $data->first_name,
        'last_name'=> $data->last_name,
        'user_gender' => $data->user_gender,
        'user_country' => $data->user_country,
        'user_state' => $data->user_state,
        'user_city' => $data->user_city,
      ];

    }
    //display data in site
    $form['table'] = [
      '#type' => 'table',
      '#header' => $header_table,
      '#rows' => $rows,
      '#empty' => t('No users found'),
    ];
    //echo '<pre>';print_r($form['table']);exit;
    return $form;
  }

}
