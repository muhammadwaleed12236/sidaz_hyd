<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialPurchase;
use App\Models\MaterialPurchaseItem;
use App\Models\MaterialStockMovement;
use App\Models\RawMaterial;
use App\Models\PackagingMaterial;
use App\Models\Vendor;
use App\Models\VendorLedger;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use App\Services\JournalEntryService;
use App\Services\BalanceService;

class MaterialPurchaseController extends Controller
{
    public function index()
    {
        $purchases = MaterialPurchase::with('vendor')->orderBy('id', 'desc')->get();
        return view('admin_panel.material_purchases.index', compact('purchases'));
    }

    public function create()
    {
        $vendors = Vendor::where('status', 1)->get();
        // Get materials
        $rawMaterials = RawMaterial::with('unit')->where('status', 1)->get();
        $packagingMaterials = PackagingMaterial::with('unit')->where('status', 1)->get();
        
        $accounts = Account::whereHas('head', function($q) {
            $q->whereIn('name', ['Cash', 'Bank']);
        })->where('status', 1)->orderBy('title')->get();
        
        $lastInvoice = MaterialPurchase::latest('id')->value('invoice_no');
        $nextInvoice = $lastInvoice
            ? 'MPUR-'.str_pad(((int) preg_replace('/[^0-9]/', '', $lastInvoice)) + 1, 3, '0', STR_PAD_LEFT)
            : 'MPUR-001';

        return view('admin_panel.material_purchases.create', compact('vendors', 'rawMaterials', 'packagingMaterials', 'accounts', 'nextInvoice'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'purchase_date' => 'required|date',
            'purchase_type' => 'required|string',
            'payment_method' => 'required|string',
            'payment_status' => 'required|string',
            
            'item_type' => 'required|array',
            'item_id' => 'required|array',
            'qty' => 'required|array',
            'unit_price' => 'required|array',
            'discount' => 'nullable|array',
            'tax' => 'nullable|array',
            'batch_no' => 'nullable|array',
            'mfg_date' => 'nullable|array',
            'exp_date' => 'nullable|array',
            
            'transport_charges' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'invoice_status' => 'required|in:draft,completed'
        ]);

        try {
            DB::beginTransaction();

            $lastInvoice = MaterialPurchase::latest('id')->value('invoice_no');
            $nextInvoice = $lastInvoice
                ? 'MPUR-'.str_pad(((int) preg_replace('/[^0-9]/', '', $lastInvoice)) + 1, 3, '0', STR_PAD_LEFT)
                : 'MPUR-001';

            $totalSubtotal = 0;
            $totalDiscount = 0;
            $totalTax = 0;
            $totalAmount = 0;
            $transportCharges = $request->transport_charges ?? 0;
            $paidAmount = $request->paid_amount ?? 0;

            // 1. Create Header
            $purchase = MaterialPurchase::create([
                'invoice_no' => $request->invoice_no ?? $nextInvoice,
                'purchase_date' => $request->purchase_date,
                'vendor_id' => $request->vendor_id,
                'purchase_type' => $request->purchase_type,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status,
                'transport_name' => $request->transport_name,
                'driver_name' => $request->driver_name,
                'driver_contact' => $request->driver_contact,
                'vehicle_no' => $request->vehicle_no,
                'transport_charges' => $transportCharges,
                'remarks' => $request->remarks,
                'subtotal' => 0,
                'total_discount' => 0,
                'total_tax' => 0,
                'total_amount' => 0,
                'paid_amount' => $paidAmount,
                'balance_amount' => 0,
                'status' => $request->invoice_status
            ]);

            // 2. Process Items
            for ($i = 0; $i < count($request->item_id); $i++) {
                $qty = (float)$request->qty[$i];
                $price = (float)$request->unit_price[$i];
                $discount = isset($request->discount[$i]) ? (float)$request->discount[$i] : 0;
                $tax = isset($request->tax[$i]) ? (float)$request->tax[$i] : 0;
                
                $itemSubtotal = $qty * $price;
                $rowTotal = $itemSubtotal - $discount + $tax;
                
                $totalSubtotal += $itemSubtotal;
                $totalDiscount += $discount;
                $totalTax += $tax;
                $totalAmount += $rowTotal;

                $itemClass = $request->item_type[$i] == 'RawMaterial' 
                    ? RawMaterial::class 
                    : PackagingMaterial::class;
                    
                $itemId = $request->item_id[$i];

                MaterialPurchaseItem::create([
                    'material_purchase_id' => $purchase->id,
                    'item_type' => $itemClass,
                    'item_id' => $itemId,
                    'qty' => $qty,
                    'unit_price' => $price,
                    'discount' => $discount,
                    'tax' => $tax,
                    'batch_no' => $request->batch_no[$i] ?? null,
                    'mfg_date' => $request->mfg_date[$i] ?? null,
                    'exp_date' => $request->exp_date[$i] ?? null,
                    'subtotal' => $rowTotal,
                ]);

                if ($request->invoice_status === 'completed') {
                    // 3. Update Stock & Movements
                    MaterialStockMovement::create([
                        'item_type' => $itemClass,
                        'item_id' => $itemId,
                        'type' => 'in',
                        'qty' => $qty,
                        'ref_type' => 'PURCHASE',
                        'ref_id' => $purchase->id,
                        'note' => 'Purchase Invoice ' . $purchase->invoice_no
                    ]);

                    // Update current_stock directly on material
                    if ($itemClass == RawMaterial::class) {
                        RawMaterial::where('id', $itemId)->increment('current_stock', $qty);
                    } else {
                        PackagingMaterial::where('id', $itemId)->increment('current_stock', $qty);
                    }
                }
            }

            // Add transport to total amount
            $totalAmount += $transportCharges;

            // Update Totals
            $balanceAmount = $totalAmount - $paidAmount;
            $purchase->update([
                'subtotal' => $totalSubtotal,
                'total_discount' => $totalDiscount,
                'total_tax' => $totalTax,
                'total_amount' => $totalAmount,
                'balance_amount' => $balanceAmount
            ]);

            if ($request->invoice_status === 'completed') {
                // 4. Update Vendor Ledger
                $prevClosing = VendorLedger::where('vendor_id', $purchase->vendor_id)->value('closing_balance') ?? 0;
                VendorLedger::updateOrCreate(
                    ['vendor_id' => $purchase->vendor_id],
                    [
                        'admin_or_user_id' => auth()->id() ?? 1,
                        'previous_balance' => $prevClosing,
                        'closing_balance' => $prevClosing + $balanceAmount,
                    ]
                );

                // 5. Accounting (Journal Entries)
                $balanceService = app(BalanceService::class);
                $journalService = app(JournalEntryService::class);
                
                $expenseAccountId = $balanceService->getPurchaseExpenseId();
                $apAccountId = $balanceService->getAccountsPayableId();
                $vendor = Vendor::find($purchase->vendor_id);

                // Need a voucher wrapper to satisfy recordEntry requirements
                // Create a pseudo voucher or save a real one if needed, we'll create a VocherMaster
                $voucherService = app(\App\Services\TransactionService::class);
                
                // For material purchases, we'll directly record journal entries linked to the purchase
                // using the vendor payable logic
                $journalService->recordEntry(
                    $purchase,
                    $expenseAccountId,
                    $totalAmount, // Dr Expense
                    0,
                    "Material Purchase Invoice #{$purchase->invoice_no}",
                    $purchase->purchase_date
                );

                $journalService->recordEntry(
                    $purchase,
                    $apAccountId,
                    0,
                    $totalAmount, // Cr Payable
                    "Material Purchase Invoice #{$purchase->invoice_no}",
                    $purchase->purchase_date,
                    $vendor
                );

                // Payment journal entries if paid amount > 0
                if ($paidAmount > 0 && $request->payment_account_id) {
                    // Dr Payable
                    $journalService->recordEntry(
                        $purchase,
                        $apAccountId,
                        $paidAmount, 
                        0, 
                        "Payment for Material Purchase #{$purchase->invoice_no}",
                        $purchase->purchase_date,
                        $vendor
                    );
                    
                    // Cr Cash/Bank
                    $journalService->recordEntry(
                        $purchase,
                        $request->payment_account_id,
                        0,
                        $paidAmount,
                        "Payment for Material Purchase #{$purchase->invoice_no}",
                        $purchase->purchase_date
                    );
                }
            }

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'ok' => true,
                    'message' => 'Material Purchase saved successfully!',
                    'redirect' => route('material-purchases.index')
                ]);
            }

            return redirect()->route('material-purchases.index')->with('success', 'Purchase saved successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $errorMsg = $e->getMessage();
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $errorMsg = implode(', ', collect($e->errors())->flatten()->toArray());
            } else {
                \Log::error('Material Purchase Error: ' . $errorMsg);
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'ok' => false,
                    'message' => $errorMsg
                ], 422);
            }

            return back()->with('error', 'Error: ' . $errorMsg)->withInput();
        }
    }

    public function show($id)
    {
        $purchase = MaterialPurchase::with('items', 'vendor')->findOrFail($id);
        return view('admin_panel.material_purchases.show', compact('purchase'));
    }

    public function edit($id)
    {
        $purchase = MaterialPurchase::with('items')->findOrFail($id);
        $vendors = Vendor::where('status', 1)->get();
        $rawMaterials = RawMaterial::with('unit')->where('status', 1)->get();
        $packagingMaterials = PackagingMaterial::with('unit')->where('status', 1)->get();
        $accounts = Account::whereHas('head', function($q) {
            $q->whereIn('name', ['Cash', 'Bank']);
        })->where('status', 1)->orderBy('title')->get();
        
        return view('admin_panel.material_purchases.edit', compact('purchase', 'vendors', 'rawMaterials', 'packagingMaterials', 'accounts'));
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $purchase = MaterialPurchase::with('items')->findOrFail($id);
            
            if ($purchase->status === 'completed') {
                $this->reverseCompletedPurchase($purchase);
            }
            
            MaterialPurchaseItem::where('material_purchase_id', $purchase->id)->delete();
            $purchase->delete();
            
            DB::commit();
            return redirect()->route('material-purchases.index')->with('success', 'Purchase deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'purchase_date' => 'required|date',
            'purchase_type' => 'required|string',
            'payment_method' => 'required|string',
            'payment_status' => 'required|string',
            
            'item_type' => 'required|array',
            'item_id' => 'required|array',
            'qty' => 'required|array',
            'unit_price' => 'required|array',
            'discount' => 'nullable|array',
            'tax' => 'nullable|array',
            'batch_no' => 'nullable|array',
            'mfg_date' => 'nullable|array',
            'exp_date' => 'nullable|array',
            
            'transport_charges' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'invoice_status' => 'required|in:draft,completed'
        ]);

        try {
            DB::beginTransaction();
            $purchase = MaterialPurchase::with('items')->findOrFail($id);
            
            // 1. Reverse if it was completed previously
            if ($purchase->status === 'completed') {
                $this->reverseCompletedPurchase($purchase);
            }

            // 2. Clear old items
            MaterialPurchaseItem::where('material_purchase_id', $purchase->id)->delete();

            $totalSubtotal = 0;
            $totalDiscount = 0;
            $totalTax = 0;
            $totalAmount = 0;
            $transportCharges = $request->transport_charges ?? 0;
            $paidAmount = $request->paid_amount ?? 0;

            // 3. Update Header
            $purchase->update([
                'purchase_date' => $request->purchase_date,
                'vendor_id' => $request->vendor_id,
                'purchase_type' => $request->purchase_type,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status,
                'transport_name' => $request->transport_name,
                'driver_name' => $request->driver_name,
                'driver_contact' => $request->driver_contact,
                'vehicle_no' => $request->vehicle_no,
                'transport_charges' => $transportCharges,
                'remarks' => $request->remarks,
                'status' => $request->invoice_status
            ]);

            // 4. Process New Items
            for ($i = 0; $i < count($request->item_id); $i++) {
                $qty = (float)$request->qty[$i];
                $price = (float)$request->unit_price[$i];
                $discount = isset($request->discount[$i]) ? (float)$request->discount[$i] : 0;
                $tax = isset($request->tax[$i]) ? (float)$request->tax[$i] : 0;
                
                $itemSubtotal = $qty * $price;
                $rowTotal = $itemSubtotal - $discount + $tax;
                
                $totalSubtotal += $itemSubtotal;
                $totalDiscount += $discount;
                $totalTax += $tax;
                $totalAmount += $rowTotal;

                $itemClass = $request->item_type[$i] == 'RawMaterial' 
                    ? RawMaterial::class 
                    : PackagingMaterial::class;
                    
                $itemId = $request->item_id[$i];

                MaterialPurchaseItem::create([
                    'material_purchase_id' => $purchase->id,
                    'item_type' => $itemClass,
                    'item_id' => $itemId,
                    'qty' => $qty,
                    'unit_price' => $price,
                    'discount' => $discount,
                    'tax' => $tax,
                    'batch_no' => $request->batch_no[$i] ?? null,
                    'mfg_date' => $request->mfg_date[$i] ?? null,
                    'exp_date' => $request->exp_date[$i] ?? null,
                    'subtotal' => $rowTotal,
                ]);

                if ($request->invoice_status === 'completed') {
                    MaterialStockMovement::create([
                        'item_type' => $itemClass,
                        'item_id' => $itemId,
                        'type' => 'in',
                        'qty' => $qty,
                        'ref_type' => 'PURCHASE',
                        'ref_id' => $purchase->id,
                        'note' => 'Purchase Invoice ' . $purchase->invoice_no
                    ]);

                    if ($itemClass == RawMaterial::class) {
                        RawMaterial::where('id', $itemId)->increment('current_stock', $qty);
                    } else {
                        PackagingMaterial::where('id', $itemId)->increment('current_stock', $qty);
                    }
                }
            }

            $totalAmount += $transportCharges;
            $balanceAmount = $totalAmount - $paidAmount;
            
            $purchase->update([
                'subtotal' => $totalSubtotal,
                'total_discount' => $totalDiscount,
                'total_tax' => $totalTax,
                'total_amount' => $totalAmount,
                'balance_amount' => $balanceAmount
            ]);

            // 5. Apply new accounting if completed
            if ($request->invoice_status === 'completed') {
                $prevClosing = VendorLedger::where('vendor_id', $purchase->vendor_id)->value('closing_balance') ?? 0;
                VendorLedger::updateOrCreate(
                    ['vendor_id' => $purchase->vendor_id],
                    [
                        'admin_or_user_id' => auth()->id() ?? 1,
                        'previous_balance' => $prevClosing,
                        'closing_balance' => $prevClosing + $balanceAmount,
                    ]
                );

                $balanceService = app(BalanceService::class);
                $journalService = app(JournalEntryService::class);
                $expenseAccountId = $balanceService->getPurchaseExpenseId();
                $apAccountId = $balanceService->getAccountsPayableId();
                $vendor = Vendor::find($purchase->vendor_id);
                
                $journalService->recordEntry($purchase, $expenseAccountId, $totalAmount, 0, "Material Purchase Invoice #{$purchase->invoice_no}", $purchase->purchase_date);
                $journalService->recordEntry($purchase, $apAccountId, 0, $totalAmount, "Material Purchase Invoice #{$purchase->invoice_no}", $purchase->purchase_date, $vendor);

                if ($paidAmount > 0 && $request->payment_account_id) {
                    $journalService->recordEntry($purchase, $apAccountId, $paidAmount, 0, "Payment for Material Purchase #{$purchase->invoice_no}", $purchase->purchase_date, $vendor);
                    $journalService->recordEntry($purchase, $request->payment_account_id, 0, $paidAmount, "Payment for Material Purchase #{$purchase->invoice_no}", $purchase->purchase_date);
                }
            }

            DB::commit();
            return redirect()->route('material-purchases.index')->with('success', 'Purchase updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Material Purchase Update Error: ' . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    private function reverseCompletedPurchase($purchase)
    {
        // 1. Reverse Stock
        foreach ($purchase->items as $item) {
            if ($item->item_type == RawMaterial::class) {
                RawMaterial::where('id', $item->item_id)->decrement('current_stock', $item->qty);
            } else {
                PackagingMaterial::where('id', $item->item_id)->decrement('current_stock', $item->qty);
            }
        }
        MaterialStockMovement::where('ref_type', 'PURCHASE')->where('ref_id', $purchase->id)->delete();

        // 2. Reverse Vendor Ledger
        $prevClosing = VendorLedger::where('vendor_id', $purchase->vendor_id)->value('closing_balance') ?? 0;
        VendorLedger::updateOrCreate(
            ['vendor_id' => $purchase->vendor_id],
            [
                'admin_or_user_id' => auth()->id() ?? 1,
                'previous_balance' => $prevClosing,
                'closing_balance' => $prevClosing - $purchase->balance_amount,
            ]
        );

        // 3. Delete Journal Entries
        \App\Models\JournalEntry::where('reference_type', MaterialPurchase::class)
            ->where('reference_id', $purchase->id)
            ->delete();
    }

}
