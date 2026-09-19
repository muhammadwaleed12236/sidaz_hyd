<?php

namespace App\Http\Controllers;

use App\Models\DummyInvoice;
use App\Models\DummyInvoiceItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DummyInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = DummyInvoice::with('items')->orderBy('id', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('gate_pass_no', 'like', "%{$search}%");
            });
        }

        $invoices = $query->paginate(15);

        return view('admin_panel.dummy_invoice.index', compact('invoices'));
    }

    public function create()
    {
        $nextInvoiceNo = DummyInvoice::generateNextInvoiceNo();
        $products = Product::where(function($q) {
                $q->whereNull('is_active')->orWhere('is_active', 1);
            })
            ->orderBy('item_name', 'asc')
            ->get(['id', 'item_name', 'product_type', 'wholesale_price', 'sale_price_per_box', 'sale_price_per_piece'])
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'item_name' => $p->item_name,
                    'package_type' => $p->product_type ?? '',
                    'mrp' => (float) ($p->wholesale_price ?? 0),
                    'tp' => (float) ($p->sale_price_per_box ?? 0),
                    'dp' => (float) ($p->sale_price_per_piece ?? 0),
                ];
            });

        $defaultWarranty = "It is herby certified & I (SEEMA KHAN D/O AHMED KHAN) undertakes the above mentioned finished products of specified batch number supplied by me do not contravene any provision of production related DRAP Act, 2012 and rules framed thereunder. The authorized agent (with valid distribution authorized letter) shall pass on this warranty to the retailers in his area of jurisdiction during the supply of medicine and health product.";

        return view('admin_panel.dummy_invoice.create', compact('nextInvoiceNo', 'products', 'defaultWarranty'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_no' => 'required|string|unique:dummy_invoices,invoice_no',
            'invoice_date' => 'required|date',
            'customer_name' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.dp' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = 0;

            $invoice = DummyInvoice::create([
                'invoice_no' => $request->invoice_no,
                'invoice_date' => $request->invoice_date,
                'order_date' => $request->order_date,
                'customer_name' => $request->customer_name,
                'customer_address' => $request->customer_address,
                'gate_pass_no' => $request->gate_pass_no,
                'licence_no' => $request->licence_no,
                'licence_expiry' => $request->licence_expiry,
                'total_amount' => 0,
                'warranty_text' => $request->warranty_text,
                'signatory_name' => $request->signatory_name ?? 'SEEMA KHAN',
                'signatory_title' => $request->signatory_title ?? 'Production Incharge',
                'created_by' => Auth::id(),
            ]);

            foreach ($request->items as $itemData) {
                $qty = (float) ($itemData['qty'] ?? 0);
                $dp = (float) ($itemData['dp'] ?? 0);
                $rowTotal = isset($itemData['total_amount']) && $itemData['total_amount'] > 0 
                    ? (float)$itemData['total_amount'] 
                    : ($qty * $dp);

                $totalAmount += $rowTotal;

                DummyInvoiceItem::create([
                    'dummy_invoice_id' => $invoice->id,
                    'product_id' => $itemData['product_id'] ?? null,
                    'product_name' => $itemData['product_name'],
                    'pack' => $itemData['pack'] ?? null,
                    'batch_no' => $itemData['batch_no'] ?? null,
                    'mfg_date' => $itemData['mfg_date'] ?? null,
                    'exp_date' => $itemData['exp_date'] ?? null,
                    'qty' => $qty,
                    'mrp' => (float) ($itemData['mrp'] ?? 0),
                    'tp' => (float) ($itemData['tp'] ?? 0),
                    'dp' => $dp,
                    'total_amount' => $rowTotal,
                ]);
            }

            $invoice->update(['total_amount' => $totalAmount]);

            DB::commit();

            if ($request->has('print_after_save')) {
                return redirect()->route('dummy-invoices.print', $invoice->id)
                    ->with('success', 'Warranty Sale Invoice created successfully.');
            }

            return redirect()->route('dummy-invoices.index')
                ->with('success', 'Warranty Sale Invoice created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating invoice: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $invoice = DummyInvoice::with('items')->findOrFail($id);
        $products = Product::where(function($q) {
                $q->whereNull('is_active')->orWhere('is_active', 1);
            })
            ->orderBy('item_name', 'asc')
            ->get(['id', 'item_name', 'product_type', 'wholesale_price', 'sale_price_per_box', 'sale_price_per_piece'])
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'item_name' => $p->item_name,
                    'package_type' => $p->product_type ?? '',
                    'mrp' => (float) ($p->wholesale_price ?? 0),
                    'tp' => (float) ($p->sale_price_per_box ?? 0),
                    'dp' => (float) ($p->sale_price_per_piece ?? 0),
                ];
            });

        return view('admin_panel.dummy_invoice.edit', compact('invoice', 'products'));
    }

    public function update(Request $request, $id)
    {
        $invoice = DummyInvoice::findOrFail($id);

        $request->validate([
            'invoice_no' => 'required|string|unique:dummy_invoices,invoice_no,' . $id,
            'invoice_date' => 'required|date',
            'customer_name' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.dp' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = 0;

            $invoice->update([
                'invoice_no' => $request->invoice_no,
                'invoice_date' => $request->invoice_date,
                'order_date' => $request->order_date,
                'customer_name' => $request->customer_name,
                'customer_address' => $request->customer_address,
                'gate_pass_no' => $request->gate_pass_no,
                'licence_no' => $request->licence_no,
                'licence_expiry' => $request->licence_expiry,
                'warranty_text' => $request->warranty_text,
                'signatory_name' => $request->signatory_name ?? 'SEEMA KHAN',
                'signatory_title' => $request->signatory_title ?? 'Production Incharge',
            ]);

            // Delete old items and recreate
            $invoice->items()->delete();

            foreach ($request->items as $itemData) {
                $qty = (float) ($itemData['qty'] ?? 0);
                $dp = (float) ($itemData['dp'] ?? 0);
                $rowTotal = isset($itemData['total_amount']) && $itemData['total_amount'] > 0 
                    ? (float)$itemData['total_amount'] 
                    : ($qty * $dp);

                $totalAmount += $rowTotal;

                DummyInvoiceItem::create([
                    'dummy_invoice_id' => $invoice->id,
                    'product_id' => $itemData['product_id'] ?? null,
                    'product_name' => $itemData['product_name'],
                    'pack' => $itemData['pack'] ?? null,
                    'batch_no' => $itemData['batch_no'] ?? null,
                    'mfg_date' => $itemData['mfg_date'] ?? null,
                    'exp_date' => $itemData['exp_date'] ?? null,
                    'qty' => $qty,
                    'mrp' => (float) ($itemData['mrp'] ?? 0),
                    'tp' => (float) ($itemData['tp'] ?? 0),
                    'dp' => $dp,
                    'total_amount' => $rowTotal,
                ]);
            }

            $invoice->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('dummy-invoices.index')
                ->with('success', 'Warranty Sale Invoice updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating invoice: ' . $e->getMessage());
        }
    }

    public function print($id)
    {
        $invoice = DummyInvoice::with('items')->findOrFail($id);
        return view('admin_panel.dummy_invoice.print', compact('invoice'));
    }

    public function destroy($id)
    {
        $invoice = DummyInvoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('dummy-invoices.index')
            ->with('success', 'Warranty Sale Invoice deleted successfully.');
    }
}
