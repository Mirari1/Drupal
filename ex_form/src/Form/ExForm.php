<?php
 
namespace Drupal\ex_form\Form;
 
use Drupal\Core\Form\FormBase;                   // Базовый класс Form API
use Drupal\Core\Form\FormStateInterface;              // Класс отвечает за обработку данных
 
/**
 * Наследуемся от базового класса Form API
 * @see \Drupal\Core\Form\FormBase
 */
class ExForm extends FormBase {
 
 // метод, который отвечает за саму форму - кнопки, поля
 public function buildForm(array $form, FormStateInterface $form_state) {
 
  $form['name'] = [
   '#type' => 'textfield',
   '#title' => $this->t('Ваше имя'),
   '#description' => $this->t('Имя не должно содержать цифр'),
   '#required' => TRUE,
  ];
  
  $form['surname'] = [
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
  ];
  
   $form['mail'] = [
   '#type' => 'email',
   '#title' => $this->t('E-mail'),
   '#required' => TRUE,
   '#element_validate' => array('myelement_email_validate')
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
  $name = $form_state->getValue('name');
  $surname = $form_state->getValue('surname');
  $is_number = preg_match("/[\d]+/", $name, $match);
 
  if ($is_number > 0) {
   $form_state->setErrorByName('name', $this->t('Строка содержит цифру.'));
   $form_state->setErrorByName('surname', $this->t('Строка содержит цифру.'));
  }
 }
 
function myelement_email_validate($element, &$form_state, $form) {
  $value = $element['#value'];
  if (!valid_email_address($value)) {
    form_error($element, t('Please enter a valid email address.'));
  }
}
 
 // действия по сабмиту
 public function submitForm(array &$form, FormStateInterface $form_state) {
	$name = $form_state->getValue('name');
	$surname = $form_state->getValue('surname');
	$subject = $form_state->getValue('subject');
	$messege = $form_state->getValue('messege');
	$mail = $form_state->getValue('mail');
	drupal_set_message(t('Вы ввели: %name.', ['%name' => $name]));
	drupal_set_message(t('Вы ввели: %surname.', ['%surname' => $surname]));
	drupal_set_message(t('Вы ввели: %subject.', ['%subject' => $subject]));
	drupal_set_message(t('Вы ввели: %messege.', ['%messege' => $messege]));
	drupal_set_message(t('Вы ввели: %mail.', ['%mail' => $mail]));
 }
 
}
?>