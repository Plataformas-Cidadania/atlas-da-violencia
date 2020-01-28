@extends('layout')
@section('title', 'Estações')
@section('content')




    <link rel="stylesheet" href="js/lib/leaflet/leaflet.css"/>
    <script src="js/lib/leaflet/leaflet.js"></script>
    <script src="js/lib/leaflet/jquery.min.js"></script>
    <script src="js/lib/leaflet/Control.FullScreen.js"></script>

        <style type="text/css">
             .fullscreen-icon { background-image: url(img/icon-fullscreen.png); }
             #mapSeasons:-webkit-full-screen { width: 100% !important; height: 100% !important; z-index: 99999; }
             #mapSeasons:-ms-fullscreen { width: 100% !important; height: 100% !important; z-index: 99999; }
             #mapSeasons:full-screen { width: 100% !important; height: 100% !important; z-index: 99999; }
             #mapSeasons:fullscreen { width: 100% !important; height: 100% !important; z-index: 99999; }
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


            .icons-circle{
                border: solid 10px #CCCCCC;
                width: 160px;
                height: 160px;
                margin: 0 auto 30px auto;

            }
            .icons-circle img{
                margin-top: 20px;
            }

            .icons-circle-int{
                box-shadow: 0 0 5px #333333;
                width: 145px;
                height: 145px;
                margin: -3px 0 0 -3px ;
                transform: scale(1.0);
                transition: 0.5s ease-in;
            }

            .icons-circle-int h2{
                margin-top: 8px;
                font-weight: bolder;
            }

            .icons-circle-int:hover{
                box-shadow: 0 0 5px #333333;
                width: 145px;
                height: 145px;
                margin: -3px 0 0 -3px ;
                transform: scale(1.3);
                transition: 0.5s ease-in;
            }

            .icon-style{
                border:  solid 2px #CCCCCC;
                border-radius: 50px;
                width: 30px;
                height: 30px;
                padding: 2px;
                float: left;
                margin: 2px;
            }
             .icon-style:hover {
                 transform: scale(1.3);
                 transition: 0.5s ease-in;
             }
            .box-list{
                padding: 20px 10px;
                text-align: center;
            }
            .tamanhoMinimo{
                min-height: 50px;
                margin: 0 10px;
            }


        </style>

        <div class="row text-center bg-ter">
            <div class="col-md-12">
                <br>
                <h1>Estações</h1>
                <br>
            </div>
        </div>

        <br>
        <div id="page"></div>




@endsection
