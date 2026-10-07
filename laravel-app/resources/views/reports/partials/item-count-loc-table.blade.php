@if(count($rows))
    <table>
        <thead>
            <tr><th>النوع</th><th>العدد</th><th>الموقع</th></tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <tr>
                <td>{{ $row['item'] }}</td>
                <td>{{ $row['count'] }}</td>
                <td>{{ $row['loc'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="no-data">لا توجد بيانات</div>
@endif
