<?php
include('crud_tvRev.php');
$crud = new Crud();
$arrayName = $crud->readGejala();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <title>Television Risk Diagnosis</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
        }

        .hero-section {
            background: linear-gradient(135deg, #007bff, rgb(81, 0, 179));
            color: white;
            padding: 100px 20px;
            text-align: center;
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 700;
        }

        .hero-section p {
            font-size: 1.5rem;
            margin-top: 15px;
        }

        .hero-section .btn {
            margin-top: 30px;
            padding: 15px 40px;
            font-size: 1.2rem;
            background: linear-gradient(135deg, #ff7f50, #ff4500);
            color: white;
            border: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .hero-section .btn:hover {
            background: linear-gradient(135deg, #ff4500, #ff7f50);
            transform: scale(1.1);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .about-section img {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .consultation-form {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="hero-section">
        <h1>Selamat Datang di SigmaTV</h1>
        <p>Sistem Pakar Diagnosa Kerusakan Televisi Berbasis Web</p>
        <a href="konsul.php" class="btn btn-light btn-lg">Mulai Konsultasi</a>
    </div>

    <div class="container my-5">
        <div class="row align-items-center">
            <div class="col-md-6">
                <img src="images/Electronics-Technician-Career-Path.jpg" class="img-fluid" alt="Technician">
            </div>
            <div class="col-md-6">
                <h2 class="mb-4">Tentang SigmaTV</h2>
                <p>SigmaTV adalah platform berbasis web yang dirancang untuk membantu Anda mendiagnosa kerusakan pada televisi Anda. Dengan teknologi sistem pakar berbasis metode certainty factor, kami memberikan solusi dengan secara cepat dan ringkas.</p>
                <p>Mulailah konsultasi sekarang untuk mendapatkan bantuan terbaik dari kami!</p>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <h2 class="text-center mb-4">Daftar Gejala</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center">Nomor Gejala</th>
                        <th class="text-center">Nama Gejala</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($arrayName as $r) { ?>
                        <tr>
                            <td class="text-center"><?php echo $r["ID_GEJALA"]; ?></td>
                            <td class="text-center"><?php echo $r["NAMA_GEJALA"]; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="container my-5">
        <h2 class="text-center mb-4">Galeri Komponen Televisi</h2>
        <div class="row">
                <div class="col-md-4 mb-4"><!--gambar komponen tv full-->
                    <div class="card">
                        <a href="images/1747451810955.jpg" target="_blank" class="zoom-img">
                            <img src="images/1747451810955.jpg" class="img-thumbnail" alt="" style="cursor: zoom-in;">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title text-center">Full Komponen TV </h5>
                        </div>
                    </div>
                    <!-- Modal for zoom -->
                    <div class="modal fade" id="zoomModal" tabindex="-1" role="dialog" aria-labelledby="zoomModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content bg-transparent border-0">
                          <div class="modal-body text-center p-0">
                            <img src="" id="zoomedImg" class="img-fluid rounded" alt="" style="max-width:100%; max-height:80vh; cursor: grab;">
                          </div>
                        </div>
                      </div>
                    </div>
                    <script>
                    $(document).ready(function(){
                        $('.zoom-img').on('click', function(e){
                            e.preventDefault();
                            var src = $(this).find('img').attr('src');
                            $('#zoomedImg').attr('src', src);
                            $('#zoomModal').modal('show');
                        });

                        // Zoom and pan functionality
                        let scale = 1;
                        let originX = 0, originY = 0;
                        let isDragging = false, startX, startY, imgX = 0, imgY = 0;

                        $('#zoomedImg').on('wheel', function(e){
                            e.preventDefault();
                            let delta = e.originalEvent.deltaY > 0 ? -0.1 : 0.1;
                            scale += delta;
                            if(scale < 1) scale = 1;
                            if(scale > 5) scale = 5;
                            $(this).css('transform', `scale(${scale}) translate(${imgX}px, ${imgY}px)`);
                        });

                        $('#zoomedImg').on('mousedown touchstart', function(e){
                            isDragging = true;
                            startX = e.pageX || e.originalEvent.touches[0].pageX;
                            startY = e.pageY || e.originalEvent.touches[0].pageY;
                            $(this).css('cursor', 'grabbing');
                        });

                        $(document).on('mousemove touchmove', function(e){
                            if(isDragging){
                                let moveX = (e.pageX || e.originalEvent.touches[0].pageX) - startX;
                                let moveY = (e.pageY || e.originalEvent.touches[0].pageY) - startY;
                                $('#zoomedImg').css('transform', `scale(${scale}) translate(${imgX + moveX/scale}px, ${imgY + moveY/scale}px)`);
                            }
                        });

                        $(document).on('mouseup touchend', function(e){
                            if(isDragging){
                                let endX = e.pageX || (e.originalEvent.changedTouches ? e.originalEvent.changedTouches[0].pageX : 0);
                                let endY = e.pageY || (e.originalEvent.changedTouches ? e.originalEvent.changedTouches[0].pageY : 0);
                                imgX += (endX - startX)/scale;
                                imgY += (endY - startY)/scale;
                                isDragging = false;
                                $('#zoomedImg').css('cursor', 'grab');
                            }
                        });

                        // Reset zoom and pan when modal closed
                        $('#zoomModal').on('hidden.bs.modal', function () {
                            scale = 1;
                            imgX = 0;
                            imgY = 0;
                            $('#zoomedImg').css('transform', 'scale(1) translate(0,0)');
                        });
                    });
                    </script>
                    <div class="card">
                        <img src="images/BacklightTV.jpg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title text-center">Backlight LCD</h5>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <img src="images/Speaker.jpg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title text-center">Speaker Televisi</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="images\Thcom.jpg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title text-center">Thcom</h5>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <img src="images\LCDtv.jpg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title text-center">LCD</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="images\ICsuara.jpg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title text-center">IC Suara</h5>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <img src="images\ElcoSuaraTV.jpg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title text-center">Elco Suara</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="images\CPU TV.jpg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title text-center">CPU</h5>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <img src="images\FleksibelLCD.jpg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title text-center">Fleksibel LCD</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="images\PowerSupplyTV.jpg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title text-center">Power Supply Tv</h5>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <img src="images\AntenaTV.jpeg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title text-center">Antena Televisi</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="images\TunnerTV.jpg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title text-center">Tunner Tv</h5>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <img src="images\ICEprom.jpg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title text-center">IC Eprom</h5>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</body>

</html>
