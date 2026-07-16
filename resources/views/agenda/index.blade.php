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

        .cabecalho{

    text-align:center;

    padding:25px 0 10px;

}

        .cabecalho h1{

    margin:0;

    font-size:44px;

    color:#1f2937;

}

        .cabecalho p{

    margin-top:8px;

    color:#6b7280;

    font-size:16px;

}

        h1{
            margin:20px;
        }

        .header-agenda{

    display:flex;
    justify-content:center;
    align-items:center;
    gap:20px;
    margin-bottom:20px;

}

.btn-data{

    width:45px;
    height:45px;

    display:flex;
    align-items:center;
    justify-content:center;

    background:#2563eb;
    color:white;

    text-decoration:none;

    border-radius:50%;

    font-size:22px;
    font-weight:bold;

    transition:.2s;

}

.btn-data:hover{

    background:#1d4ed8;

}

.data-atual{

    font-size:28px;
    font-weight:bold;
    color:#222;

    min-width:180px;
    text-align:center;

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

    </style>

</head>

<body>

<div class="cabecalho">

    <h1>Agenda Telecom</h1>

    <p>Programação diária dos técnicos</p>

</div>


<div class="header-agenda">

    <a
        class="btn-data"
        href="/?data={{ \Carbon\Carbon::parse($dataSelecionada)->copy()->subDay()->toDateString() }}"
    >
        ◀
    </a>

    <div class="data-atual">

    {{ \Carbon\Carbon::parse($dataSelecionada)->format('d/m/Y') }}

    <div class="dia-semana">

        {{ \Carbon\Carbon::parse($dataSelecionada)->translatedFormat('l') }}

       .dia-semana{

       margin-top:8px;

       font-size:15px;

       color:#6b7280;

       font-weight:normal;

     }


    </div>

</div>
    <a
        class="btn-data"
        href="/?data={{ \Carbon\Carbon::parse($dataSelecionada)->copy()->addDay()->toDateString() }}"
    >
        ▶
    </a>

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