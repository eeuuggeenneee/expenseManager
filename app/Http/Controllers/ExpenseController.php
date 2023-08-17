<?php

namespace App\Http\Controllers;
use App\Models\ExpenseCategory;

use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $expense = ExpenseCategory::create($request->all());

        return response()->json(['message' => 'Expense Category created successfully', 'expense' => $expense], 201);
    }

    public function getAllExpenseC()
    {
        $expense = ExpenseCategory::all();
        return response()->json($expense);
    }

    public function getExpenseC($id)
    {
        $expense = ExpenseCategory::findOrFail($id);
        return response()->json($expense);
    }

    public function updateExpenseC(Request $request, $id)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $expense = ExpenseCategory::findOrFail($id);
        $expense->update($request->all());

        return response()->json(['message' => 'Expense Category updated successfully', 'expense' => $expense], 200);
    }

    public function deleteExpenseC($id)
    {
        $expense = ExpenseCategory::findOrFail($id);
        $expense->delete();

        return response()->json(['message' => 'Expense Category deleted successfully'], 204);
    }
}
