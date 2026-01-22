<?php

namespace App\Http\Controllers;

use App\Models\ProductTransaction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransactionReportDatatableController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->ajax()) {
            $query = ProductTransaction::with(['product', 'user'])
                ->select('product_transactions.*');

            // Filter by type if provided
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            // Filter by product if provided
            if ($request->filled('product_id')) {
                $query->where('product_id', $request->product_id);
            }

            // Filter by date range if provided
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('product_name', fn($row) => $row->product->name ?? '-')
                ->addColumn('user_name', fn($row) => $row->user->name ?? '-')
                ->addColumn('qty_in', fn($row) => $row->qty_in > 0 ? '+' . number_format($row->qty_in) : '-')
                ->addColumn('qty_out', fn($row) => $row->qty_out > 0 ? '-' . number_format($row->qty_out) : '-')
                ->addColumn('type_badge', function ($row) {
                    $badges = [
                        'purchase' => 'bg-success',
                        'sale' => 'bg-primary',
                        'stock_opname' => 'bg-warning',
                        'adjustment' => 'bg-info',
                    ];
                    $class = $badges[$row->type] ?? 'bg-secondary';
                    $label = ucfirst(str_replace('_', ' ', $row->type));
                    return "<span class=\"badge {$class}\">{$label}</span>";
                })
                ->addColumn('price_formatted', fn($row) => number_format($row->price, 0, ',', '.'))
                ->addColumn('total_price_formatted', fn($row) => number_format($row->total_price, 0, ',', '.'))
                ->addColumn('created_at_formatted', fn($row) => $row->created_at->format('d M Y H:i'))
                ->addColumn('note', fn($row) => $row->note ?? '-')
                ->rawColumns(['type_badge'])
                ->orderColumn('created_at', function ($query, $order) {
                    $query->orderBy('created_at', $order);
                })
                ->make(true);
        }
    }
}
