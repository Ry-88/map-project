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
        $data['kpi'] = $this->commsKpi($data);

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
            $data['kpi'] = $this->commsKpi($data);  // ← جديد
            return $data;
        });

        // مؤشرات على مستوى المملكة
        $groups = [];
        foreach (['exchanges', 'repeaters', 'antennas', 'devices', 'fiberOptics', 'workshops'] as $k) {
            $groups[$k] = $regions->flatMap(fn($r) => $r[$k])->values()->all();
        }
        $national = $this->commsKpi($groups, 85, 120);
        $national['ready'] = $regions->filter(fn($r) => $r['kpi']['readiness'] !== null && $r['kpi']['readiness'] >= 80)->count();
        $national['mid']   = $regions->filter(fn($r) => $r['kpi']['readiness'] !== null && $r['kpi']['readiness'] >= 60 && $r['kpi']['readiness'] < 80)->count();
        $national['crit']  = $regions->filter(fn($r) => $r['kpi']['readiness'] !== null && $r['kpi']['readiness'] < 60)->count();

        $html = view('reports.all-regions-report', compact('regions', 'national'))->render();

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
            ->map(fn($city) => $this->securityReportData($city));

        // مؤشرات على مستوى المملكة
        $groups = [];
        foreach (['cameras', 'alarms', 'access'] as $k) {
            $groups[$k] = $regions->flatMap(fn($r) => $r[$k])->values()->all();
        }
        $allRows   = collect($groups)->collapse();
        $nationalV = $this->readinessOf($allRows);

        $national = [
            'readiness' => $nationalV,
            'gauge'     => $nationalV !== null ? $this->gauge($nationalV, 120) : null,
            'cards'     => $this->categoryCards($groups, 85),
            'stats'     => $this->statusStats($allRows),
            'records'   => $allRows->count(),
            'total'     => $allRows->sum(fn($r) => $r['count'] ?? 1),
            'ready'     => $regions->filter(fn($r) => $r['readiness'] !== null && $r['readiness'] >= 80)->count(),
            'mid'       => $regions->filter(fn($r) => $r['readiness'] !== null && $r['readiness'] >= 60 && $r['readiness'] < 80)->count(),
            'crit'      => $regions->filter(fn($r) => $r['readiness'] !== null && $r['readiness'] < 60)->count(),
        ];

        $html = view('reports.security-all-report', compact('regions', 'national'))->render();

        return $this->pdfSecurity($html, 'تقرير-الأنظمة-الأمنية-جميع-المناطق.pdf');
    }

    private function securityReportData(City $city): array
    {
        $sec = $this->buildSecurityData($city);
        $all = collect($sec)->collapse();
        $readiness = $this->readinessOf($all);

        return [
            'regionName' => $city->name,
            'cameras'    => $sec['cameras'] ?? [],
            'alarms'     => $sec['alarms'] ?? [],
            'access'     => $sec['access'] ?? [],
            'records'    => $all->count(),
            'total'      => $all->sum(fn($r) => $r['count'] ?? 1),
            'readiness'  => $readiness,
            'gauge'      => $readiness !== null ? $this->gauge($readiness, 110) : null,
            'cards'      => $this->categoryCards($sec),
            'stats'      => $this->statusStats($all),
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

    /** دائرة جاهزية SVG (صورة مضمّنة) تعمل داخل mPDF */
    private function gauge(int $v, int $size = 90): string
    {
        $v = max(0, min(100, $v));
        $c = $v >= 80 ? '#1fb37f' : ($v >= 60 ? '#e8a317' : '#e5484d');

        if ($v >= 100) {
            $arc = '<circle cx="50" cy="50" r="38" fill="none" stroke="' . $c . '" stroke-width="10"/>';
        } elseif ($v <= 0) {
            $arc = '';
        } else {
            $a = deg2rad($v * 3.6 - 90);
            $arc = sprintf(
                '<path d="M50 12 A38 38 0 %d 1 %.2f %.2f" fill="none" stroke="%s" stroke-width="10" stroke-linecap="round"/>',
                $v > 50 ? 1 : 0,
                50 + 38 * cos($a),
                50 + 38 * sin($a),
                $c
            );
        }

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 100 100">'
            . '<circle cx="50" cy="50" r="38" fill="none" stroke="#e3e6f0" stroke-width="10"/>'
            . $arc
            . '<text x="50" y="58" text-anchor="middle" font-size="24" font-weight="bold" font-family="DejaVu Sans" fill="#1c2142">' . $v . '%</text>'
            . '</svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    private function readinessOf($rows): ?int
    {
        $rows = collect($rows);
        return $rows->isEmpty()
            ? null
            : (int) round($rows->where('status', 'active')->count() / $rows->count() * 100);
    }

    /** بطاقات التصنيفات (دائرة + أرقام) للتصنيفات التي فيها بيانات فقط */
    private function categoryCards(array $groups, int $size = 80): array
    {
        $titles = [
            'cameras' => 'الكاميرات (ساجم)',
            'alarms'  => 'الإنذار (زالي)',
            'access'  => 'التحكم بالدخول (فلير)',
        ];

        $cards = [];
        foreach ($titles as $key => $title) {
            $rows = collect($groups[$key] ?? []);
            if ($rows->isEmpty()) continue;

            $v = $this->readinessOf($rows);
            $cards[] = [
                'title'   => $title,
                'gauge'   => $this->gauge($v, $size),
                'records' => $rows->count(),
                'total'   => $rows->sum(fn($r) => $r['count'] ?? 1),
            ];
        }
        return $cards;
    }

    private function statusStats($rows): array
    {
        $rows = collect($rows);
        return [
            'active'      => $rows->where('status', 'active')->count(),
            'faulty'      => $rows->where('status', 'faulty')->count(),
            'maintenance' => $rows->where('status', 'maintenance')->count(),
            'retired'     => $rows->where('status', 'retired')->count(),
        ];
    }

    /** مؤشرات تقرير الاتصالات. تقبل مصفوفة بمفاتيح: exchanges/repeaters/antennas/devices/fiberOptics/workshops */
    private function commsKpi(array $groups, int $size = 80, int $mainSize = 110): array
    {
        $titles = [
            'exchanges' => 'السنترالات',
            'repeaters' => 'محطات الإعادة',
            'antennas'  => 'الهوائيات',
            'devices'   => 'الأجهزة',
        ];

        $cards = [];
        $all   = collect();

        foreach ($titles as $key => $title) {
            $rows = collect($groups[$key] ?? []);
            if ($rows->isEmpty()) continue;

            $cards[] = [
                'title'   => $title,
                'gauge'   => $this->gauge($this->readinessOf($rows), $size),
                'records' => $rows->count(),
                'total'   => $key === 'exchanges'
                    ? $rows->count()
                    : $rows->sum(fn($r) => $r['count'] ?? 1),
            ];
            $all = $all->concat($rows);
        }

        $readiness = $this->readinessOf($all);
        $workshops = collect($groups['workshops'] ?? []);

        return [
            'readiness' => $readiness,
            'gauge'     => $readiness !== null ? $this->gauge($readiness, $mainSize) : null,
            'cards'     => $cards,
            'stats'     => $this->statusStats($all),
            'records'   => $all->count(),
            'fiber'     => collect($groups['fiberOptics'] ?? [])->sum(fn($r) => $r['count'] ?? 0),
            'workshops' => $workshops->count(),
            'staff'     => $workshops->sum(fn($w) => ($w['engineers_count'] ?? 0) + ($w['technicians_count'] ?? 0)),
        ];
    }
}
