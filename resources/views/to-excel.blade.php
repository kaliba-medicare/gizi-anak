<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Posyandu - sigizikesga.kemkes.go.id</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h3 {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        thead {
            background-color: #f8f8f8;
        }
        th, td {
            border: 1px solid #999;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        tbody tr:nth-child(even) {
            background-color: #fcfcfc;
        }
    </style>
</head>
<body>
    <h3>Daftar Posyandu berdasarkan data sigizikesga.kemkes.go.id</h3>

    @foreach($dataGizi_posyandu as $desa => $posyandus)
                <h3>{{ $desa }}</h3>
                <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                    <thead style="background-color: #f0f0f0;">
                        <tr>
                            <th style="width: 50%;">Posyandu dari Database Lokal</th>
                            <th style="width: 50%;">Posyandu dari sigizikesga.kemkes.go.id</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $lokal = $posyandus->where('sumber', 'database')->pluck('posyandu')->values();
                            $sigizi = $posyandus->where('sumber', 'sigizikesga')->pluck('posyandu')->values();
                            $max = max($lokal->count(), $sigizi->count());
                        @endphp

                        @for($i = 0; $i < $max; $i++)
                            <tr>
                                <td>{{ $lokal[$i] ?? '-' }}</td>
                                <td>{{ $sigizi[$i] ?? '-' }}</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
                <br>
            @endforeach
</body>
</html>
