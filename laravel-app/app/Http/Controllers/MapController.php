<?php

namespace App\Http\Controllers;

use App\Models\City;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;


class MapController extends Controller
{

    public function login()
    {


        return view('login');
    }


    public function index()
    {
        $cities = City::with([
            'centrals',
            'repeaterStations',
            'antennas',
            'devices',
            'fiberOptics',
            'workshops',
        ])->get();

        $regionalDataStore = [];

        foreach ($cities as $city) {
            $regionalDataStore[$city->code] = $this->buildRegionData($city);
        }

        return view('new-map', compact('regionalDataStore'));
    }

    public function downloadReport($regionId)
    {
        $city = City::with([
            'centrals',
            'repeaterStations',
            'antennas',
            'devices',
            'fiberOptics',
            'workshops',
        ])->where('code', $regionId)->firstOrFail();

        $data = $this->buildRegionData($city);
        $data['regionName'] = $city->name;

        $html = view('reports.region-report', $data)->render();

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'tempDir' => storage_path('app/mpdf-temp'),
            'fontDir' => array_merge($fontDirs, [storage_path('fonts')]),
            'fontdata' => $fontData + [
                'Cairo' => [
                    'R' => 'Cairo-Regular.ttf',
                    'B' => 'Cairo-Bold.ttf',
                ],
            ],
            'default_font' => 'Cairo',
            'directionality' => 'rtl',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_left' => 12,
            'margin_right' => 12,
        ]);

        $mpdf->WriteHTML($html);

        return response($mpdf->Output("تقرير-{$city->name}.pdf", 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="تقرير-' . $city->name . '.pdf"',
        ]);
    }

    public function downloadAllReport()
{
    $cities = City::with([
        'centrals',
        'repeaterStations',
        'antennas',
        'devices',
        'fiberOptics',
        'workshops',
    ])->get();

    $regions = $cities->map(function ($city) {
        $data = $this->buildRegionData($city);
        $data['regionName'] = $city->name;
        return $data;
    });

    $html = view('reports.all-regions-report', compact('regions'))->render();

    $defaultConfig = (new ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];

    $defaultFontConfig = (new FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'tempDir' => storage_path('app/mpdf-temp'),
        'fontDir' => array_merge($fontDirs, [storage_path('fonts')]),
        'fontdata' => $fontData + [
            'Cairo' => [
                'R' => 'Cairo-Regular.ttf',
                'B' => 'Cairo-Bold.ttf',
            ],
        ],
        'default_font' => 'Cairo',
        'directionality' => 'rtl',
        'autoScriptToLang' => true,
        'autoLangToFont' => true,
        'margin_top' => 15,
        'margin_bottom' => 15,
        'margin_left' => 12,
        'margin_right' => 12,
    ]);

    $mpdf->WriteHTML($html);

    return response($mpdf->Output('تقرير-جميع-المناطق.pdf', 'S'), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="تقرير-جميع-المناطق.pdf"',
    ]);
}


    private function buildRegionData(City $city): array
    {
        return [
            'exchanges' => $city->centrals->map(fn($central) => [
                'type'    => $central->type,
                'sw'      => $central->version,
                'location'=> $central->location,
                'analog'  => $central->analog_count,
                'digital' => $central->digital_count,
                'direct'  => $central->direct_count,
                'ac'      => $central->ac_units,
                'batt'    => $central->batteries,
                'ups'     => $central->ups,
                'ext'     => $central->external_capacity,
                'int'     => $central->internal_capacity,
                'cap'     => $central->central_capacity,
            ])->values(),

            'repeaters' => $city->repeaterStations->map(fn($station) => [
                'item'  => $station->type,
                'count' => $station->count,
                'loc'   => $station->location,
            ])->values(),

            'antennas' => $city->antennas->map(fn($antenna) => [
                'item'  => $antenna->type,
                'count' => $antenna->count,
                'loc'   => $antenna->location,
            ])->values(),

            'devices' => $city->devices->map(fn($device) => [
                'item'  => $device->type,
                'count' => $device->count,
                'loc'   => $device->location,
            ])->values(),

            'fiberOptics' => $city->fiberOptics->map(fn($fiber) => [
                'item'  => $fiber->type,
                'count' => $fiber->count,
                'loc'   => $fiber->location,
            ])->values(),

            'workshops' => $city->workshops->map(fn($workshop) => [
                'name'              => $workshop->workshop_name,
                'location'          => $workshop->location,
                'employees'         => $workshop->employees_count,
                'engineers_count'   => $workshop->engineers_count,
                'technicians_count' => $workshop->technicians_count,
                'engineers'         => $workshop->engineers ?? [],
                'technicians'       => $workshop->technicians ?? [],
            ])->values(),
        ];
    }
}
