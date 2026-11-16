<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    @include('reports.partials.security-style')
</head>
<body>
    <h1>تقرير الأنظمة الأمنية</h1>
    <p class="sub">{{ now()->format('Y-m-d H:i') }}</p>

    @include('reports.partials.security-region', ['region' => $region])
</body>
</html>
