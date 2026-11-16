@if (!empty($gauge))
    <table class="kpi-strip">
        <tr>
            <td class="kpi-main">
                <img src="{{ $gauge }}" width="110" height="110">
                <div class="kpi-name">الجاهزية العامة</div>
                <div class="kpi-sub">{{ $records }} سجل · {{ $total }} جهاز</div>
            </td>
            @foreach ($cards as $c)
                <td class="kpi-cell">
                    <img src="{{ $c['gauge'] }}" width="80" height="80">
                    <div class="kpi-name">{{ $c['title'] }}</div>
                    <div class="kpi-sub">{{ $c['records'] }} سجل · {{ $c['total'] }} جهاز</div>
                </td>
            @endforeach
        </tr>
    </table>

    <table class="stat-strip">
        <tr>
            <td class="s-ok"><b>{{ $stats['active'] }}</b><br>عامل</td>
            <td class="s-bad"><b>{{ $stats['faulty'] }}</b><br>معطّل</td>
            <td class="s-mid"><b>{{ $stats['maintenance'] }}</b><br>تحت الصيانة</td>
            <td class="s-off"><b>{{ $stats['retired'] }}</b><br>خارج الخدمة</td>
        </tr>
    </table>
@endif
