<?php

namespace WpifyMapyCzDeps\Wpify\Model\Relations;

use WpifyMapyCzDeps\Wpify\Model\Interfaces\ModelInterface;
use WpifyMapyCzDeps\Wpify\Model\Interfaces\PostModelInterface;
use WpifyMapyCzDeps\Wpify\Model\Interfaces\PostRepositoryInterface;
use WpifyMapyCzDeps\Wpify\Model\Interfaces\RelationInterface;
use WpifyMapyCzDeps\Wpify\Model\Interfaces\RepositoryInterface;
use WpifyMapyCzDeps\Wpify\Model\Order;
use WpifyMapyCzDeps\Wpify\Model\OrderItemRepository;
class OrderItemsRelation implements RelationInterface
{
    /** @var Order */
    private $model;
    /** @var OrderItemRepository */
    private $repository;
    private $type;
    /**
     * TermRelation constructor.
     *
     * @param PostModelInterface      $model
     * @param PostRepositoryInterface $repository
     */
    public function __construct(ModelInterface $model, RepositoryInterface $repository, string $type = 'line_item')
    {
        $this->model = $model;
        $this->repository = $repository;
        $this->type = $type;
    }
    public function fetch()
    {
        $items = [];
        foreach ($this->model->source_object()->get_items($this->type) as $item) {
            $items[] = $this->repository->get($item);
        }
        return $items;
    }
    public function assign()
    {
    }
}
