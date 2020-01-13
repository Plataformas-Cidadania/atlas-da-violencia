@extends('.layout')
@section('title', 'Brt')
@section('content')




    <link rel="stylesheet" href="js/lib/leaflet/leaflet.css"/>
    <script src="js/lib/leaflet/leaflet.js"></script>
    <script src="js/lib/leaflet/jquery.min.js"></script>
    <script src="js/lib/leaflet/Control.FullScreen.js"></script>

        <style type="text/css">
             .fullscreen-icon { background-image: url(img/icon-fullscreen.png); }
             #mapBrt:-webkit-full-screen { width: 100% !important; height: 100% !important; z-index: 99999; }
             #mapBrt:-ms-fullscreen { width: 100% !important; height: 100% !important; z-index: 99999; }
             #mapBrt:full-screen { width: 100% !important; height: 100% !important; z-index: 99999; }
             #mapBrt:fullscreen { width: 100% !important; height: 100% !important; z-index: 99999; }
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

            .linhas{
                width: 15px;
                height: 15px;
                border-radius: 50%;
                float: left;
                margin: 2px;
            }
            .linha-trans-brasil{
                background-color: #CCCCCC;
            }
            .linha-trans-carioca{
                background-color: #F37E01;
            }
            .linha-trans-oeste{
                background-color: #0099DA;
            }
            .linha-trans-olimpica{
                background-color: #039443;
            }
            .linha-off{
                display: none;
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
                <br>
                <h1>BRT</h1>
                <br>
            </div>
        </div>

        <br>
        <div id="page"></div>




@endsection
