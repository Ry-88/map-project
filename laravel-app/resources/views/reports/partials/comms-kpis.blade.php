<style>
    .ck-strip { width: 100%; border-collapse: separate; border-spacing: 6px; margin: 8px 0; }
    .ck-main, .ck-cell { text-align: center; border: 1px solid #dfe3f1; background: #f8f9fd; padding: 8px 4px; }
    .ck-main { background: #eef0fa; }
    .ck-name { font-weight: bold; font-size: 10pt; color: #1c2142; }
    .ck-sub { font-size: 8.5pt; color: #666; }
    .ck-stat { width: 100%; border-collapse: separate; border-spacing: 6px; margin-bottom: 8px; }
    .ck-stat td { text-align: center; padding: 6px 2px; font-size: 9pt; border: 1px solid #ddd; }
    .ck-stat b { font-size: 16pt; }
    .c-ok { background: #e3f6ee; color: #117a55; }
    .c-bad { background: #fde6e6; color: #b42323; }
    .c-mid { background: #fdf3da; color: #8a6200; }
    .c-off { background: #eceef5; color: #555; }
    .c-info { background: #eef0fa; color: #1c2142; }
</style>

@if (!empty($kpi['gauge']))
    <table class="ck-strip">
        <tr>
            <td class="ck-main">
                <img src="{{ $kpi['gauge'] }}" width="110" height="110">
                <div class="ck-name">الجاهزية العامة</div>
                <div class="ck-sub">{{ $kpi['records'] }} سجل</div>
            </td>
            @foreach ($kpi['cards'] as $c)
                <td class="ck-cell">
                    <img src="{{ $c['gauge'] }}" width="80" height="80">
                    <div class="ck-name">{{ $c['title'] }}</div>
                    <div class="ck-sub">{{ $c['records'] }} سجل · {{ $c['total'] }}</div>
                </td>
            @endforeach
        </tr>
    </table>

    <table class="ck-stat">
        <tr>
            <td class="c-ok"><b>{{ $kpi['stats']['active'] }}</b><br>عامل</td>
            <td class="c-bad"><b>{{ $kpi['stats']['faulty'] }}</b><br>معطّل</td>
            <td class="c-mid"><b>{{ $kpi['stats']['maintenance'] }}</b><br>تحت الصيانة</td>
            <td class="c-off"><b>{{ $kpi['stats']['retired'] }}</b><br>خارج الخدمة</td>
        </tr>
    </table>
@endif

<table class="ck-stat">
    <tr>
        <td class="c-info"><b>{{ $kpi['fiber'] }}</b><br>الألياف البصرية</td>
        <td class="c-info"><b>{{ $kpi['workshops'] }}</b><br>الورش</td>
        <td class="c-info"><b>{{ $kpi['staff'] }}</b><br>الطاقم الفني</td>
    </tr>
</table>
