<?php

namespace App\Admin\Controllers;

use App\Models\Customer;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

class SpinHistoryController extends AdminController
{
    protected $title = 'Spin History';

    protected function grid()
    {
        $grid = new Grid(new Customer());

        $grid->column('id', 'ID')->sortable();
        $grid->column('name', 'Email / Name')->sortable();
        $grid->column('phone_number', 'Phone Number');
        $grid->column('spined_gift_item', 'Prize Won');
        $grid->column('created_at', 'Spun At')->sortable();

        $grid->disableCreateButton();
        $grid->disableEditButton();
        $grid->disableDeleteButton();

        $grid->exporter('csv');

        $grid->filter(function ($filter) {
            $filter->like('name', 'Email / Name');
            $filter->like('phone_number', 'Phone Number');
            $filter->like('spined_gift_item', 'Prize Won');
            $filter->between('created_at', 'Spun At')->datetime();
        });

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(Customer::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('name', 'Email / Name');
        $show->field('phone_number', 'Phone Number');
        $show->field('spined_gift_item', 'Prize Won');
        $show->field('created_at', 'Spun At');

        return $show;
    }
}
