<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Repair;
use App\Models\PorscheModel;
use App\Models\ServiceCatalog;

class HomeController extends Controller
{
    public function index()
    {
        $models = PorscheModel::take(6)->get();
        $services = ServiceCatalog::where('is_active', true)->take(6)->get();
        
        $totalRepairs = Repair::count();
        $completedRepairs = Repair::where('status', 'completed')->count();
        $inProgressRepairs = Repair::whereIn('status', ['inspecting', 'estimated', 'approved', 'in_progress'])->count();

        // รายการเคสล่าสุด (ไม่เปิดเผยข้อมูลส่วนบุคคลลึกเกินไป)
        $recentTickets = Repair::with(['porscheModel'])
            ->latest()
            ->take(5)
            ->get();

        return view('home', compact(
            'models',
            'services',
            'totalRepairs',
            'completedRepairs',
            'inProgressRepairs',
            'recentTickets'
        ));
    }

    // ติดตามสถานะงานซ่อมแบบด่วน (Quick Search By Ticket No or License Plate)
    public function track(Request $request)
    {
        $query = trim($request->input('search'));
        $repair = null;

        if ($query) {
            $repair = Repair::with(['customer', 'technician', 'porscheModel', 'items', 'images', 'logs'])
                ->where('ticket_no', 'LIKE', "%{$query}%")
                ->orWhere('license_plate', 'LIKE', "%{$query}%")
                ->orWhere('vin_number', 'LIKE', "%{$query}%")
                ->first();
        }

        return view('track', compact('query', 'repair'));
    }
}
