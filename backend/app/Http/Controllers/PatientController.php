<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
     public function index(Request $request)
    {
            $query = Patient::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('id', $search);
            });
        }
        $patients = $query->orderBy('id', 'desc')->paginate(10);
        return response()->json($patients);
    }
    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:255', 'date_of_birth' => 'required|date', 'address' => 'required|string|max:255', 'phone' => 'nullable|string|max:20', 'medical_history' => 'nullable|string']);
        return Patient::create($validated);
    }
    public function show($id)
    {
        $patient = Patient::findOrFail($id);

        if (!$patient) {
            return response()->json(["message" => "Patient not found"], 404);
        }

        return $patient;
    }
    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        if (!$patient) {
            return response()->json(["message" => "Patient not found"], 404);
        }

        $patient->update($request->all());
        return response()->json($patient);
    }
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);

        if (!$patient) {
            return response()->json(["message" => "Patient not found"], 404);
        }

        $patient->delete();
        return response()->json(["message" => "Patient deleted successfully"], 200);
    }
}
