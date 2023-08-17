<?php

namespace App\Http\Controllers;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class Expenses extends Controller
{
  
    public function store(Request $request)
    {
        try {
       
            $data = $request->validate([
                'expense_category_id' => 'required|exists:expense_categories,id',
                'amount' => 'required|numeric|between:0,999999.99',
                'entry_date' => 'required|date',
            ]);
    
            
            $data['user_id'] = auth()->id();
    
          
            $expense = Expense::create($data);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Expense created successfully!',
                'expense' => $expense
            ], 201); 
        } catch (\Exception $e) {
            
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'user_id' => auth()->id(),
            ], 500);
        }
    }
    
    public function getAllExpenses()
    {
        $expenses = auth()->user()->expenses;
        return response()->json($expenses);
    }

    public function getExpenses($id)
    {
        $expense = Expense::findOrFail($id);
        return response()->json($expense);
    }

    public function updateExpenses(Request $request, $id)
    {
        $request->validate([
            'expense_category_id' => 'required|integer|exists:expense_categories,id',
            'amount' => 'required|numeric|between:0,999999.99',
            'entry_date' => 'required|date',
        ]);

        $expense = Expense::findOrFail($id);
        $expense->update($request->all());

        return response()->json(['message' => 'Expenses updated successfully', 'expense' => $expense], 200);
    }

    public function deleteExpenses($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return response()->json(['message' => 'Expenses deleted successfully'], 204);
    }
}
