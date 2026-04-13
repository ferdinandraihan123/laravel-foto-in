<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Log Aktivitas</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 5px 0;
            font-size: 10px;
            color: #666;
        }

        .filter-info {
            margin-bottom: 15px;
            padding: 8px;
            background: #f5f5f5;
            border-radius: 4px;
            font-size: 10px;
        }

        .filter-info p {
            margin: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #4CAF50;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
        }

        td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN LOG AKTIVITAS</h1>
        <p>Dicetak: {{ $filter['export_date'] }} | Oleh: {{ $filter['export_by'] }}</p>
    </div>

    <div class="filter-info">
        <p><strong>Filter:</strong>
            @if($filter['user'] != 'Semua User') User: {{ $filter['user'] }} | @endif
            @if($filter['tanggal'] != 'Semua Tanggal') Tanggal: {{ $filter['tanggal'] }} | @endif
            @if($filter['dari_tanggal'] != '-') Periode: {{ $filter['dari_tanggal'] }} s/d {{ $filter['sampai_tanggal']
            }} | @endif
            Total: {{ $filter['total'] }} record
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Waktu</th>
                <th width="12%">User</th>
                <th width="10%">Role</th>
                <th width="25%">Aktivitas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $index => $log)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                <td>{{ $log->user->name ?? 'System' }}</td>
                <td>{{ $log->user->role ?? '-' }}</td>
                <td>{{ $log->aktivitas }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data log aktivitas</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
</body>

</html>