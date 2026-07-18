@foreach($colunas as $coluna)

<div class="tecnico">

    <div class="titulo">
        <span>{{ $coluna['titulo'] }}</span>

        @if($coluna['subtitulo'])
            <small>{{ $coluna['subtitulo'] }}</small>
        @endif
    </div>

    <div class="horarios">

        @for($minuto = 0; $minuto <= 23 * 60; $minuto += 30)

            @php
                $horaSlot = sprintf('%02d:%02d', intdiv($minuto, 60), $minuto % 60);
            @endphp

            <div
                class="slot"
                data-tecnico="{{ $coluna['tecnico_id'] }}"
                data-data="{{ $coluna['data'] }}"
                data-hora="{{ $horaSlot }}"
            >
                <div class="hora">
                    {{ $minuto % 60 === 0 ? $horaSlot : '' }}
                </div>
            </div>

        @endfor

        @foreach($coluna['itens'] as $item)

            @php
                $inicio = \Carbon\Carbon::parse($item->hora_inicio);
                $fim = $item->hora_fim
                    ? \Carbon\Carbon::parse($item->hora_fim)
                    : $inicio->copy()->addHour();
                $minutosInicio = $inicio->hour * 60 + $inicio->minute;
                $duracao = ($fim->hour * 60 + $fim->minute)
                    - ($inicio->hour * 60 + $inicio->minute);
            @endphp

            <div
                class="card"
                data-id="{{ $item->id }}"
                data-tecnico="{{ $coluna['tecnico_id'] }}"
                data-data="{{ $coluna['data'] }}"
                data-inicio="{{ $inicio->format('H:i') }}"
                data-fim="{{ $fim->format('H:i') }}"
                data-duracao="{{ $duracao }}"
                draggable="true"
                style="top:{{ $minutosInicio * 1.2 }}px; height:{{ max($duracao * 1.2 - 4, 32) }}px;"
            >
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
