<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerDatatableController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->ajax()) {
            $data = Customer::select('*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('address', function ($row) {
                    return str($row->address)->limit(50);
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y');
                })
                ->addColumn('actions', function ($row) {
                    $routeEdit = route('customers.edit', $row->id);

                    return <<<HTML
                    <div class="btn-group" role="group">
                        <a href="{$routeEdit}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button type="button"
                                class="btn btn-sm btn-danger delete-btn"
                                data-id="{$row->id}"
                                data-name="{$row->name}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    HTML;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }
}
