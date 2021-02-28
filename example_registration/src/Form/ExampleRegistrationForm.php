<?php

namespace Drupal\example_registration\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Symfony\Component\HttpFoundation\RedirectResponse;
use CommerceGuys\Addressing\Subdivision\SubdivisionRepository;
/**
 * Class ExampleRegistrationForm.
 *
 * @package Drupal\example_registration\Form
 */
class ExampleRegistrationForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'example_registration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $country = \Drupal::service('country_manager')->getList();


    //hardcoded some city name for some state of India Country..
    $city = [
        "Chandigarh"=> [
          "Chandigarh*" => 'jasfs',
        ],
        "Delhi"=> [
          "Delhi",
          "New Delhi*"
        ],
        "Goa"=> [
          "Mapusa",
          "Margao",
          "Marmagao",
          "Panaji*"
        ],
        "Gujarat"=> [
          
          "Surat",
          "Talaja",
          "Thangadh",
          "Tharad",
          "Umbergaon",
          "Umreth",
          "Una",
        ],
        "Himachal Pradesh"=> [
          "Mandi",
          "Nahan",
          "Palampur",
          "Shimla*",
          "Solan",
          "Sundarnagar"
        ],
        "Jammu and Kashmir"=> [
          "Anantnag",
          "Baramula",
          "Jammu",
          "Kathua",
          "Punch",
          "Rajauri",
          "Sopore",
          "Srinagar*",
          "Udhampur"
        ],
        "Madhya Pradesh"=> [
          "Alirajpur",
          "Ashok Nagar",
          "Balaghat",
          "Bhopal",
          "Ganjbasoda",
          "Gwalior",
          "Indore",
          "Itarsi",
          "Jabalpur",
          "Lahar",
          "Maharajpur",
          "Mahidpur",
          "Maihar",
          "Malaj Khand",
          "Manasa",
          "Manawar",
          "Mandideep",
          "Mandla",
          "Mandsaur",
          "Mauganj",
          "Mhow Cantonment",
          "Mhowgaon",
          "Morena",
          "Multai",
          "Mundi",
          "Murwara (Katni)",
          "Nagda",
          "Nainpur",
          "Narsinghgarh",
          "Narsinghgarh",
          "Neemuch",
          "Nepanagar",
          "Niwari",
          "Nowgong",
          "Nowrozabad (Khodargama)",
          "Pachore",
          "Pali",
          "Panagar",
          "Pandhurna",
          "Panna",
          "Pasan",
          "Pipariya",
          "Pithampur",
          "Porsa",
          "Prithvipur",
          "Raghogarh-Vijaypur",
          "Rahatgarh",
          "Raisen",
          "Rajgarh",
          "Ratlam",
          "Rau",
          "Rehli",
          "Rewa",
          "Sabalgarh",
          "Sagar",
          "Sanawad",
          "Sarangpur",
          "Sarni",
          "Satna",
          "Sausar",
          "Sehore",
          "Sendhwa",
          "Seoni",
          "Seoni-Malwa",
          "Shahdol",
          "Shajapur",
          "Shamgarh",
          "Sheopur",
          "Shivpuri",
          "Shujalpur",
          "Sidhi",
          "Sihora",
          "Singrauli",
          "Sironj",
          "Sohagpur",
          "Tarana",
          "Tikamgarh",
          "Ujjain",
          "Umaria",
          "Vidisha",
          "Vijaypur",
          "Wara Seoni"
        ],
        "Maharashtra"=> [
          "Dhule",
          "Kalyan-Dombivali",
          "Manjlegaon",
          "Manmad",
          "Manwath",
          "Mehkar",
          "Mhaswad",
        ],
        "Uttar Pradesh"=> [
          "Unnao",
          "Utraula",
          "Varanasi",
          "Vrindavan",
          "Warhapur",
          "Zaidpur",
          "Zamania"
        ],
        "Uttarakhand"=> [
          "Bageshwar",
          "Roorkee",
          "Rudrapur",
          "Sitarganj",
          "Srinagar",
          "Tehri"
        ],
    ];

    $form['user_name'] = [
      '#type' => 'textfield',
      '#title' => t('User Name:'),
      '#required' => TRUE,
      '#suffix' => '<div id="user-name"></div>',
      '#ajax' => [
        'callback' => '::checkUserNameValidation',
        'effect' => 'fade',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Verifying entry...'),
        ],
      ],
    ];

    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => t('First Name:'),
      '#required' => TRUE,
    ];

    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => t('Last Name:'),
      '#required' => TRUE,
    ];

    $form['user_gender'] = [
      '#type' => 'select',
      '#title' => ('Gender'),
      '#required' => TRUE,
      '#options' => [
        'female' => t('Female'),
        'male' => t('Male'),
        'other' => t('Other'),
        ],
    ];

    $form['user_country'] = [
      '#type' => 'select',
      '#title' => ('Select Country'),
      '#required' => TRUE,
      '#options' => $country,
      '#ajax' => [
        'callback' => '::getStateName',
        'disable-refocus' => FALSE,
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Verifying entry...'),
        ],
        'wrapper' => 'first',
      ],
    ];
    $states =['- Select -'];
    $form['user_state'] = [
      '#type' => 'select',
      '#title' => $this->t('Select State'),
      '#prefix' => '<div id="first">',
      '#suffix' => '</div>',
      '#validated' => 'true',
      '#options' => $states,
      '#default_value' => t('Select'),
      '#ajax' => [
        'callback' => '::myAjaxCallback2',
        'disable-refocus' => FALSE,
        'event' => 'change',
        'wrapper' => 'city',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Verifying entry...'),
        ],
      ],
    ];

    $form['user_city'] = [
      '#type' => 'select',
      '#title' => ('City'),
      '#prefix' => '<div id="city">',
      '#suffix' => '</div>',
      '#required' => TRUE,
      '#options' => $city,
      '#ajax' => [
        'callback' => '::myAjaxCallback3',
        'disable-refocus' => FALSE,
        'event' => 'change',
        'wrapper' => 'city1',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Verifying entry...'),
        ],
      ],
    ];

    $form['submit'] = [
        '#type' => 'submit',
        '#value' => t('Submit'),
    ];

    return $form;
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $field=$form_state->getValues();
    $res = $this->getUserData($field);
    if(!empty($res)){
      $form_state->setErrorByName('user_name', $this->t('User name must be unique'));
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $field=$form_state->getValues();
    $field  = array(
      'user_name'   =>  $field['user_name'],
      'first_name' =>  $field['first_name'],
      'last_name' =>  $field['last_name'],
      'user_gender' =>  $field['user_gender'],
      'user_country' => $field['user_country'],
      'user_state' => $field['user_state'],
      'user_city' => $field['user_city'],
    );
    $query = \Drupal::database();
    $query ->insert('example_registration')
      ->fields($field)
      ->execute();
    drupal_set_message("succesfully saved");

    $response = new RedirectResponse("/user/data");
    $response->send();
  }

  /**
   * checkUserNameValidation.
   *
   */
  public function checkUserNameValidation(array $form, FormStateInterface $form_state) {

    $field=$form_state->getValues();
    $res = $this->getUserData($field);
    if(!empty($res)) {
      $text = 'User Name is already exists. Please select other User name';
    } else {
      $text = 'Valid User Name';
    }

    $response = new AjaxResponse();
    $response->addCommand(
      new HtmlCommand('#user-name', $text)
    );
    return $response;
  }

  /**
   * getUserData.
   *
   */
  public function getUserData($data) {
    # code...
    $query = \Drupal::database()->select('example_registration', 'm');
    $query->fields('m', ['user_name']);
    $query->condition('m.user_name', $data['user_name'], '=' );
    $results = $query->execute()->fetchAll();
    return $results;
  }

  /**
   * getStateName.
   *
   */
  public function getStateName(array $form, FormStateInterface $form_state) {
  
    if ($selectedValue = $form_state->getValue('user_country')) {
      $subdivisionRepository = new SubdivisionRepository();
      $states = [];
      $states = $subdivisionRepository->getList([$selectedValue]);
      if(!empty($states)){
        $form['user_state']['#options'] = $states;
      } else {
        $arr = ['no_data' => 'No Data Found'];
        $form['user_state']['#options'] = $arr;
      }
    }
    $form_state->setRebuild(TRUE);
    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand("#first", ($form['user_state'])));
    return $response;
  }

}
