<?php

namespace WpifyMapyCzDeps\Wpify\Model\Interfaces;

use WpifyMapyCzDeps\Wpify\Model\Abstracts\AbstractPostModel;
interface PostRepositoryInterface extends RepositoryInterface
{
    public function fetch_parent(AbstractPostModel $model);
    public function get_user_repository();
    public function assign_post_to_term(PostModelInterface $model, array $terms, bool $append = \false);
    public function all_by_term(TermModelInterface $term);
}
