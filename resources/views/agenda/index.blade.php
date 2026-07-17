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
            position:relative;
            height:720px;
            background:#fff;
        }

        .slot{
            height:36px;
            padding:4px 8px;
            border-bottom:1px dashed #d1d5db;
            box-sizing:border-box;
            transition:background .15s;
        }

        .slot:nth-child(even){
            border-bottom-color:#e5e7eb;
        }

        .slot.drag-over{
            background:#dbeafe;
        }

        .hora{
            font-size:12px;
            color:#666;
        }

        .card{
            position:absolute;
            left:48px;
            right:8px;
            z-index:2;
            box-sizing:border-box;
            overflow:hidden;
            background:#dbeafe;
            border-left:5px solid #2563eb;
            padding:8px 8px 12px;
            border-radius:4px;
            box-shadow:0 2px 5px rgba(37, 99, 235, .18);
            cursor:grab;
        }

        .card.dragging{
            opacity:.55;
        }

        .card-horario{
            margin-bottom:4px;
            color:#1d4ed8;
            font-size:11px;
            font-weight:600;
        }

        .resize-handle{
            position:absolute;
            right:0;
            bottom:0;
            left:0;
            height:10px;
            cursor:ns-resize;
            touch-action:none;
        }

        .resize-handle::after{
            content:'';
            position:absolute;
            bottom:3px;
            left:50%;
            width:28px;
            height:3px;
            border-radius:2px;
            background:#60a5fa;
            transform:translateX(-50%);
        }

        .card.resizing{
            cursor:ns-resize;
            user-select:none;
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

@for($minuto = 8 * 60; $minuto < 18 * 60; $minuto += 30)

@php
    $horaSlot = sprintf('%02d:%02d', intdiv($minuto, 60), $minuto % 60);
@endphp

<div class="slot"

    data-tecnico="{{ $tecnico->id }}"
    data-hora="{{ $horaSlot }}">

<div class="hora">

{{ $minuto % 60 === 0 ? $horaSlot : '' }}

</div>

</div>

@endfor

@foreach($agenda[$tecnico->id] ?? collect() as $item)

@php
    $inicio = \Carbon\Carbon::parse($item->hora_inicio);
    $fim = $item->hora_fim
        ? \Carbon\Carbon::parse($item->hora_fim)
        : $inicio->copy()->addHour();
    $minutosInicio = ($inicio->hour * 60 + $inicio->minute) - (8 * 60);
    $duracao = ($fim->hour * 60 + $fim->minute) - ($inicio->hour * 60 + $inicio->minute);
@endphp

<div class="card"
    data-id="{{ $item->id }}"
    data-tecnico="{{ $tecnico->id }}"
    data-inicio="{{ $inicio->format('H:i') }}"
    data-fim="{{ $fim->format('H:i') }}"
    data-duracao="{{ $duracao }}"
    draggable="true"
    style="top:{{ $minutosInicio * 1.2 }}px; height:{{ max($duracao * 1.2 - 4, 32) }}px;">

    <div class="card-horario">
        {{ $inicio->format('H:i') }}–<span class="card-hora-fim">{{ $fim->format('H:i') }}</span>
    </div>

    <strong>{{ $item->osTecnico->ordem_servico }}</strong>

    <div>{{ $item->osTecnico->titulo }}</div>

    <small>{{ $item->osTecnico->regiao }} · {{ $item->osTecnico->status }}</small>

    <div class="resize-handle" title="Arraste para alterar a duração"></div>

</div>

@endforeach

</div>

</div>

@endforeach

</div>
<script>

const cards = document.querySelectorAll('.card');

const ALTURA_SLOT = 36;
const MINUTOS_SLOT = 30;
const PIXELS_POR_MINUTO = ALTURA_SLOT / MINUTOS_SLOT;

async function lerErro(response, mensagemPadrao) {

    try {

        const data = await response.json();

        return data.message || mensagemPadrao;

    } catch {

        return mensagemPadrao;

    }

}

cards.forEach(card => {

    card.addEventListener('dragstart', e => {

        if (card.classList.contains('resizing')) {

            e.preventDefault();

            return;

        }

        e.dataTransfer.setData('id', card.dataset.id);

        card.classList.add('dragging');

    });

    card.addEventListener('dragend', () => {

        card.classList.remove('dragging');

    });

    const handle = card.querySelector('.resize-handle');

    handle.addEventListener('pointerdown', e => {

        e.preventDefault();

        e.stopPropagation();

        const inicioY = e.clientY;
        const duracaoOriginal = Number(card.dataset.duracao);
        const alturaOriginal = card.style.height;
        const fimOriginal = card.dataset.fim;
        const [horaInicio, minutoInicio] = card.dataset.inicio.split(':').map(Number);
        const inicioEmMinutos = horaInicio * 60 + minutoInicio;
        const duracaoMaxima = 18 * 60 - inicioEmMinutos;

        let novaDuracao = duracaoOriginal;

        card.classList.add('resizing');
        card.draggable = false;
        handle.setPointerCapture(e.pointerId);

        const redimensionar = evento => {

            const passos = Math.round((evento.clientY - inicioY) / ALTURA_SLOT);

            novaDuracao = Math.min(
                duracaoMaxima,
                Math.max(MINUTOS_SLOT, duracaoOriginal + passos * MINUTOS_SLOT),
            );

            card.style.height = `${Math.max(novaDuracao * PIXELS_POR_MINUTO - 4, 32)}px`;

            const fimEmMinutos = inicioEmMinutos + novaDuracao;
            const novoFim = `${String(Math.floor(fimEmMinutos / 60)).padStart(2, '0')}:${String(fimEmMinutos % 60).padStart(2, '0')}`;

            card.querySelector('.card-hora-fim').textContent = novoFim;

        };

        const finalizar = async evento => {

            handle.releasePointerCapture(evento.pointerId);
            handle.removeEventListener('pointermove', redimensionar);
            handle.removeEventListener('pointerup', finalizar);
            handle.removeEventListener('pointercancel', cancelar);

            card.classList.remove('resizing');
            card.draggable = true;

            if (novaDuracao === duracaoOriginal) {

                return;

            }

            const fimEmMinutos = inicioEmMinutos + novaDuracao;
            const novoFim = `${String(Math.floor(fimEmMinutos / 60)).padStart(2, '0')}:${String(fimEmMinutos % 60).padStart(2, '0')}`;

            const response = await fetch(`/agenda/${card.dataset.id}/duracao`, {

                method:'PATCH',

                headers:{

                    'Content-Type':'application/json',

                    'Accept':'application/json',

                    'X-CSRF-TOKEN':'{{ csrf_token() }}'

                },

                body:JSON.stringify({ hora_fim:novoFim })

            });

            if (!response.ok) {

                card.style.height = alturaOriginal;
                card.querySelector('.card-hora-fim').textContent = fimOriginal;

                alert(await lerErro(response, 'Não foi possível alterar a duração.'));

                return;

            }

            card.dataset.duracao = novaDuracao;
            card.dataset.fim = novoFim;

        };

        const cancelar = evento => {

            card.style.height = alturaOriginal;
            card.querySelector('.card-hora-fim').textContent = fimOriginal;
            novaDuracao = duracaoOriginal;

            finalizar(evento);

        };

        handle.addEventListener('pointermove', redimensionar);
        handle.addEventListener('pointerup', finalizar);
        handle.addEventListener('pointercancel', cancelar);

    });

});

document.querySelectorAll('.slot').forEach(slot => {

    slot.addEventListener('dragover', e => {

        e.preventDefault();

        slot.classList.add('drag-over');

    });

    slot.addEventListener('dragleave', () => {

        slot.classList.remove('drag-over');

    });

    slot.addEventListener('drop', async e => {

        e.preventDefault();

        slot.classList.remove('drag-over');

        const id = e.dataTransfer.getData('id');

        const tecnico = slot.dataset.tecnico;

        const hora = slot.dataset.hora;

        const response = await fetch(`/agenda/${id}/mover`,{

            method:'POST',

            headers:{

                'Content-Type':'application/json',

                'Accept':'application/json',

                'X-CSRF-TOKEN':'{{ csrf_token() }}'

            },

            body:JSON.stringify({

                tecnico_id:tecnico,

                hora_inicio:hora

            })

        });

        if(response.ok){

            location.reload();

            return;

        }

        alert(await lerErro(response, 'Não foi possível mover a atividade.'));

    });

});

</script>
</body>
</html>
