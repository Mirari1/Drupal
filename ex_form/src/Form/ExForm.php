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
			'#maxlength' => 255,
		];
	  
		$form['mail'] = [
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
		$name = $form_state->getValue('name');
		$surname = $form_state->getValue('surname');
		$is_number = preg_match("/[\d]+/", $name, $match);
		$email = $form_state->getValue('mail');
		
		if ($is_number > 0) {
			$form_state->setErrorByName('name', $this->t('Строка содержит цифру.'));
			$form_state->setErrorByName('surname', $this->t('Строка содержит цифру.'));
		}
		
		if (!$form_state->getValue('mail') || !filter_var($form_state->getValue('mail'), FILTER_VALIDATE_EMAIL)) {
            $form_state->setErrorByName('mail', $this->t('Votre adresse e-mail semble invalide.'));
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