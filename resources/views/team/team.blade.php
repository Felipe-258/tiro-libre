@extends('screens.app')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('team/style.css') }}">
    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Equipo de Desarrollo</h1>
            </div>
        </div>

        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Participantes</h3>
                </div>
                <div class="team-container">
                    <div class="team-member">
                        <a href="https://github.com/Felipe-258" target="_blank">
                            <img src="{{ asset('team/feli-circle.png') }}" alt="Feli">
                        </a>
                        <p>Franco Felipe</p>
                        <p>Desarrollador</p>
                    </div>
                    <div class="team-member">
                        <a href="https://github.com/P2jaraFacundo" target="_blank">
                            <img src="{{ asset('team/facu-circle.png') }}" alt="Facu">
                        </a>
                        <p>Jara Facundo</p>
                        <p>Desarrollador</p>
                    </div>
                    <div class="team-member">
                        <a href="https://github.com/FedericoMartinolich" target="_blank">
                            <img src="{{ asset('team/fede-circle.png') }}" alt="Fede">
                        </a>
                        <p>Martinolich Federico</p>
                        <p>Desarrollador</p>

                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- MAIN -->

    <script src="script.js"></script>
@endsection
