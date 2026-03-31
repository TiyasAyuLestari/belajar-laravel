<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // 🔥 RIWAYAT TRANSAKSI
    public function index()
    {
        $transactions = Transaction::with(['user','item'])->latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Data transaksi',
            'data' => $transactions
        ]);
    }

    // 🔥 PINJAM BARANG
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id'
        ]);

        $item = Item::find($request->item_id);

        // cek stok
        if ($item->stock <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'Stok barang habis'
            ], 400);
        }

        // kurangi stok
        $item->decrement('stock');

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id,
            'borrow_date' => now(),
            'status' => 'dipinjam'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Barang berhasil dipinjam',
            'data' => $transaction
        ], 201);
    }

    // 🔥 KEMBALIKAN BARANG
    public function update($id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->status == 'dikembalikan') {
            return response()->json([
                'status' => false,
                'message' => 'Barang sudah dikembalikan'
            ], 400);
        }

        // tambah stok
        $item = Item::find($transaction->item_id);
        $item->increment('stock');

        $transaction->update([
            'return_date' => now(),
            'status' => 'dikembalikan'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Barang berhasil dikembalikan',
            'data' => $transaction
        ]);
    }

    // 🔥 DETAIL (opsional tapi bagus)
    public function show($id)
    {
        $transaction = Transaction::with(['user','item'])->findOrFail($id);

        return response()->json([
            'data' => $transaction
        ]);
    }
}