<?php namespace LeMaX10\ExtendedSortable\Behaviors;

use October\Rain\Database\SortableScope;
use October\Rain\Extension\ExtensionBase;

class SortableModel extends ExtensionBase
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
        $this->bootSortable();
    }

    /**
     * Boot the sortable trait for this model.
     * @return void
     */
    public function bootSortable()
    {
        $this->model::created(function ($model) {
            $sortOrderColumn = $model->getSortOrderColumn();

            if (is_null($model->$sortOrderColumn)) {
                $model->setSortableOrder($model->getKey());
            }
        });

        $this->model->addGlobalScope(new SortableScope);
    }

    /**
     * Sets the sort order of records to the specified orders. If the orders is
     * undefined, the record identifier is used.
     * @param  mixed $itemIds
     * @param  array $itemOrders
     * @return void
     */
    public function setSortableOrder($itemIds, $itemOrders = null)
    {
        if (!is_array($itemIds)) {
            $itemIds = [$itemIds];
        }

        if ($itemOrders === null) {
            $itemOrders = $itemIds;
        }

        if (count($itemIds) !== count($itemOrders)) {
            throw new \Exception('Invalid setSortableOrder call - count of itemIds do not match count of itemOrders');
        }

        foreach ($itemIds as $index => $id) {
            $order = $itemOrders[$index];
            $this->model->newQuery()
                ->where($this->model->getKeyName(), $id)
                ->update([
                    $this->getSortOrderColumn() => $order
                ]);
        }
    }

    /**
     * Get the name of the "sort order" column.
     * @return string
     */
    public function getSortOrderColumn()
    {
        return defined($this->model . '::SORT_ORDER') ? $this->model::SORT_ORDER : 'sort_order';
    }
}
