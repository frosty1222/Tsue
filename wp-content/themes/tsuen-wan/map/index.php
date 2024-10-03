<?php
$scroll_zoom = isset($_GET['scrollZoom']) ? $_GET['scrollZoom'] : true;
$dragging = isset($_GET['dragging']) ? $_GET['dragging'] : true;
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link href="vendor/leaflet/leaflet.css" rel="stylesheet">
        <style>
            html{
                height: 100%;
            }
            html,body{
                border: 0 none;
                margin: 0;
                padding: 0;
            }
            body{
                position: relative;
                min-height: 100%;
            }
            #map{
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
            }
            .controls{
                position: absolute;
                z-index: 1000;
            }
            .zoom{
                top: 20px;
                right: 20px;
            }
            .zoom a{
                display: block;
            }
            .zoom a img{
                vertical-align: top;
                width: 50px;
            }
            @media (max-width: 991px){
                .controls.zoom img{
                    width: 40px;
                }
            }
            @media (max-width: 575px){
                .controls.zoom img{
                    width: 30px;
                }
            }
        </style>
    </head>
    <body>
        <div id="map"></div>
        <div class="controls zoom">
            <a href="javascript:void(0)" onclick="zoomIn();">
                <img src="resources/master/controls/button_zoom_in.svg">
            </a>
            <a href="javascript:void(0)" onclick="zoomOut();">
                <img src="resources/master/controls/button_zoom_out.svg">
            </a>
        </div>
        <script src="vendor/leaflet/leaflet-src.js" type="text/javascript"></script>
        <script>
                var leaflet = L.map('map', {
                    zoomControl: false,
                    attributionControl: false,
                    maxBounds: new L.LatLngBounds(
                            new L.LatLng(-0.012724399462052194, -0.023603439331054688),
                            new L.LatLng(0.012724399462052194, 0.023603439331054688)
                            ),
                    scrollWheelZoom: <?php echo $scroll_zoom ? 'true' : 'false' ?>,
                    dragging: <?php echo $dragging ? 'true' : 'false' ?>,
                }).fitBounds(new L.LatLngBounds(
                        new L.LatLng(-0.01768667264506111, -0.03280878067016602),
                        new L.LatLng(0.01768667264506111, 0.03280878067016602)
                        ));
                L.tileLayer('resources/map/tile.php?z={z}&x={x}&y={y}', {
                    minZoom: 15,
                    maxZoom: 17,
                }).addTo(leaflet);

                function zoomIn() {
                    leaflet.zoomIn();
                }

                function zoomOut() {
                    leaflet.zoomOut();
                }
        </script>
    </body>
</html>