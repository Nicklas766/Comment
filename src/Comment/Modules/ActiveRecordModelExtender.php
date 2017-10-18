<?php

namespace Nicklas\Comment\Modules;

use \Anax\Database\ActiveRecordModel;
use \Nicklas\Comment;

use \Anax\TextFilter\TextFilter;

/**
 * A database driven model.
 */
class ActiveRecordModelExtender extends ActiveRecordModel
{

    public $db;
    /**
     * Constructor injects with database
     *
     */
     public function __construct($db = null)
     {
         $this->db = $db;
     }
    /**
     * Returns gravatar link
     *
     * @param string $email
     *
     * @return string as gravatar link
     */
    public function gravatar($email)
    {
        return "https://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "&s=" . 40;
    }

    /**
     * Return markdown based on string
     *
     * @param string $content unparsed markdown
     *
     * @return string as parsed markdown
     */
    public function getMD($content)
    {
        $funcArr = ["yamlfrontmatter", "shortcode", "markdown", "titlefromheader"];
        $textFilter = new textFilter();
        return $textFilter->parse($content, $funcArr)->text;
    }
}
