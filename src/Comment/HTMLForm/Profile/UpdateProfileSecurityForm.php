<?php
namespace Nicklas\Comment\HTMLForm\Profile;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Nicklas\Comment\Modules\User;

/**
 * Example of FormModel implementation.
 */
class UpdateProfileSecurityForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di)
    {
        parent::__construct($di);
        $this->user = new User();
        $this->user->setDb($this->di->get("db"));
        $this->user = $this->user->find("name", $this->di->get("session")->get("user"));
        $this->form->create(
            [
                "id" => __CLASS__,
                "br-after-label" => false,
                "wrapper-element" => "div",
                "legend" => "Ändra ditt lösenord"
            ],
            [
                "answer" => [
                    "type"        => "text",
                    "value" => "{$this->user->question}",
                    "label" => "Kontrollfråga",
                    "validation" => ["not_empty"],
                ],
                "password" => [
                    "type"        => "password",
                    "label" => "Lösenord",
                ],
                "password-again" => [
                    "type"        => "password",
                    "label" => "Lösenord igen"
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Ändra lösenord",
                    "callback" => [$this, "callbackSubmit"],
                ],
                "submit2" => [
                    "type" => "submit",
                    "value" => "Ändra kontrollfråga",
                    "callback" => [$this, "callbackQuestion"],
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
        $password      = $this->form->value("password");
        $passwordAgain = $this->form->value("password-again");

        // Check password matches
        if ($password == null) {
            $this->form->rememberValues();
            $this->form->addOutput("Du angav aldrig ett lösenord");
            return false;
        }

        // Check password matches
        if ($password !== $passwordAgain) {
            $this->form->rememberValues();
            $this->form->addOutput("Lösenordet matchade ej");
            return false;
        }

        $this->user->setPassword($password);
        $this->user->save();
        $this->form->addOutput("<p>Du ändrade ditt lösenord!</p>
        <p>TIPS:Innan du loggar ut, kontrollera så din kontrollfråga stämmer ifall du glömmer bort</p>");
        return true;
    }

    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackQuestion()
    {
        // Get values from the submitted form
        $this->user->question = $this->form->value("answer");
        $this->form->addOutput("Du har ändrat svaret på din kontrollfråga till `{$this->user->question}`");
        $this->user->save();
        return true;
    }
}
