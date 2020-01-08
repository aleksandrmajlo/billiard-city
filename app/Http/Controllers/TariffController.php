<?php

namespace App\Http\Controllers;

use App\Stock;
use App\Table;
use App\Tariff;
use Illuminate\Http\Request;

class TariffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tables = Table::all();
        return view('tarifs', compact('tables'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $start = Tariff::minuts($request->from_time) + ($request->from_day_week * 24 * 60);
        $end = Tariff::minuts($request->before_time) + ($request->before_day_week * 24 * 60);


        $range[] = range($start, $end);

        $tablePrices = Tariff::where('table_id', '=', $request->table)
            ->get();

        // See yes tarif in the table or not
        if (count($tablePrices) > 0) {
            $k = 0;
            foreach ($tablePrices as $tablePrice) {
                $rang[] = range($tablePrice->start, $tablePrice->end);
                $k++;
            }
            $k = $k - 1;
            for ($i = 0; $i <= $k; $i++) {
                $rang2 = array_merge($rang[$i], $rang[$i++]);
            }

            if (in_array($start, $rang2) == true) {
                return redirect('/tarif')->with('status', 'Тариф на цей час вже існує
');
            }

            if (in_array($end, $rang2) == true) {
                return redirect('/tarif')->with('status', 'Тариф на цей час вже існує
');
            }
        }

        $tariff = new Tariff;
        $tariff->table_id = $request->table;
        $tariff->from_time = $request->from_time;
        $tariff->start = $start;
        $tariff->from_day_week = $request->from_day_week;
        $tariff->before_time = $request->before_time;
        $tariff->end = $end;
        $tariff->before_day_week = $request->before_day_week;
        $tariff->price = $request->price;
        $tariff->save();
        return redirect('/tarif')->with('status', 'Доданно!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tariff $tariff
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tables = Table::all();
        $tarif = Tariff::where('id', '=', $id)
            ->first();


        return view('admin.table.tarif-edit', compact('tarif', 'tables'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tariff $tariff
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {

        $start = Tariff::minuts($request->from_time) + $request->from_day_week * 24 * 60;
        $end = Tariff::minuts($request->before_time) + $request->before_day_week * 24 * 60;

        $range[] = range($start, $end);

        $tablePrices = Tariff::where('table_id', '=', $request->table)
            ->where('id', '!=', $request->id)
            ->get();


        // See yes tarif in the table or not
        if (count($tablePrices) > 0) {
            $k = 0;
            foreach ($tablePrices as $tablePrice) {
                $rang[] = range($tablePrice->start, $tablePrice->end);
                $k++;
            }
            $k = $k - 1;
            for ($i = 0; $i <= $k; $i++) {
                $rang2 = array_merge($rang[$i], $rang[$i++]);
            }

            if (in_array($start, $rang2) == true) {
                return redirect('/tarif')->with('status', 'Тариф на цей час вже існує
');
            }

            if (in_array($end, $rang2) == true) {
                return redirect('/tarif')->with('status', 'Тариф на цей час вже існує
');
            }
        }

        $tariff = Tariff::find($request->id);
        $tariff->table_id = $request->table;
        $tariff->from_time = $request->from_time;
        $tariff->start = $start;
        $tariff->from_day_week = $request->from_day_week;
        $tariff->before_time = $request->before_time;
        $tariff->end = $end;
        $tariff->before_day_week = $request->before_day_week;
        $tariff->price = $request->price;
        $tariff->save();
        return redirect('/tarif')->with('status', 'edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Tariff $tariff
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tariff $tariff)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tariff $tariff
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stock = Tariff::find($id);
        $stock->delete();
        return redirect('/tarif')->with('status', 'Delete!');
    }
}
