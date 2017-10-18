<?php

namespace Nicklas\Comment\Modules;

/**
 * A database driven model.
 */
class User extends ActiveRecordModelExtender
{

        /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "ramverk1_users";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $name;
    public $email;
    public $pass;
    public $authority = "user";
    public $question;

    /**
     * Check if user exists
     *
     * @param string $name
     *
     * @return boolean true if user exists in database else false
     */
    public function userExists($name)
    {
        $user = $this->find("name", $name);
        return !$user ? false : true;
    }
    /**
     * Returns gravatar link
     *
     * @param string $email
     *
     * @return string as gravatar link
     */
    public function setGravatar()
    {
        $this->img = $this->gravatar($this->email);
    }

    /**
     * Returns gravatar link
     *
     * @param string $name
     *
     * @return string as gravatar link
     */
    public function getGravatar($name)
    {
        $this->find("name", $name);
        return $this->gravatar($this->email);
    }




    /**
     * Set the pass.
     *
     * @param string $pass the pass to use.
     *
     * @return void
     */
    public function setPassword($pass)
    {
        $this->pass = password_hash($pass, PASSWORD_DEFAULT);
    }

    /**
     * Verify the name and the pass, if successful the object contains
     * all details from the database row.
     *
     * @param string $name  name to check.
     * @param string $pass the pass to use.
     *
     * @return boolean true if name and pass matches, else false.
     */
    public function verifyPassword($name, $pass)
    {
        $this->find("name", $name);
        return password_verify($pass, $this->pass);
    }

    /**
     * Verify the name and the anaswer, if successful the object contains
     * all details from the database row.
     *
     * @param string $name  name to check.
     * @param string $answer the answer.
     *
     * @return boolean true if name and pass matches, else false.
     */
    public function verifyQuestion($name, $answer)
    {
        $this->find("name", $name);
        return ($this->question == $answer);
    }


    /**
    * Check if a post belongs to user
    *
    *
    * @return boolean
    */
    public function controlAuthority($loggedUser, $name)
    {
        $this->find("name", $loggedUser);
        // IF AUTHORITY == admin, then continue
        if ($this->authority != "admin") {
            return ($this->name == $name);
        }
        return true;
    }
}
