<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    @include('reports.partials.security-style')
</head>
<body>
    <h1>تقرير الأنظمة الأمنية — جميع المناطق</h1>
    <p class="sub">{{ now()->format('Y-m-d H:i') }}</p>

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
                    <td>{{ $r['readiness'] !== null ? $r['readiness'] . '%' : '—' }}</td>
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
