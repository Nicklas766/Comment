<?php

namespace Nicklas\Comment;

use \Anax\Database\ActiveRecordModel;
use \Nicklas\Comment;

/**
 * A database driven model.
 */
class ActiveRecordModelExtender extends ActiveRecordModel
{
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
        return $this->di->get('textfilter')->parse($content, $funcArr)->text;
    }
}
