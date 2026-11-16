<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    @include('reports.partials.security-style')
</head>
<body>
    <h1>تقرير الأنظمة الأمنية — جميع المناطق</h1>
    <p class="sub">{{ now()->format('Y-m-d H:i') }}</p>

    <h3>المؤشرات العامة على مستوى المملكة</h3>
@include('reports.partials.security-kpis', [
    'gauge'   => $national['gauge'],
    'cards'   => $national['cards'],
    'stats'   => $national['stats'],
    'records' => $national['records'],
    'total'   => $national['total'],
])

<table class="stat-strip">
    <tr>
        <td class="s-ok"><b>{{ $national['ready'] }}</b><br>مناطق جاهزة (80% فأكثر)</td>
        <td class="s-mid"><b>{{ $national['mid'] }}</b><br>تحتاج متابعة (60–79%)</td>
        <td class="s-bad"><b>{{ $national['crit'] }}</b><br>مناطق حرجة (أقل من 60%)</td>
    </tr>
</table>

    <h3>ملخص المناطق</h3>
    <table>
    <thead>
        <tr><th>المنطقة</th><th>الكاميرات</th><th>الإنذار</th><th>التحكم بالدخول</th><th>الجاهزية</th></tr>
    </thead>
    <tbody>
        @foreach ($regions as $r)
            <tr>
                <td>{{ $r['regionName'] }}</td>
                <td>{{ count($r['cameras']) ?: '—' }}</td>
                <td>{{ count($r['alarms']) ?: '—' }}</td>
                <td>{{ count($r['access']) ?: '—' }}</td>

                @php
                    $v = $r['readiness'];
                    $bg = $v === null ? '#f2f2f2' : ($v >= 80 ? '#d8f3e8' : ($v >= 60 ? '#fdf0cf' : '#fbdada'));
                @endphp
                <td style="background:{{ $bg }}; font-weight:bold;">{{ $v !== null ? $v . '%' : '—' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

    @foreach ($regions->filter(fn ($r) => $r['records'] > 0) as $region)
        <pagebreak />
        @include('reports.partials.security-region', ['region' => $region])
    @endforeach
</body>
</html>
