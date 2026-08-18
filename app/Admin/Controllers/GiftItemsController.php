<?php

namespace App\Admin\Controllers;

use App\Models\GiftItems;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

class GiftItemsController extends AdminController
{
    protected $title = 'Gift Items';

    protected function grid()
    {
        $grid = new Grid(new GiftItems());

        $grid->column('id', 'ID')->sortable();
        $grid->column('gift_items', 'Gift Name')->sortable();
        $grid->column('qty', 'Total Qty')->sortable();
        $grid->column('per_day', 'Per Day Limit')->sortable();
        $grid->column('remaining_qty', 'Remaining Qty')->sortable();
        $grid->column('created_at', 'Added At')->sortable();

        $grid->filter(function ($filter) {
            $filter->like('gift_items', 'Gift Name');
            $filter->between('created_at', 'Added At')->datetime();
        });

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(GiftItems::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('gift_items', 'Gift Name');
        $show->field('qty', 'Total Qty');
        $show->field('per_day', 'Per Day Limit');
        $show->field('remaining_qty', 'Remaining Qty');
        $show->field('created_at', 'Added At');
        $show->field('updated_at', 'Updated At');

        return $show;
    }

    protected function form()
    {
        $form = new Form(new GiftItems());

        $form->text('gift_items', 'Gift Name')->rules('required');
        $form->number('qty', 'Total Qty')->rules('required|integer|min:0')->default(0);
        $form->number('per_day', 'Per Day Limit')->rules('required|integer|min:0')->default(0);

        if ($form->isEditing()) {
            $form->number('remaining_qty', 'Remaining Qty')
                ->rules('required|integer|min:0')
                ->help('Stock left to give away. Starts equal to Total Qty when the gift is created.');
        }

        return $form;
    }
}
