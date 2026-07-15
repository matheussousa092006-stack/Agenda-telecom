<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Agenda Telecom</title>

    <style>

        body{
            font-family: Arial, Helvetica, sans-serif;
            margin:40px;
            background:#f5f5f5;
        }

        table{
            width:100%;
            border-collapse:collapse;
            background:white;
        }

        th,td{
            padding:12px;
            border:1px solid #ddd;
            text-align:left;
        }

        th{
            background:#1f2937;
            color:white;
        }

        tr:nth-child(even){
            background:#f9f9f9;
        }

    </style>

</head>

<body>

<h1>Agenda Telecom</h1>

@foreach($tecnicos as $nome => $lista)

    <h2>{{ $nome }}</h2>

    <table>

        <thead>

        <tr>
            <th>OS</th>
            <th>Título</th>
            <th>Região</th>
            <th>Status</th>
        </tr>

        </thead>

        <tbody>

        @foreach($lista as $os)

            <tr>

                <td>{{ $os->ordem_servico }}</td>
                <td>{{ $os->titulo }}</td>
                <td>{{ $os->regiao }}</td>
                <td>{{ $os->status }}</td>

            </tr>

        @endforeach

        </tbody>

    </table>

    <br><br>

@endforeach

</body>