<?php

namespace WpifyMapyCzDeps\Wpify\Model;

use WpifyMapyCzDeps\Wpify\Model\Abstracts\AbstractTermModel;
use WpifyMapyCzDeps\Wpify\Model\Interfaces\PostModelInterface;
use WpifyMapyCzDeps\Wpify\Model\Relations\TermPostsRelation;
class PostTag extends AbstractTermModel
{
    /** @var PostModelInterface */
    public $posts;
    protected function posts_relation() : TermPostsRelation
    {
        return new TermPostsRelation($this, 'posts', $this->model_repository()->get_post_repository());
    }
}
