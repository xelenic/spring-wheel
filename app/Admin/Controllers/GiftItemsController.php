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
        $grid->column('qty', 'Available Qty')->sortable();
        $grid->column('per_day', 'Odds Weight')->sortable();
        $grid->column('remaining_qty', 'Given Away')->sortable();
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
        $show->field('qty', 'Available Qty');
        $show->field('per_day', 'Odds Weight');
        $show->field('remaining_qty', 'Given Away');
        $show->field('created_at', 'Added At');
        $show->field('updated_at', 'Updated At');

        return $show;
    }

    protected function form()
    {
        $form = new Form(new GiftItems());

        $form->text('gift_items', 'Gift Name')->rules('required');
        $form->number('qty', 'Available Qty')
            ->rules('required|integer|min:0')
            ->default(0)
            ->help('Stock left to give away. Decreases by 1 each time this gift is won; set to 0 to pull it out of the draw without deleting it.');
        $form->number('per_day', 'Odds Weight')
            ->rules('required|integer|min:0')
            ->default(0)
            ->help('Used to weight this gift against the others when a spin picks a winner. Higher = more likely to be picked.');
        $form->number('remaining_qty', 'Given Away')
            ->rules('required|integer|min:0')
            ->default(0)
            ->help('Running count of how many of this gift have been won so far. Usually left alone — the spin endpoint increments it automatically.');

        return $form;
    }
}
