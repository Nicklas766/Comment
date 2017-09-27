Anax comment
==================================

[![Latest Stable Version](https://poser.pugx.org/anax/comment/v/stable)](https://packagist.org/packages/anax/comment)
[![Join the chat at https://gitter.im/mosbth/anax](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/canax?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Build Status](https://travis-ci.org/canax/comment.svg?branch=master)](https://travis-ci.org/canax/comment)
[![CircleCI](https://circleci.com/gh/canax/comment.svg?style=svg)](https://circleci.com/gh/canax/comment)
[![Build Status](https://scrutinizer-ci.com/g/canax/comment/badges/build.png?b=master)](https://scrutinizer-ci.com/g/canax/comment/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/canax/comment/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/canax/comment/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/canax/comment/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/canax/comment/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d831fd4c-b7c6-4ff0-9a83-102440af8929/mini.png)](https://insight.sensiolabs.com/projects/d831fd4c-b7c6-4ff0-9a83-102440af8929)

Anax comment module.



Usage
------------------

Short examples on how to use the module comment.



Setup
------------------
composer require nicklas/comment

Make sure you start inserting the following code in your pagerender.

```
<?php

    public function add($data)
    {
        // if multiple views create indexes for them
        if (is_array($data[0])) {
            return array_map(function ($val) use ($data) {
                return ["view" => $val, "content" => $data[1], "region" => $data[2]];
            }, $data[0]);
        }
        // if multiple content (multideminsional) create indexes for them
        if (array_key_exists(0, $data[1])) {
            return array_map(function ($val) use ($data) {
                return ["view" => $data[0], "content" => $val, "region" => $data[2]];
            }, $data[1]);
        }
        return [["view" => $data[0], "content" => $data[1], "region" => $data[2]]];
    }

    public function setArray($array, $key)
    {
        return array_map(function ($val) use ($key) {
            return ["$key" => "$val"];
        }, $array);
    }

    /**
     * Render a standard web page using a specific layout.
     */
    public function viewify($views)
    {
        foreach ($views as $views) {
            foreach ($this->add($views) as $view) {
                // print_r($view["content"]);
                $this->di->get("view")->add($view["view"], $view["content"], $view["region"]);
            }
        }
    }

    /**
     * Render a standard web page using a specific layout.
     *
     * @param array   $data   variables to expose to layout view.
     * @param integer $status code to use when delivering the result.
     *
     * @return void
     */
    public function renderPage($data, $status = 200)
    {
        // get view class
        $view = $this->di->get("view");
        // creates the views with viewify function
        array_key_exists("views", $data) && $this->viewify($data["views"]);

        $data["stylesheets"] = ["css/style.css"];
        $data["javascripts"] = ["js/index.js"];


        // Add layout, render it, add to response and send.
        $view->add("default1/layout", $data, "layout");
        $body = $view->renderBuffered("layout");
        $this->di->get("response")->setBody($body)
                                  ->send($status);
        exit;
    }
}

```



License
------------------

This software carries a MIT license.



```
 .  
..:  Copyright (c) 2017 Nicklas Envall (Nicklas766@live.se)
```
