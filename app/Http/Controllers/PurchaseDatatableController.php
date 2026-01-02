<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PurchaseDatatableController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->ajax()) {
            $data = Purchase::with('supplier')->select('purchases.*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('supplier_name', function ($row) {
                    return $row->supplier->name ?? '-';
                })
                ->addColumn('description', function ($row) {
                    return str($row->description)->limit(40);
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y H:i');
                })
                ->addColumn('actions', function ($row) {
                    $routeShow = route('purchases.show', $row->id);
                    $routeEdit = route('purchases.edit', $row->id);

                    return <<<HTML
                    <div class="btn-group" role="group">
                        <a href="{$routeShow}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
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
