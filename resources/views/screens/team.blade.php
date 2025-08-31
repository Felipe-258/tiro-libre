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
                        <a href="https://open.spotify.com/show/7Gw9T9Yg4JkDszJKjehvwT?si=b2d9984dad4a4341" target="_blank">
                            <img src="{{ asset('team/feli-circle.png') }}" alt="Feli">
                        </a>
                        <p>Feli</p>
                        <p>Desarrollador</p>
                    </div>
                    <div class="team-member">
                        <a href="https://www.instagram.com/_facuujara_/" target="_blank">
                            <img src="{{ asset('team/facu-circle.png') }}" alt="Facu">
                        </a>
                        <p>Facu</p>
                        <p>Desarrollador</p>
                    </div>
                    <div class="team-member">
                        <a href="https://www.instagram.com/federico.martinolich/" target="_blank">
                            <img src="{{ asset('team/fede-circle.png') }}" alt="Fede">
                        </a>
                        <p>Fede</p>
                        <p>Desarrollador</p>

                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- MAIN -->

    <script src="script.js"></script>
@endsection
