<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PledgeRequest;
use App\Models\Collection;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PledgeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PledgeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Pledge::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/pledge');
        CRUD::setEntityNameStrings('pledge', 'pledges');
        CRUD::enableExportButtons();

        if(backpack_user()->hasRole('member')){
            CRUD::addClause('whereUserId', backpack_user()->id);
        }
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // CRUD::setFromDb(); // columns

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */

        // CRUD::column('amount')->type('text');

        CRUD::addColumn(['name' => 'amount', 'label' => 'Amount Pledge', 'type' => 'model_function', 'function_name' => 'getAmountPledgeFormatAttribute']);
        CRUD::addColumn(['name' => 'collection', 'label' => 'Project Name', 'type' => 'model_function', 'function_name' => 'getCollectionNameAttribute']);
        CRUD::addColumn(['name' => 'user', 'label' => 'Name', 'type' => 'model_function', 'function_name' => 'getUserFullNameAttribute']);
        CRUD::addColumn(['name' => 'created_at', 'label' => 'Date Pledged',]);

        // CRUD::orderBy("created_at",'desc');
        CRUD::addFilter([
          'type'  => 'date_range',
          'name'  => 'from_to',
          'label' => 'Date range'
        ],
        false,
        function ($value) { // if the filter is active, apply these constraints
            $dates = json_decode($value);
            $this->crud->addClause('where', 'created_at', '>=', $dates->from);
            $this->crud->addClause('where', 'created_at', '<=', $dates->to . ' 23:59:59');
        });
        CRUD::addFilter([
            'name' => 'user_id',
            'type' => 'select2',
            'label' => 'User',
        ], function(){
            // if(backpack_user()->hasRole('staff') && backpack_user()->caregiver!==null) {
            //     return User::where('clinic_id', backpack_user()->caregiver->clinic_id)->get()->pluck('full_name', 'id')->toArray();
            // }
            return User::all()->pluck('full_name', 'id')->toArray();
        }, function($value){
            $this->crud->addClause('where', 'user_id', $value);
        });

        CRUD::addFilter([
            'name' => 'collection_id',
            'type' => 'select2',
            'label' => 'Project',
        ], function(){
            // if(backpack_user()->hasRole('staff') && backpack_user()->caregiver!==null) {
            //     return User::where('clinic_id', backpack_user()->caregiver->clinic_id)->get()->pluck('full_name', 'id')->toArray();
            // }
            return Collection::all()->pluck('name', 'id')->toArray();
        }, function($value){
            $this->crud->addClause('where', 'collection_id', $value);
        });
        // $this->crud->orderBy('created_at', 'desc');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PledgeRequest::class);

        // CRUD::setFromDb(); // fields

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
        CRUD::addField([
            'name' => 'amount',
            'type' => 'number',
            'label' => "Pledge Amount",

        ]);
        CRUD::addField([
            'label' => "Project",
            'type' => 'select2',
            'name' => 'collection_id',
            'entity' => 'collection',
            'attribute' => 'name',
            'model' => "App\Models\Collection",

            'options'   => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            }),
        ]);

        if (backpack_user()->hasRole('admin')) {
            CRUD::addField([
                'label' => "User",
                'type' => 'select2',
                'name' => 'user_id',
                'entity' => 'user',
                'attribute' => 'full_name',
                'model' => "App\Models\User",
                'options'   => (function ($query) {
                    return $query->orderBy('last_name', 'ASC')->get();
                }),
            ]);
        }else{
            // CRUD::addField([
            //     'label' => "User",
            //     'type' => 'select2',
            //     'name' => 'user_id',
            //     'entity' => 'user',
            //     'attribute' => 'full_name',
            //     'model' => "App\Models\User",
            //     'options'   => (function ($query) {
            //         return $query->orderBy('last_name', 'ASC')->get();
            //     }),
            // ]);
            CRUD::addField([
                'name' => 'user_id',
                'type' => 'hidden',
                'value' => backpack_user()->id,
            ]);
        }
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
