<?php
 
namespace Drupal\ex_form\Form;
 
use Drupal\Core\Form\FormBase;                   
use Drupal\Core\Form\FormStateInterface;   
use Egulias\EmailValidator\EmailValidator;          
/**
 * Наследуемся от базового класса Form API
 * @see \Drupal\Core\Form\FormBase
 */
	class ExForm extends FormBase {
	
		// метод, который отвечает за саму форму - кнопки, поля
		public function buildForm(array $form, FormStateInterface $form_state) {
			$form['first_name'] = [
				'#type' => 'textfield',
				'#title' => $this->t('Ваше имя'),
				'#description' => $this->t('Имя не должно содержать цифр'),
				'#required' => TRUE,
			];
		  
			$form['last_name'] = [
				'#type' => 'textfield',
				'#title' => $this->t('Ваша фамилия'),
				'#description' => $this->t('Фамилия не должно содержать цифр'),
				'#required' => TRUE,
			];
		  
			$form['subject'] = [
				'#type' => 'textfield',
				'#title' => $this->t('Тема'),
				'#required' => TRUE,
			];
		  
			$form['messege'] = [
				'#type' => 'textarea',
				'#title' => $this->t('Сообщение'),
				'#required' => TRUE,
				'#maxlength' => 255,
			];
		  
			$form['email'] = [
				'#type' => 'textfield',
				'#title' => $this->t('E-mail'),
				'#required' => TRUE,
				
			];
		 
			// Add a submit button that handles the submission of the form.
			$form['actions']['submit'] = [
				'#type' => 'submit',
				'#value' => $this->t('Отправить форму'),
			];
		 
			return $form;
		}
	 
	 // метод, который будет возвращать название формы
		public function getFormId() {
			return 'ex_form_exform_form';
		}
	 
	 // ф-я валидации
		public function validateForm(array &$form, FormStateInterface $form_state) {
			$firstname = $form_state->getValue('first_name');
			$lastname = $form_state->getValue('last_name');
			$is_number = preg_match("/[\d]+/", $firstname, $match);
			$e_mail = $form_state->getValue('email');
			
			if ($is_number > 0) {
				$form_state->setErrorByName('first_name', $this->t('Строка содержит цифру.'));
				$form_state->setErrorByName('last_name', $this->t('Строка содержит цифру.'));
			}
			
			if (!$form_state->getValue('email') || !filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
				$form_state->setErrorByName('email', $this->t('Votre adresse e-mail semble invalide.'));
			}
		}
		
		// действия по сабмиту
		public function submitForm(array &$form, FormStateInterface $form_state) {
			$id_hubspot = 'e86ea99f-a41c-4649-9519-824265a38990';
			
			$firstname = $form_state->getValue('first_name');
			$lastname = $form_state->getValue('last_name');
			$subject = $form_state->getValue('subject');
			$messege = $form_state->getValue('messege');
			$e_mail = $form_state->getValue('email');
			drupal_set_message(t('Вы ввели: %first_name.', ['%first_name' => $firstname]));
			drupal_set_message(t('Вы ввели: %last_name.', ['%last_name' => $lastname]));
			drupal_set_message(t('Вы ввели: %subject.', ['%subject' => $subject]));
			drupal_set_message(t('Вы ввели: %messege.', ['%messege' => $messege]));
			drupal_set_message(t('Вы ввели: %email.', ['%email' => $e_mail]));
			
			/*$email = $form_state->getValue('email');
			$firstname = $form_state->getValue('first_name');
			$lastname = $form_state->getValue('last_name');*/

			$url = "https://api.hubapi.com/contacts/v1/contact/createOrUpdate/email/".$e_mail."/?hapikey=".$id_hubspot;

			$data = array(
			  'properties' => [
				[
				  'property' => 'firstname',
				  'value' => $firstname
				],
				[
				  'property' => 'lastname',
				  'value' => $lastname 
				]
			  ]
			);

			$json = json_encode($data,true);

			$response = \Drupal::httpClient()->post($url.'&_format=hal_json', [
			  'headers' => [
				'Content-Type' => 'application/json'
			  ],
				'body' => $json
			]);
		}
	}
?>