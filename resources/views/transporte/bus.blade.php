@extends('.layout')
@section('title', 'Bus')
@section('content')



    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.min.js"></script>
    <link rel="stylesheet" href="js/lib/leaflet/leaflet.css"/>
    <script src="js/lib/leaflet/leaflet.js"></script>
    <script src="js/lib/leaflet/jquery.min.js"></script>
    <script src="js/lib/leaflet/Control.FullScreen.js"></script>

        <style type="text/css">
             .fullscreen-icon { background-image: url(img/icon-fullscreen.png); }
             #mapBus:-webkit-full-screen { width: 100% !important; height: 100% !important; z-index: 99999; }
             #mapBus:-ms-fullscreen { width: 100% !important; height: 100% !important; z-index: 99999; }
             #mapBus:full-screen { width: 100% !important; height: 100% !important; z-index: 99999; }
             #mapBus:fullscreen { width: 100% !important; height: 100% !important; z-index: 99999; }
             .leaflet-pseudo-fullscreen { position: fixed !important; width: 100% !important; height: 100% !important; top: 0px !important; left: 0px !important; z-index: 99999; }



            .box-pesquisa{
                border: solid 1px #CCCCCC;
                padding: 10px;
                margin-bottom: 10px;
            }
            .box-pesquisa h2{
                margin: 0;
                padding: 0;
                font-size: 16px;
            }
            .box-icons{
                padding: 20px;
                min-height: 310px;
            }
            .box-icons i{
                margin: 10px;
                color: #555555;
            }
             .box-icons i:hover{
                 transform: scale(1.3);
                 transition: 0.5s ease-in;
             }
            .box-icons h2{
                font-weight: bold;
            }

            .menu-center{
                margin: 0;
                padding: 0;
            }

            .menu-center li{
                margin: 2px;
                padding: 5px;
                list-style: none;
                display: block;
                float: left;
                cursor: pointer;
            }
            .menu-center li:hover{
                background-color: #EEEEEE;
            }
            .menu-center-marker{
                background-color: #1B559F;
            }
        </style>

        <div class="row text-center bg-ter">
            <div class="col-md-12">
                <br/>
                <h1>Ônibus</h1>
                <br>
            </div>
        </div>

        <br>
        <div id="page"></div>


@endsection
