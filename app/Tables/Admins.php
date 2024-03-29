<?php

namespace App\Tables;

use App\Models\Admin;
use Illuminate\Http\Request;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\Facades\Toast;

class Admins extends AbstractTable
{
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the user is authorized to perform bulk actions and exports.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return true;
    }

    /**
     * The resource or query builder.
     *
     * @return mixed
     */
    public function for()
    {
        return Admin::query();
    }

    /**
     * Configure the given SpladeTable.
     *
     * @param \ProtoneMedia\Splade\SpladeTable $table
     * @return void
     */
    public function configure(SpladeTable $table)
    {
        $table
            ->withGlobalSearch(columns: ['id','name','phone','email','first_name','last_name'])
            ->column('id', sortable: true)
            ->column('first_name', canBeHidden:false , sortable: true)
            ->column('last_name', canBeHidden:false , sortable: true)
            ->column('name', canBeHidden:false , sortable: true)
            ->column('email', sortable: false)
            ->column('phone', sortable: false)
            ->column('gender', sortable: false)
            ->column('date_of_brith', sortable: false)
            ->column('full_control',exportAs:false)
            ->bulkAction(
                label: 'Touch timestamp',
                each: fn (Admin $admin) => $admin->touch(),
                before: fn () => info('Touching the selected admins'),
                after: fn () => Toast::info('Timestamps updated!')
            )
            ->bulkAction(
                label: 'Remove Admins',
                each: fn (Admin $admin) => $admin->delete(),
                before: fn () => info('Remove the selected admins'),
                after: fn () => Toast::info('Admins Removed!')
            )
            ->export()
            ->paginate(20);

            // ->searchInput()
            // ->selectFilter()
            // ->withGlobalSearch()

    }
}
