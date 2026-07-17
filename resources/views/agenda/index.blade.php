<!DOCTYPE html>
<html lang="pt-br">
<head>

    <meta charset="UTF-8">

    <title>Agenda Telecom</title>

    <style>

        body{
            margin:0;
            font-family:Arial, Helvetica, sans-serif;
            background:#f4f6f9;
        }

        .painel-agenda{
            margin:24px 20px 0;
            background:#fff;
            border:1px solid #e5e7eb;
            border-radius:12px;
            box-shadow:0 2px 8px rgba(15, 23, 42, .06);
            overflow:hidden;
        }

        .cabecalho{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:24px;
            padding:24px 28px;
        }

        .cabecalho h1{
            margin:0;
            font-size:28px;
            color:#111827;
        }

        .cabecalho p{
            margin:6px 0 0;
            color:#6b7280;
            font-size:15px;
        }

        .header-agenda{
            display:flex;
            align-items:center;
            gap:12px;
            padding:18px 28px;
            border-top:1px solid #e5e7eb;
            background:#f9fafb;
        }

        .btn-data{
            width:38px;
            height:38px;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#fff;
            color:#374151;
            border:1px solid #d1d5db;
            text-decoration:none;
            border-radius:8px;
            font-size:20px;
            font-weight:bold;
            transition:.2s;
        }

        .btn-data:hover{
            color:#2563eb;
            border-color:#2563eb;
            background:#eff6ff;
        }

        .btn-hoje{
            padding:10px 16px;
            color:#fff;
            background:#2563eb;
            border-radius:8px;
            font-size:14px;
            font-weight:600;
            text-decoration:none;
            transition:.2s;
        }

        .btn-hoje:hover{
            background:#1d4ed8;
        }

        .data-atual{
            min-width:160px;
            color:#111827;
            font-size:20px;
            font-weight:700;
            text-align:center;
        }

        .dia-semana{
            margin-top:4px;
            color:#6b7280;
            font-size:14px;
            font-weight:normal;
            text-transform:capitalize;
        }

        .agenda{
            display:flex;
            gap:20px;
            padding:20px;
            align-items:flex-start;
        }

        .tecnico{

            width:300px;
            background:white;
            border-radius:8px;
            box-shadow:0 2px 6px rgba(0,0,0,.1);
            overflow:hidden;

        }

        .titulo{

            background:#2563eb;
            color:white;
            padding:15px;
            font-weight:bold;

        }

        .horarios{

            padding:10px;

        }

        .slot{

            min-height:60px;
            border:1px dashed #ccc;
            margin-bottom:10px;
            padding:8px;

        }

        .hora{

            font-size:12px;
            color:#666;
            margin-bottom:5px;

        }

        .card{

            background:#dbeafe;
            border-left:5px solid #2563eb;
            padding:8px;
            border-radius:4px;
            margin-top:5px;

        }

        .filtro-regiao label{
            display:block;
            margin-bottom:6px;
            color:#4b5563;
            font-size:13px;
            font-weight:600;
        }

        .filtro-regiao select{
            min-width:220px;
            padding:10px 36px 10px 12px;
            color:#1f2937;
            background:#fff;
            border:1px solid #d1d5db;
            border-radius:8px;
            font-size:14px;
            cursor:pointer;
        }

        .filtro-regiao select:focus{
            border-color:#2563eb;
            outline:3px solid #dbeafe;
        }

        @media (max-width: 640px){
            .painel-agenda{
                margin:12px 12px 0;
            }

            .cabecalho{
                align-items:stretch;
                flex-direction:column;
                padding:20px;
            }

            .filtro-regiao select{
                width:100%;
            }

            .header-agenda{
                justify-content:center;
                flex-wrap:wrap;
                padding:16px 20px;
            }
        }

    </style>

</head>

<body>

<div class="painel-agenda">
<div class="cabecalho">

    <div>
        <h1>Agenda Telecom</h1>

        <p>Programação diária dos técnicos</p>
    </div>

    <div class="filtro-regiao">

    <form method="GET">

        <label for="regiao">Região</label>

        <select id="regiao" name="regiao" onchange="this.form.submit()">

            <option value="VA" {{ $regiao == 'VA' ? 'selected' : '' }}>
                Vale do Aço
            </option>

            <option value="GV" {{ $regiao == 'GV' ? 'selected' : '' }}>
                Governador Valadares
            </option>

        </select>

        <input
            type="hidden"
            name="data"
            value="{{ $dataSelecionada }}"
        >

    </form>

    </div>

</div>

<div class="header-agenda">

    <a
        class="btn-data"
        href="{{ url('/?' . http_build_query(['regiao' => $regiao, 'data' => \Carbon\Carbon::parse($dataSelecionada)->copy()->subDay()->toDateString()])) }}"
    >
        ◀
    </a>

    <div class="data-atual">

    {{ \Carbon\Carbon::parse($dataSelecionada)->format('d/m/Y') }}

    <div class="dia-semana">

        {{ \Carbon\Carbon::parse($dataSelecionada)->translatedFormat('l') }}

    </div>

</div>
    <a
        class="btn-data"
        href="{{ url('/?' . http_build_query(['regiao' => $regiao, 'data' => \Carbon\Carbon::parse($dataSelecionada)->copy()->addDay()->toDateString()])) }}"
    >
        ▶
    </a>

    <a
        class="btn-hoje"
        href="{{ url('/?' . http_build_query(['regiao' => $regiao, 'data' => now()->toDateString()])) }}"
    >
        Hoje
    </a>

</div>
</div>


<div class="agenda">

@foreach($tecnicos as $tecnico)

<div class="tecnico">

<div class="titulo">

{{ $tecnico->nome }}

</div>

<div class="horarios">

@for($hora = 8; $hora <= 17; $hora++)

<div class="slot"

    data-tecnico="{{ $tecnico->id }}"
    data-hora="{{ sprintf('%02d:00',$hora) }}">

<div class="hora">

{{ sprintf('%02d:00',$hora) }}

</div>

@if(isset($agenda[$tecnico->id]))



    @foreach($agenda[$tecnico->id] as $item)

@if(substr($item->hora_inicio, 0, 2) == sprintf('%02d', $hora))

<div class="card"
    data-id="{{ $item->id }}"
    data-tecnico="{{ $tecnico->id }}"
    draggable="true">

    <strong>{{ $item->osTecnico->ordem_servico }}</strong>

    <div>{{ $item->osTecnico->titulo }}</div>

    <small>{{ $item->osTecnico->regiao }}</small>

    <br>

    <small>Status: {{ $item->osTecnico->status }}</small>

</div>

@endif

@endforeach


@foreach($agenda[$tecnico->id] as $item)

@if(optional($item->hora_inicio)->format('H') == sprintf('%02d',$hora))

<div class="card"

    data-id="{{ $item->id }}"
    data-tecnico="{{ $tecnico->id }}"
    draggable="true">


<strong>{{ $item->osTecnico->ordem_servico }}</strong>

<br>

{{ $item->osTecnico->titulo }}

</div>

@endif

@endforeach

@endif

</div>

@endfor

</div>

</div>

@endforeach

</div>
<script>

const cards = document.querySelectorAll('.card');

cards.forEach(card => {

    card.addEventListener('dragstart', e => {

        e.dataTransfer.setData('id', card.dataset.id);

    });

});

document.querySelectorAll('.slot').forEach(slot => {

    slot.addEventListener('dragover', e => {

        e.preventDefault();

    });

    slot.addEventListener('drop', async e => {

        e.preventDefault();

        const id = e.dataTransfer.getData('id');

        const tecnico = slot.dataset.tecnico;

        const hora = slot.dataset.hora;

        const response = await fetch(`/agenda/${id}/mover`,{

            method:'POST',

            headers:{

                'Content-Type':'application/json',

                'X-CSRF-TOKEN':'{{ csrf_token() }}'

            },

            body:JSON.stringify({

                tecnico_id:tecnico,

                hora_inicio:hora

            })

        });

        if(response.ok){

            location.reload();

        }

    });

});

</script>
</body>
</html>
