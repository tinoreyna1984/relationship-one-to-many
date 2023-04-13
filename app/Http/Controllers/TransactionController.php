<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // devuelve todas las transacciones
    public function getTransactions()
    {
        return response()->json(Transaction::all(), 200);
    }

    // devuelve una transaccion por ID
    public function getTransaction($id)
    {
        $transaction = Transaction::find($id);
        if (is_null($transaction)) {
            return response()->json(['msg' => 'Transaccion no encontrada'], 404);
        }
        return response()->json($transaction::find($id), 200);
    }

    // agrega transaccion
    public function addTransaction(Request $request)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'transaction_amount' => 'required',
        ]);
        $transaction = Transaction::create($request->all());
        return response($transaction, 201);
    }

    // actualiza transaccion
    public function updTransaction(Request $request, $id)
    {
        $transaction = Transaction::find($id);
        if (is_null($transaction)) {
            return response()->json(['msg' => 'Transaccion no encontrada'], 404);
        }
        $transaction->update($request->all());
        return response($transaction, 200);
    }

    // borra transaccion
    public function deleteTransaction($id)
    {
        $transaction = Transaction::find($id);
        if (is_null($transaction)) {
            return response()->json(['msg' => 'Transaccion no encontrada'], 404);
        }
        $transaction->delete();
        return response()->json(['msg' => 'Eliminado Correctamente'], 200);
    }
}
