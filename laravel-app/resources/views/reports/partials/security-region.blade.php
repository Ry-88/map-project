@php
    $cats = [
        'cameras' => 'الكاميرات (ساجم)',
        'alarms'  => 'الإنذار (زالي)',
        'access'  => 'التحكم بالدخول (فلير)',
    ];
    $hasAny = collect($cats)->keys()->contains(fn ($k) => count($region[$k]) > 0);
@endphp

<h2>{{ $region['regionName'] }}</h2>

@if (! $hasAny)
    <p class="empty">لا توجد بيانات أنظمة أمنية مسجّلة لهذه المنطقة.</p>
@else
    <p class="meta">
        عدد السجلات: <b>{{ $region['records'] }}</b> —
        إجمالي الأجهزة: <b>{{ $region['total'] }}</b> —
        الجاهزية: <b>{{ $region['readiness'] }}%</b>
    </p>

    @foreach ($cats as $key => $title)
        @if (count($region[$key]))
            <h3>{{ $title }}</h3>
            <table>
                <thead>
                    <tr>
                        <th>#</th><th>النوع</th><th>العدد</th><th>الموقع</th>
                        <th>الرقم التسلسلي</th><th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($region[$key] as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $row['item'] ?? '—' }}</td>
                            <td>{{ $row['count'] ?? 1 }}</td>
                            <td>{{ $row['loc'] ?? '—' }}</td>
                            <td>{{ $row['serial_number'] ?? '—' }}</td>
                            <td>{{ $row['status_label'] ?? 'غير محدد' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach
@endif
