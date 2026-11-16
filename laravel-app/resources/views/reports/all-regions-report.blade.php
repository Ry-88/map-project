<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Cairo', sans-serif; direction: rtl; text-align: right; font-size: 11px; color: #1f2937; }
        .cover-title { text-align: center; margin-top: 250px; }
        .cover-title h1 { font-size: 28px; color: #1e3a5f; }
        .cover-title .subtitle { font-size: 13px; color: #6b7280; margin-top: 10px; }
        .report-header { text-align: center; border-bottom: 3px solid #1e3a5f; padding-bottom: 12px; margin-bottom: 20px; }
        .report-header h1 { font-size: 22px; color: #1e3a5f; margin: 0 0 6px 0; }
        .report-header .subtitle { font-size: 11px; color: #6b7280; }
        .section { margin-bottom: 22px; }
        .section-title { background: #1e3a5f; color: #fff; padding: 7px 12px; font-size: 15px; border-radius: 3px; margin-bottom: 8px; }
        .national-title { color: #1e3a5f; font-size: 18px; text-align: center; margin: 0 0 8px 0; }
        table { width: 100%; border-collapse: collapse; direction: rtl; }
        th, td { border: 1px solid #d1d5db; padding: 5px 6px; text-align: center; direction: rtl; font-size: 10px; }
        th { background: #eef2f7; color: #1e3a5f; font-weight: bold; }
        tr:nth-child(even) td { background: #f9fafb; }
        .no-data { text-align: center; color: #9ca3af; padding: 10px; font-size: 10px; border: 1px dashed #d1d5db; }
        .badge { display: inline-block; color: #ffffff; padding: 1px 6px; border-radius: 10px; font-size: 9px; margin-inline-start: 4px; }
    </style>
</head>
<body>

    {{-- غلاف التقرير --}}
    <div class="cover-title">
        <h1>تقرير جميع المناطق الإدارية</h1>
        <div class="subtitle">تاريخ التقرير: {{ now()->format('Y-m-d H:i') }}</div>
        <div class="subtitle">عدد المناطق: {{ $regions->count() }}</div>
    </div>

    <pagebreak />

    {{-- مؤشرات المملكة (مرة واحدة فقط) --}}
    <h2 class="national-title">المؤشرات العامة على مستوى المملكة</h2>
    @include('reports.partials.comms-kpis', ['kpi' => $national])

    <table class="ck-stat">
        <tr>
            <td class="c-ok"><b>{{ $national['ready'] }}</b><br>مناطق جاهزة (80% فأكثر)</td>
            <td class="c-mid"><b>{{ $national['mid'] }}</b><br>تحتاج متابعة (60–79%)</td>
            <td class="c-bad"><b>{{ $national['crit'] }}</b><br>مناطق حرجة (أقل من 60%)</td>
        </tr>
    </table>

    @foreach($regions as $region)

        <pagebreak />

        <div class="report-header">
            <h1>تقرير {{ $region['regionName'] }}</h1>
            <div class="subtitle">تاريخ التقرير: {{ now()->format('Y-m-d H:i') }}</div>
        </div>

        {{-- مؤشرات المنطقة --}}
        @include('reports.partials.comms-kpis', ['kpi' => $region['kpi']])

        {{-- 1: السنترالات --}}
        <div class="section">
            <div class="section-title">السنترالات <span class="badge">{{ count($region['exchanges']) }}</span></div>
            @if(count($region['exchanges']))
                <table>
                    <thead>
                        <tr><th>النوع</th><th>الإصدار</th><th>الموقع</th></tr>
                    </thead>
                    <tbody>
                        @foreach($region['exchanges'] as $row)
                        <tr>
                            <td>{{ $row['type'] }}</td><td>{{ $row['sw'] }}</td><td>{{ $row['location'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">لا توجد بيانات</div>
            @endif
        </div>

        {{-- 2: محطات الإعادة --}}
        <div class="section">
            <div class="section-title">محطات الإعادة <span class="badge">{{ count($region['repeaters']) }}</span></div>
            @include('reports.partials.item-count-loc-table', ['rows' => $region['repeaters']])
        </div>

        {{-- 3: الهوائيات --}}
        <div class="section">
            <div class="section-title">الهوائيات <span class="badge">{{ count($region['antennas']) }}</span></div>
            @include('reports.partials.item-count-loc-table', ['rows' => $region['antennas']])
        </div>

        {{-- 4: الأجهزة --}}
        <div class="section">
            <div class="section-title">الأجهزة <span class="badge">{{ count($region['devices']) }}</span></div>
            @include('reports.partials.item-count-loc-table', ['rows' => $region['devices']])
        </div>

        {{-- 5: الألياف الضوئية --}}
        <div class="section">
            <div class="section-title">الألياف الضوئية <span class="badge">{{ count($region['fiberOptics']) }}</span></div>
            @include('reports.partials.item-count-loc-table', ['rows' => $region['fiberOptics']])
        </div>

        {{-- 6: الورش الفنية --}}
        <div class="section">
            <div class="section-title">الورش الفنية <span class="badge">{{ count($region['workshops']) }}</span></div>
            @if(count($region['workshops']))
                <table>
                    <thead>
                        <tr>
                            <th>اسم الورشة</th><th>الموقع</th><th>عدد الموظفين</th>
                            <th>المهندسين</th><th>الفنيين</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($region['workshops'] as $row)
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

    @endforeach

</body>
</html>
