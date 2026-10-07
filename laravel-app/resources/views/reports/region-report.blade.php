<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            text-align: right;
            font-size: 11px;
            color: #1f2937;
        }

        .report-header {
            text-align: center;
            border-bottom: 3px solid #1e3a5f;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .report-header h1 {
            font-size: 22px;
            color: #1e3a5f;
            margin: 0 0 6px 0;
        }

        .report-header .subtitle {
            font-size: 11px;
            color: #6b7280;
        }

        .section {
            margin-bottom: 22px;
        }

        .section-title {
            background: #1e3a5f;
            color: #fff;
            padding: 7px 12px;
            font-size: 15px;
            border-radius: 3px;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            direction: rtl;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 5px 6px;
            text-align: center;
            direction: rtl;
            font-size: 10px;
        }

        th {
            background: #eef2f7;
            color: #1e3a5f;
            font-weight: bold;
        }

        tr:nth-child(even) td {
            background: #f9fafb;
        }

        .no-data {
            text-align: center;
            color: #9ca3af;
            padding: 10px;
            font-size: 10px;
            border: 1px dashed #d1d5db;
        }

        .badge {
            display: inline-block;
            /* background: #e5e7eb; */
            color: #ffffff;
            padding: 1px 6px;
            border-radius: 10px;
            font-size: 12px;
            margin-inline-start: 4px;
        }
    </style>
</head>
<body>

    <div class="report-header">
        <h1>تقرير  {{ $regionName }}</h1>
        <div class="subtitle">تاريخ التقرير: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    {{-- 1: Exchanges --}}
    <div class="section">
        <div class="section-title">السنترالات <span class="badge">{{ count($exchanges) }}</span></div>
        @if(count($exchanges))
            <table>
                <thead>
                    <tr>
                        <th>النوع</th><th>الإصدار</th><th>الموقع</th>
                        {{-- <th>تماثلية</th><th>رقمية</th><th>مباشرة</th>
                        <th>مكيفات</th><th>بطاريات</th><th>UPS</th><th>سعة خارجية</th><th>سعة داخلية</th><th>سعة السنترال</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($exchanges as $row)
                    <tr>
                        <td>{{ $row['type'] }}</td><td>{{ $row['sw'] }}</td><td>{{ $row['location'] }}</td>
                        {{-- <td>{{ $row['analog'] }}</td>
                        <td>{{ $row['digital'] }}</td><td>{{ $row['direct'] }}</td><td>{{ $row['ac'] }}</td>
                        <td>{{ $row['batt'] }}</td><td>{{ $row['ups'] }}</td><td>{{ $row['ext'] }}</td>
                        <td>{{ $row['int'] }}</td><td>{{ $row['cap'] }}</td> --}}
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">لا توجد بيانات</div>
        @endif
    </div>

    {{-- 2: Repeaters --}}
    <div class="section">
        <div class="section-title">محطات الإعادة <span class="badge">{{ count($repeaters) }}</span></div>
        @include('reports.partials.item-count-loc-table', ['rows' => $repeaters])
    </div>

    {{-- 3: Antennas --}}
    <div class="section">
        <div class="section-title">الهوائيات <span class="badge">{{ count($antennas) }}</span></div>
        @include('reports.partials.item-count-loc-table', ['rows' => $antennas])
    </div>

    {{-- 4: Devices --}}
    <div class="section">
        <div class="section-title">الأجهزة <span class="badge">{{ count($devices) }}</span></div>
        @include('reports.partials.item-count-loc-table', ['rows' => $devices])
    </div>

    {{-- 5: Fiber Optics --}}
    <div class="section">
        <div class="section-title">الألياف الضوئية <span class="badge">{{ count($fiberOptics) }}</span></div>
        @include('reports.partials.item-count-loc-table', ['rows' => $fiberOptics])
    </div>

    {{-- 6: Workshops --}}
    <div class="section">
        <div class="section-title">الورش الفنية <span class="badge">{{ count($workshops) }}</span></div>
        @if(count($workshops))
            <table>
                <thead>
                    <tr>
                        <th>اسم الورشة</th><th>الموقع</th><th>عدد الموظفين</th>
                        <th>المهندسين</th><th>الفنيين</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workshops as $row)
                    <tr>
                        <td>{{ $row['name'] }}</td>
                        <td>{{ $row['location'] }}</td>
                        <td>{{ $row['employees'] }}</td>
                        <td>{{ $row['engineers_count'] }} — {{ implode('، ', $row['engineers']) ?: '-' }}</td>
                        <td>{{ $row['technicians_count'] }} — {{ implode('، ', $row['technicians']) ?: '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">لا توجد بيانات</div>
        @endif
    </div>

</body>
</html>
