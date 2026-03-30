<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockItem;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $items      = StockItem::orderBy('name')->get();
        $lowStock   = StockItem::lowStock()->count();
        $totalValue = $items->sum(fn($i) => $i->quantity * $i->unit_price);

        return view('admin.stock.index', compact('items', 'lowStock', 'totalValue'));
    }

    public function create()
    {
        return view('admin.stock.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'part_number'   => 'nullable|string|unique:stock_items',
            'category'      => 'required|string',
            'quantity'      => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'unit_price'    => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'supplier'      => 'nullable|string',
            'notes'         => 'nullable|string',
        ]);

        /** @var StockItem $item */
        $item = StockItem::create($request->all());

        // Record opening stock transaction
        if ($request->quantity > 0) {
            StockTransaction::create([
                'stock_item_id'   => $item->id,
                'user_id'         => auth()->id(),
                'type'            => 'in',
                'quantity'        => $request->quantity,
                'quantity_before' => 0,
                'quantity_after'  => $request->quantity,
                'unit_price'      => $request->unit_price,
                'notes'           => 'Opening stock',
            ]);
        }

        return redirect()->route('admin.stock.index')
            ->with('success', 'Stock item added.');
    }

    public function edit(StockItem $stock)
    {
        return view('admin.stock.edit', compact('stock'));
    }

    public function update(Request $request, StockItem $stock)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'category'      => 'required|string',
            'reorder_level' => 'required|integer|min:0',
            'unit_price'    => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'supplier'      => 'nullable|string',
            'notes'         => 'nullable|string',
        ]);

        $stock->update($request->except(['quantity', 'part_number']));

        return redirect()->route('admin.stock.index')
            ->with('success', 'Stock item updated.');
    }

    public function destroy(StockItem $stock)
    {
        $stock->delete();

        return redirect()->route('admin.stock.index')
            ->with('success', 'Stock item deleted.');
    }

    /**
     * Add stock (restock)
     */
    public function addStock(Request $request, StockItem $stock)
    {
        $request->validate([
            'quantity'   => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'notes'      => 'nullable|string',
        ]);

        $before = (int) $stock->quantity;
        $after  = $before + (int) $request->quantity;

        $stock->update([
            'quantity'   => $after,
            'unit_price' => $request->unit_price ?? $stock->unit_price,
        ]);

        StockTransaction::create([
            'stock_item_id'   => $stock->id,
            'user_id'         => auth()->id(),
            'type'            => 'in',
            'quantity'        => (int) $request->quantity,
            'quantity_before' => $before,
            'quantity_after'  => $after,
            'unit_price'      => $request->unit_price ?? $stock->unit_price,
            'notes'           => $request->notes ?? 'Stock added',
        ]);

        return back()->with('success', $request->quantity . ' units added to stock.');
    }

    public function transactions(StockItem $stock)
    {
        $transactions = $stock->transactions()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('admin.stock.transactions', compact('stock', 'transactions'));
    }
}