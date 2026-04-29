<?php

namespace App\Http\Controllers;

use App\Lib\Hijri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DateTime;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $miqaats = $this->getMiqaats();
        
        return view('calendar', [
            'miqaats' => $miqaats,
            'monthNames' => Hijri::MONTH_NAMES,
            'arabicMonthNames' => Hijri::ARABIC_MONTH_NAMES,
        ]);
    }

    private function getMiqaats()
    {
        $path = storage_path('app/data/miqaats.json');
        if (!file_exists($path)) {
            return [];
        }

        $json = file_get_contents($path);
        $data = json_decode($json, true);

        $map = [];
        foreach ($data as $item) {
            $month = $item['month'];
            $date = $item['date'];
            $map["$month-$date"] = array_map(function($m) {
                $m['type'] = 'hijri';
                return $m;
            }, $item['miqaats']);
        }

        return $map;
    }
}
