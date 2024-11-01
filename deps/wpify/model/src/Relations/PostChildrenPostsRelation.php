<?php

namespace WpifyMapyCzDeps\Wpify\Model\Relations;

use WpifyMapyCzDeps\Wpify\Model\Interfaces\PostModelInterface;
use WpifyMapyCzDeps\Wpify\Model\Interfaces\PostRepositoryInterface;
use WpifyMapyCzDeps\Wpify\Model\Interfaces\RelationInterface;
class PostChildrenPostsRelation implements RelationInterface
{
    /** @var PostModelInterface */
    private $model;
    /** @var PostRepositoryInterface */
    private $repository;
    /**
     * TermRelation constructor.
     *
     * @param PostModelInterface $model
     * @param PostRepositoryInterface $repository
     */
    public function __construct(PostModelInterface $model, PostRepositoryInterface $repository)
    {
        $this->model = $model;
        $this->repository = $repository;
    }
    public function fetch()
    {
        $args = ['post_parent' => $this->model->id];
        return $this->repository->find($args);
    }
    public function assign()
    {
    }
}
