<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class ProductTable extends PowerGridComponent
{
    use ActionButton;

    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }




    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Model or Collection
    |
    */

    /**
     * PowerGrid datasource.
     *
     * @return Builder<\App\Models\Product>
     */
    public function datasource(): Builder
    {
        return Product::query();
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */

    /**
     * Relationship search.
     *
     * @return array<string, array<int, string>>
     */
    public function relationSearch(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    */
    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('created_at_formatted', fn (Product $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'))
            ->addColumn('updated_at_formatted', fn (Product $model) => Carbon::parse($model->updated_at)->format('d/m/Y H:i:s'))
            ->addColumn('price', fn (Product $model) => '₹ ' . $model->price);
    }

    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    | Include the columns added columns, making them visible on the Table.
    | Each column can be configured with properties, filters, actions...
    |
    */

    /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->makeInputRange(),
            Column::make('Name', 'name')
                ->searchable()
                ->sortable()
                ->makeInputText(),

            Column::make('Price', 'price')
                ->searchable()
                ->sortable()
                ->makeInputText(),
            Column::make('CREATED AT', 'created_at_formatted', 'created_at')
                ->searchable()
                ->sortable()
                ->makeInputDatePicker(),
            Column::add('action')
                ->title('Edit'),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

    /**
     * PowerGrid Product Action Buttons.
     *
     * @return array<int, Button>
     */


    public function actions(): array
    {
        return [
            Button::make('edit', 'Edit')
                ->class('btn btn-icon rounded-circle btn-outline-primary waves-effect edit-product')
                ->caption('<svg aria-hidden="true" focusable="false" data-prefix="fa-duotone" data-icon="pen-nib" class="svg-inline--fa fa-pen-nib fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><defs><style>.fa-secondary{opacity:.4}</style></defs><g class="fa-group"><path class="fa-primary" d="M497.9 74.19l-60.13-60.13c-18.75-18.75-49.24-18.74-67.98 .0065l-81.87 81.98l127.1 127.1l81.98-81.87C516.7 123.4 516.7 92.94 497.9 74.19z" fill="currentColor"/><path class="fa-secondary" d="M136.6 138.8c-20.37 5.749-36.62 21.25-43.37 41.37L0 460l14.75 14.75l149.1-150.1c-2.1-6.249-4.749-13.25-4.749-20.62c0-26.5 21.5-47.99 47.99-47.99s47.99 21.5 47.99 47.99s-21.5 47.99-47.99 47.99c-7.374 0-14.37-1.75-20.62-4.749l-150.1 149.1L51.99 512l279.8-93.24c20.12-6.749 35.62-22.1 41.37-43.37l42.75-151.4l-127.1-127.1L136.6 138.8z" fill="currentColor"/></g></svg>')
                ->emit('edit', [
                    'id' => 'id',
                    'route' => route('admin.product.edit'),
                    'offcanvas' => '#edit-product',
                ]),


            Button::make('destroy', 'Delete')
                ->class('btn btn-icon rounded-circle btn-outline-danger waves-effect ')
                ->caption('<svg aria-hidden="true" focusable="false" data-prefix="fa-duotone" data-icon="trash-can" class="svg-inline--fa fa-trash-can fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><defs><style>.fa-secondary{opacity:.4}</style></defs><g class="fa-group"><path class="fa-primary" d="M432 32H320l-11.58-23.16c-2.709-5.42-8.25-8.844-14.31-8.844H153.9c-6.061 0-11.6 3.424-14.31 8.844L128 32H16c-8.836 0-16 7.162-16 16V80c0 8.836 7.164 16 16 16h416c8.838 0 16-7.164 16-16V48C448 39.16 440.8 32 432 32z" fill="currentColor"/><path class="fa-secondary" d="M32 96v368C32 490.5 53.5 512 80 512h288c26.5 0 48-21.5 48-48V96H32zM144 416c0 8.875-7.125 16-16 16S112 424.9 112 416V192c0-8.875 7.125-16 16-16S144 183.1 144 192V416zM240 416c0 8.875-7.125 16-16 16S208 424.9 208 416V192c0-8.875 7.125-16 16-16s16 7.125 16 16V416zM336 416c0 8.875-7.125 16-16 16s-16-7.125-16-16V192c0-8.875 7.125-16 16-16s16 7.125 16 16V416z" fill="currentColor"/></g></svg>')
                ->emit('delete', ['id' => 'id']),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

    /**
     * PowerGrid Product Action Rules.
     *
     * @return array<int, RuleActions>
     */


    public function actionRules(): array
    {
        return [

            //Hide button edit for ID 1
            // Rule::button('edit')
            //     ->setAttribute('data-route', route('admin.product.edit', ['product' => 'id']))
        ];
    }
}
