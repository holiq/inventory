<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StockReportDatatableController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->ajax()) {
            $query = Product::with('productStock')->select('products.*');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('product_name', fn($row) => $row->name)
                ->addColumn('qty_in', fn($row) => optional($row->productStock)->qty_in ?? 0)
                ->addColumn('qty_out', fn($row) => optional($row->productStock)->qty_out ?? 0)
                ->addColumn('current_stock', fn($row) => optional($row->productStock)->current_stock ?? 0)
                ->addColumn('updated_at', function ($row) {
                    $updated = optional($row->productStock)->updated_at;
                    return $updated ? $updated->format('d M Y H:i') : '-';
                })
                ->make(true);
        }
    }
}
