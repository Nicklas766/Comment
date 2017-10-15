<?php
namespace Nicklas\Comment\HTMLForm\User;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Nicklas\Comment\Modules\User;

/**
 * Example of FormModel implementation.
 */
class UserResetForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
                "br-after-label" => false,
                "use_fieldset" => false,
                "wrapper-element" => "div",
            ],
            [
                "user" => [
                    "type"        => "text",
                    //"description" => "Here you can place a description.",
                    "placeholder" => "Användarnamn",
                    "validation" => ["not_empty"],
                    "label" => false,
                ],
                "answer" => [
                    "type"        => "text",
                    "description" => "Vad är din favorträtt?",
                    "placeholder" => "Kontrollfråga",
                    "validation" => ["not_empty"],
                    "label" => false,
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Svara",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }
    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit()
    {
        // Get values from the submitted form
        $name       = $this->form->value("user");
        $answer      = $this->form->value("answer");
        $user = new User();
        $user->setDb($this->di->get("db"));
        $res = $user->verifyQuestion($name, $answer);
        if ($res) {
            $this->form->rememberValues();
            $random = substr(md5(mt_rand()), 0, 7);
            $this->form->addOutput("Rätt! Ditt lösenord har ändrats till <b>'{$random}'</b>.
             Ange det när du loggar in och byt sedan till valfritt i din profilsida.");
            $user->setPassword($random);
            $user->save();
            return true;
        }
        $this->form->rememberValues();
        $this->form->addOutput("Du hade fel, kontrollera så du inte skriver med stora bokstäver");
        return false;
    }
}
