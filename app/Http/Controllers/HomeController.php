<?php

namespace App\Http\Controllers;

use App\Models\Calculation;
use App\Models\RowData;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home');
    }

    public function newCalculation(): View
    {
        return  \view('new_calculation_form');
    }

    public function calculateNstore(Request $request)
    {
        // Get input data
        $total_meal = $request->total_meal;
        $overall_cost = $request->overall_cost;
        $utility_pay = $request->utility_pay;

        $utility_have_to_pay = $request->utility_have_to_pay;

        // Calculation
        $food_cost = $overall_cost - $utility_pay;

        $meal_rate = $food_cost / $total_meal;

        $utility_cost = $utility_pay + $utility_have_to_pay;

        $utility_rate = $utility_cost / User::$TOTAL_MEMBER;

        // Storable Data
        $afran = (($request->afran_toal_meal * $meal_rate) + $utility_rate) - $request->afran;
        $mazharul = (($request->mazharul_toal_meal * $meal_rate) + $utility_rate) - $request->mazharul;
        $rasel = (($request->rasel_toal_meal * $meal_rate) + $utility_rate) - $request->rasel;
        $mostofa = (($request->mostofa_toal_meal * $meal_rate) + $utility_rate) - $request->mostofa;
        $arafat = (($request->arafat_toal_meal * $meal_rate) + $utility_rate) - $request->arafat;
        $nirob = (($request->nirob_toal_meal * $meal_rate) + $utility_rate) - $request->nirob;
        $unknown = (($request->unknown_toal_meal * $meal_rate) + $utility_rate) - $request->unknown;

        $store = new Calculation();

        $store->month = $request->month;
        $store->year = $request->year;
        $store->total_meal = $request->total_meal;
        $store->overall_cost = $request->overall_cost;
        $store->food_cost = $food_cost;
        $store->utility_cost = $utility_cost;
        $store->meal_rate = $meal_rate;
        $store->utility_rate = $utility_rate;

        $store->afran_total_meal = $request->afran_toal_meal;
        $store->afran_spend = $request->afran;
        $store->afran_payable = $afran;

        $store->mazharul_total_meal = $request->mazharul_total_meal;
        $store->mazharul_spend = $request->mazharul;
        $store->mazharul_payable = $mazharul;

        $store->rasel_total_meal = $request->rasel_total_meal;
        $store->rasel_spend = $request->rasel;
        $store->rasel_payable = $rasel;

        $store->mostofa_total_meal = $request->mostofa_total_meal;
        $store->mostofa_spend = $request->mostofa;
        $store->mostofa_payable = $mostofa;

        $store->arafat_total_meal = $request->arafat_total_meal;
        $store->arafat_spend = $request->arafat;
        $store->arafat_payable = $arafat;

        $store->nirob_total_meal = $request->nirob_total_meal;
        $store->nirob_spend = $request->nirob;
        $store->nirob_payable = $nirob;

        $store->unknown_total_meal = $request->unknown_total_meal;
        $store->unknown_spend = $request->unknown;
        $store->unknown_payable = $unknown;

        $store->cooker_bill = -4000;

        $store->save();

        $row_date = New RowData();
        $row_date->calculation_id = $store->id;
        $row_date->month = $request->month;
        $row_date->year = $request->year;
        $row_date->total_meal = $request->total_meal;
        $row_date->overall_cost = $request->overall_cost;
        $row_date->utility_pay = $request->utility_pay;
        $row_date->utility_have_to_pay = $request->utility_have_to_pay;
        $row_date->afran_toal_meal = $request->afran_toal_meal;
        $row_date->afran = $request->afran;
        $row_date->mazharul_total_meal = $request->mazharul_total_meal;
        $row_date->mazharul = $request->mazharul;
        $row_date->rasel_total_meal = $request->rasel_total_meal;
        $row_date->rasel = $request->rasel;
        $row_date->mostofa_total_meal = $request->mostofa_total_meal;
        $row_date->mostofa = $request->mostofa;
        $row_date->arafat_total_meal = $request->arafat_total_meal;
        $row_date->arafat = $request->arafat;
        $row_date->nirob_total_meal = $request->nirob_total_meal;
        $row_date->nirob = $request->nirob;
        $row_date->unknown_total_meal = $request->unknown_total_meal;
        $row_date->unknown = $request->unknown;
        $row_date->save();

        return redirect()->back()->with('message', 'Calculation Done! Go to manage To see Result');

    }

    public function manageAll(): View
    {
        $data = Calculation::all();

        return \view('manage', compact('data'));
    }

    public function show($id): View
    {
        $data = Calculation::find($id);

        return  \view('show', compact('data'));
    }

    public function dataEdit($id): View
    {

        $data = RowData::where('calculation_id', $id)->first();

        return  \view('edit', compact('data'));
    }

    public function dataUpdate(Request $request, $id)
    {
        return $request->all();

    }

}
