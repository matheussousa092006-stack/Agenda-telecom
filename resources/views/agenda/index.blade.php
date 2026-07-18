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
            min-width:max-content;
            align-items:flex-start;
        }

        .agenda-scroll{
            max-height:calc(100vh - 250px);
            margin:20px;
            overflow:auto;
            border:1px solid #e5e7eb;
            border-radius:8px;
            background:#fff;
        }

        .tecnico{

            width:300px;
            flex:0 0 300px;
            background:white;
            overflow:visible;

        }

        .titulo{

            position:sticky;
            top:0;
            z-index:5;
            display:flex;
            justify-content:space-between;
            align-items:center;

            background:#2563eb;
            color:white;
            padding:15px;
            font-weight:bold;

        }

        .horarios{
            position:relative;
            height:1692px;
            background:#fff;
        }

        .titulo small{
            color:#dbeafe;
            font-size:12px;
            font-weight:500;
        }

        .agenda.semanal .tecnico{
            width:240px;
            flex-basis:240px;
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

        .filtros-agenda{
            display:flex;
            align-items:flex-end;
            gap:12px;
            flex-wrap:wrap;
        }

        .campo-filtro label{
            display:block;
            margin-bottom:6px;
            color:#4b5563;
            font-size:13px;
            font-weight:600;
        }

        .campo-filtro select{
            min-width:170px;
            padding:10px 36px 10px 12px;
            color:#1f2937;
            background:#fff;
            border:1px solid #d1d5db;
            border-radius:8px;
            font-size:14px;
            cursor:pointer;
        }

        .campo-filtro select:focus{
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

            .filtros-agenda{
                align-items:stretch;
                flex-direction:column;
            }

            .campo-filtro select{
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

@php
    $parametrosNavegacao = [
        'visao' => $visao,
        'regiao' => $regiao,
    ];

    if ($tecnicoSelecionado) {
        $parametrosNavegacao['tecnico'] = $tecnicoSelecionado->id;
    }

    $dataAnterior = $visao === 'semanal'
        ? $semanaInicio->copy()->subWeek()->toDateString()
        : \Carbon\Carbon::parse($dataSelecionada)->subDay()->toDateString();
    $dataPosterior = $visao === 'semanal'
        ? $semanaInicio->copy()->addWeek()->toDateString()
        : \Carbon\Carbon::parse($dataSelecionada)->addDay()->toDateString();
@endphp

<div class="painel-agenda">
<div class="cabecalho">

    <div>
        <h1>Agenda Telecom</h1>

        <p>{{ $visao === 'semanal' ? 'Programação semanal do técnico' : 'Programação diária dos técnicos' }}</p>
    </div>

    <form method="GET" class="filtros-agenda">
        <input type="hidden" name="data" value="{{ $dataSelecionada }}">

        <div class="campo-filtro">
            <label for="visao">Visualização</label>

            <select id="visao" name="visao" onchange="this.form.submit()">
                <option value="diaria" {{ $visao === 'diaria' ? 'selected' : '' }}>Diária</option>
                <option value="semanal" {{ $visao === 'semanal' ? 'selected' : '' }}>Semanal</option>
            </select>
        </div>

        <div class="campo-filtro">
            <label for="regiao">Região</label>

            <select id="regiao" name="regiao" onchange="this.form.submit()">
                <option value="VA" {{ $regiao === 'VA' ? 'selected' : '' }}>Vale do Aço</option>
                <option value="GV" {{ $regiao === 'GV' ? 'selected' : '' }}>Governador Valadares</option>
            </select>
        </div>

        @if($visao === 'semanal')
            <div class="campo-filtro">
                <label for="tecnico">Técnico</label>

                <select id="tecnico" name="tecnico" onchange="this.form.submit()">
                    @foreach($tecnicos as $tecnico)
                        <option value="{{ $tecnico->id }}" {{ $tecnicoSelecionado?->id === $tecnico->id ? 'selected' : '' }}>
                            {{ $tecnico->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
    </form>

</div>

<div class="header-agenda">

    <a
        class="btn-data"
        href="{{ route('agenda.index', [...$parametrosNavegacao, 'data' => $dataAnterior]) }}"
    >
        ◀
    </a>

    <div class="data-atual">

    @if($visao === 'semanal')
        {{ $semanaInicio->format('d/m') }} a {{ $semanaFim->format('d/m/Y') }}
    @else
        {{ \Carbon\Carbon::parse($dataSelecionada)->format('d/m/Y') }}
    @endif

    <div class="dia-semana">

        {{ $visao === 'semanal' ? 'segunda-feira a domingo' : \Carbon\Carbon::parse($dataSelecionada)->translatedFormat('l') }}

    </div>

</div>
    <a
        class="btn-data"
        href="{{ route('agenda.index', [...$parametrosNavegacao, 'data' => $dataPosterior]) }}"
    >
        ▶
    </a>

    <a
        class="btn-hoje"
        href="{{ route('agenda.index', [...$parametrosNavegacao, 'data' => now()->toDateString()]) }}"
    >
        {{ $visao === 'semanal' ? 'Esta semana' : 'Hoje' }}
    </a>

</div>
</div>


<div class="agenda-scroll">
<div class="agenda {{ $visao }}">
    @include('agenda.partials.grade')
</div>
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
        const duracaoMaxima = 23 * 60 + 30 - inicioEmMinutos;

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

        const data = slot.dataset.data;

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

                data:data,

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

const agendaScroll = document.querySelector('.agenda-scroll');

if (agendaScroll) {

    const posicoes = [...cards].map(card => Number.parseFloat(card.style.top));
    const primeiraPosicao = posicoes.length
        ? Math.min(...posicoes)
        : (new Date().getHours() * 60 + new Date().getMinutes()) * PIXELS_POR_MINUTO;

    agendaScroll.scrollTop = Math.max(0, primeiraPosicao - ALTURA_SLOT * 2);

}

</script>
</body>
</html>
