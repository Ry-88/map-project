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
            'cameras',
            'alarms',
            'accessControls',
        ])->get();

        $regionalDataStore = [];
        $securityDataStore = [];
        $securityKpiStore  = [];

        foreach ($cities as $city) {
            $regionalDataStore[$city->code] = $this->buildRegionData($city);

            $sec = $this->buildSecurityData($city);
            if (!empty($sec)) {
                $securityDataStore[$city->code] = $sec;
                $securityKpiStore[$city->code]  = $this->securityKpi($sec);
            }
        }

        return view('new-map', compact(
            'regionalDataStore',
            'securityDataStore',
            'securityKpiStore'
        ));
    }

    private function buildSecurityData(City $city): array
    {
        $sources = [
            'cameras' => $city->cameras,
            'alarms'  => $city->alarms,
            'access'  => $city->accessControls,
        ];

        $out = [];

        foreach ($sources as $key => $rows) {
            if ($rows->isEmpty()) continue;

            $out[$key] = $rows->map(fn($r) => [
                'item'          => $r->item,
                'count'         => $r->count,
                'loc'           => $r->location,
                'status'        => $r->status?->value,
                'status_label'  => $r->status?->getLabel(),
                'serial_number' => $r->serial_number,
                'image'         => $r->image_url,
            ])->values()->all();
        }

        return $out;
    }

    private function securityKpi(array $sec): array
    {
        return array_map(function ($key) use ($sec) {
            $rows = collect($sec[$key] ?? []);
            if ($rows->isEmpty()) return 0;

            return (int) round(
                $rows->where('status', 'active')->count() / $rows->count() * 100
            );
        }, ['cameras', 'alarms', 'access']);
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
                'location' => $central->location,
                'analog'  => $central->analog_count,
                'digital' => $central->digital_count,
                'direct'  => $central->direct_count,
                'ac'      => $central->ac_units,
                'batt'    => $central->batteries,
                'ups'     => $central->ups,
                'ext'     => $central->external_capacity,
                'int'     => $central->internal_capacity,
                'cap'     => $central->central_capacity,
                'status'        => $central->status?->value,
                'status_label'  => $central->status?->getLabel(),
                'serial_number' => $central->serial_number,
                'image'         => $central->image_url,
            ])->values(),

            'repeaters' => $city->repeaterStations->map(fn($station) => [
                'item'  => $station->type,
                'count' => $station->count,
                'loc'   => $station->location,
                'status'        => $station->status?->value,
                'status_label'  => $station->status?->getLabel(),
                'serial_number' => $station->serial_number,
                'image'         => $station->image_url,
            ])->values(),

            'antennas' => $city->antennas->map(fn($antenna) => [
                'item'  => $antenna->type,
                'count' => $antenna->count,
                'loc'   => $antenna->location,
                'status'        => $antenna->status?->value,
                'status_label'  => $antenna->status?->getLabel(),
                'serial_number' => $antenna->serial_number,
                'image'         => $antenna->image_url,
            ])->values(),

            'devices' => $city->devices->map(fn($device) => [
                'item'  => $device->type,
                'count' => $device->count,
                'loc'   => $device->location,
                'status'        => $device->status?->value,
                'status_label'  => $device->status?->getLabel(),
                'serial_number' => $device->serial_number,
                'image'         => $device->image_url,
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

    public function downloadSecurityReport($regionId)
        {
            $city = City::with(['cameras', 'alarms', 'accessControls'])
                ->where('code', $regionId)
                ->firstOrFail();

            $region = $this->securityReportData($city);

            $html = view('reports.security-region-report', compact('region'))->render();

            return $this->pdfSecurity($html, "تقرير-الأنظمة-الأمنية-{$city->name}.pdf");
        }

        public function downloadAllSecurityReport()
{
    $regions = City::with(['cameras', 'alarms', 'accessControls'])
        ->get()
        ->map(fn ($city) => $this->securityReportData($city));

    $html = view('reports.security-all-report', compact('regions'))->render();

    return $this->pdfSecurity($html, 'تقرير-الأنظمة-الأمنية-جميع-المناطق.pdf');
}

private function securityReportData(City $city): array
{
    $sec = $this->buildSecurityData($city);
    $all = collect($sec)->collapse();

    return [
        'regionName' => $city->name,
        'cameras'    => $sec['cameras'] ?? [],
        'alarms'     => $sec['alarms'] ?? [],
        'access'     => $sec['access'] ?? [],
        'records'    => $all->count(),
        'total'      => $all->sum(fn ($r) => $r['count'] ?? 1),
        'readiness'  => $all->count()
            ? (int) round($all->where('status', 'active')->count() / $all->count() * 100)
            : null,
    ];
}

private function pdfSecurity(string $html, string $filename)
{
    $defaultConfig = (new ConfigVariables())->getDefaults();
    $defaultFontConfig = (new FontVariables())->getDefaults();

    $mpdf = new Mpdf([
        'mode'             => 'utf-8',
        'format'           => 'A4',
        'tempDir'          => storage_path('app/mpdf-temp'),
        'fontDir'          => array_merge($defaultConfig['fontDir'], [storage_path('fonts')]),
        'fontdata'         => $defaultFontConfig['fontdata'] + [
            'Cairo' => ['R' => 'Cairo-Regular.ttf', 'B' => 'Cairo-Bold.ttf'],
        ],
        'default_font'     => 'Cairo',
        'directionality'   => 'rtl',
        'autoScriptToLang' => true,
        'autoLangToFont'   => true,
        'margin_top'       => 15,
        'margin_bottom'    => 15,
        'margin_left'      => 12,
        'margin_right'     => 12,
    ]);

    $mpdf->WriteHTML($html);

    return response($mpdf->Output($filename, 'S'), 200, [
        'Content-Type'        => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ]);
}


}
