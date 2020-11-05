<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MemberRequest;
use App\Models\Member;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\PermissionManager\app\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * Class MemberCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MemberCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
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
        CRUD::setModel(\App\Models\Member::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/member');
        CRUD::setEntityNameStrings('member', 'members');
        // CRUD::denyAccess('create');
        CRUD::enableExportButtons();
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
        CRUD::column('first_name')->type('text');
        CRUD::column('last_name')->type('text');
        CRUD::column('contact_number')->type('text');
        CRUD::addColumn(['name' => 'leader_id', 'label' => 'Leader', 'type' => 'model_function', 'function_name' => 'getLeaderNameAttribute']);
        CRUD::addColumn(['name' => 'location_id', 'label' => 'Location', 'type' => 'model_function', 'function_name' => 'getLocationAttribute']);
        // CRUD::column('location_id')->type('model_function')->function_name('getLocationAttribute');
        // CRUD::columns(
        //     [
        //         'name' => 'first_name',
        //         'label' => "First Name",
        //         'type' => 'text',
        //         'visibleInTable' => false,
        //         'visibleInShow' => false,
        //     ],
        //     [
        //         'name' => 'last_name',
        //         'label' => "Last Name",
        //         'type' => 'text',
        //         'visibleInTable' => false,
        //         'visibleInShow' => false,
        //     ],
        // );
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(MemberRequest::class);

        // CRUD::setFromDb(); // fields
        CRUD::addField([
            'name' => 'first_name',
            'type' => 'text',
            'label' => "First name",

        ]);
        CRUD::addField([
            'name' => 'last_name',
            'type' => 'text',
            'label' => "Last name",

        ]);

        CRUD::addField([
            'name'  => 'email',
            'label' => trans('backpack::permissionmanager.email'),
            'type'  => 'email',

        ]);

        CRUD::addField([
            'name' => 'contact_number',
            'type' => 'text',
            'label' => "Contact Number",

        ]);

        CRUD::addField([
            'name' => 'birthdate',
            'type' => 'date',
            'label' => "Birthday",

        ]);

        CRUD::addField([
            'name' => 'address',
            'type' => 'textarea',
            'label' => "Address",

        ]);
        CRUD::addField([
            'label' => "Location",
            'type' => 'select2',
            'name' => 'location_id',
            'entity' => 'location',
            'attribute' => 'name',
            'model' => "App\Models\Location",
            'options'   => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            }),
        ]);
        CRUD::addField([
            'label' => "Congregational Leader",
            'type' => 'select2',
            'name' => 'leader_id',
            'entity' => 'leader',
            'attribute' => 'full_name',
            'model' => "App\Models\Leader",
            'options'   => (function ($query) {
                return $query->orderBy('last_name', 'ASC')->get();
            }),
        ]);
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
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

    public function store()
    {
        $this->crud->setRequest($this->crud->validateRequest());
        $this->crud->unsetValidation(); // validation has already been run
        // Log::info($this->crud->getRequest()->location_id);
        $locationId = $this->crud->getRequest()->location_id;
        $result = $this->traitStore();
        if(($result)){
            Log::info($locationId);
            $user = User::create([
                'location_id' => intval($locationId),
                'first_name' => $this->crud->getCurrentEntry()->first_name,
                'last_name' => $this->crud->getCurrentEntry()->last_name,
                'email' => $this->crud->getCurrentEntry()->email,
                'contact_number' => $this->crud->getCurrentEntry()->contact_number,
                'address' => $this->crud->getCurrentEntry()->address,
                'birthdate' => $this->crud->getCurrentEntry()->birthdate,
                'password' => Hash::make('secret'),
            ]);//->assignRole('member');
            $user->roles()->attach(2);
            Member::find($this->crud->getCurrentEntry()->id)->update(['user_id' => $user->id]);
        }

        return $result;
    }
}
