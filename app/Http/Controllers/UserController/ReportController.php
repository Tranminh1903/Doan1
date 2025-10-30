<?php
namespace App\Http\Controllers\UserController;

use Illuminate\Http\Request;
use App\Http\Controllers\UserController\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {

        return view('reports.revenue');
    }

    public function ajaxData(Request $request)
    {
        $days = $request->input('days', 7);
        $fromDate = Carbon::now()->subDays($days)->startOfDay();

        $data = DB::table('orders')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
            ->where('status', 'paid')
            ->where('created_at', '>=', $fromDate)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        return response()->json($data);
    }
    public function revenueByMovie()
{
    return view('reports.revenue_movie');
}

public function ajaxRevenueByMovie()
{
    $revenue = DB::table('orders')
        ->join('showtime', 'orders.showtimeID', '=', 'showtime.showtimeID')
        ->join('movies', 'showtime.movieID', '=', 'movies.movieID')
        ->select('movies.title', DB::raw('SUM(orders.amount) as total'))
        ->where('orders.status', 'paid')
        ->groupBy('movies.movieID', 'movies.title')
        ->orderByDesc('total')
        ->get();

    return response()->json($revenue);
}
}
