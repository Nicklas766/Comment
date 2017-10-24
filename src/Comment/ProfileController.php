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
        return $user->getUser($name);
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
            ["comment/user/profile/profile", ["user" => $user], "main"]
        ];

        if ($user->authority == "admin") {
            $views = [
                ["comment/admin/navbar", [], "main"],
                ["comment/user/profile/profile", ["user" => $user], "main"]
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
     *
     * @return void
     */
    public function getPostEditUser()
    {
        $this->checkIsLogin();
        $name = $this->di->get('session')->get("user");
        $user = $this->getUserDetails($name);

        $form       = new EditProfileForm($this->di, $name);
        $form->check();

        $views = [
            ["comment/user/profile/edit", ["form" => $form->getHTML(), "user" => $user], "main"]
        ];

        $this->di->get("pageRenderComment")->renderPage([
            "views" =>  $views,
            "title" => "Edit Profile"
        ]);
    }

    /**
     * Description.
     *
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
            ["comment/user/profile/edit", ["form" => $form->getHTML(), "user" => $user], "main"]
        ];

        $this->di->get("pageRenderComment")->renderPage([
            "views" =>  $views,
            "title" => "Edit Profile"
        ]);
    }
}
