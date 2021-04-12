<?php
namespace App\Validator;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class ContainsAlphanumeric extends Constraint{
    public $message = 'Der Titel "{{ string }}" enthält ein unzulässiges Zeichen: Es darf nur Buchstaben oder Zahlen enthalten.';
}