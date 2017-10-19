<?php

namespace Nicklas\Comment;

use \Nicklas\Comment\HTMLForm\Profile\EditProfileForm;
use \Nicklas\Comment\HTMLForm\Profile\UpdateProfileSecurityForm;
use \Nicklas\Comment\Modules\User;

/**
 * A controller class.
 */
class ProfileController extends LoginController
{

    /**
     * Get details on item to load form with.
     *
     * @param integer $id get details on item with id.
     *
     * @return object true if okey, false if something went wrong.
     */
    public function getUserDetails($name)
    {
        $user = new User($this->di->get("db"));
        $user->find("name", $name);
        $user->setGravatar();
        return $user;
    }

    /**
     * Render profile page
     *
     * @return void
     */
    public function renderProfile()
    {
        $this->checkIsLogin();

        $name = $this->di->get('session')->get("user");
        $user = $this->getUserDetails($name);


        $views = [
            ["user/profile/profile", ["user" => $user], "main"]
        ];

        if ($user->authority == "admin") {
            $views = [
                ["admin/navbar", [], "main"],
                ["user/profile/profile", ["user" => $user], "main"]
            ];
        }

        $this->di->get("pageRenderComment")->renderPage([
            "views" =>  $views,
            "title" => "$user->name"
        ]);
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return void
     */
    public function getPostEditUser()
    {
        $name = $this->di->get('session')->get("user");
        $user = $this->getUserDetails($name);

        $form       = new EditProfileForm($this->di, $name);
        $form->check();

        $views = [
            ["user/profile/edit", ["form" => $form->getHTML(), "user" => $user], "main"]
        ];

        $this->di->get("pageRenderComment")->renderPage([
            "views" =>  $views,
            "title" => "Edit Profile"
        ]);
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return void
     */
    public function getPostEditSecurity()
    {
        $name = $this->di->get('session')->get("user");
        $user = $this->getUserDetails($name);

        $form       = new UpdateProfileSecurityForm($this->di);
        $form->check();

        $views = [
            ["user/profile/edit", ["form" => $form->getHTML(), "user" => $user], "main"]
        ];

        $this->di->get("pageRenderComment")->renderPage([
            "views" =>  $views,
            "title" => "Edit Profile"
        ]);
    }
}
