<?php
include 'header.php'
    ?>

<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<style type="text/css" media="screen">
    @media only screen and (max-width: 700px) {
        .mobilgizle {
            display: none;
        }

        .mobilgizleexport {
            display: none;
        }

        .mobilgoster {
            display: block;
        }
    }

    @media only screen and (min-width: 700px) {
        .mobilgizleexport {
            display: flex;
        }

        .mobilgizle {
            display: block;
        }

        .mobilgoster {
            display: none;
        }
    }
</style>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Kullanıcılar</h1>
    <p class="mb-4">Buradaki alanda çalışanların bilgilerini görüntüleyebilirsiniz.</p>
    <!-- DataTales Giriş -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Çalışanlar</h6>
        </div>
        <div class="card-body" style="width: 100%">
            <!--Tablo filtreleme butonları mobilde gizlendiğinde gözükecek buton-->
            <button type="button" class="btn btn-sm btn-info btn-icon-split mobilgoster">
                <span class="icon text-white-65">
                    <i class="fas fa-edit"></i>
                </span>
                <span class="text">Seçenekler</span>
            </button>

            <div class="mobilgizle gizlemeyiac" style="margin-bottom: 10px;">

            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>İsim</th>
                            <th>E-mail</th>
                            <th>Ünvan</th>
                        </tr>
                    </thead>
                    <!--While döngüsü ile veritabanında ki verilerin tabloya çekilme işlemi giriş-->
                    <tbody>
                        <?php
                        $say = 0;
                        $kulcek = $db->prepare("SELECT * FROM kullanicilar ORDER BY kul_id");
                        $kulcek->execute();
                        while ($kullanicicek = $kulcek->fetch(PDO::FETCH_ASSOC)) {
                            $say++ ?>

                            <tr>
                                <td><?php echo $say; ?></td>
                                <td><?php echo $kullanicicek['kul_isim']; ?></td>
                                <td><?php echo $kullanicicek['kul_mail']; ?></td>
                                <td><?php echo $kullanicicek['kul_unvan']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <!--While döngüsü ile veritabanında ki verilerin tabloya çekilme işlemi çıkış-->
                </table>
            </div>
        </div>
    </div>
    <!--Datatables çıkış-->
</div>


<?php include 'footer.php' ?>

<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="js/demo/datatables-demo.js"></script>
<script src="vendor/datatables/dataTables.buttons.min.js"></script>
<script src="vendor/datatables/buttons.flash.min.js"></script>
<script src="vendor/datatables/jszip.min.js"></script>
<script src="vendor/datatables/pdfmake.min.js"></script>
<script src="vendor/datatables/vfs_fonts.js"></script>
<script src="vendor/datatables/buttons.html5.min.js"></script>
<script src="vendor/datatables/buttons.print.min.js"></script>

<script type="text/javascript">
    $("#aktarmagizleme").click(function () {
        $(".dt-buttons").toggle();
    });
</script>
<script type="text/javascript">
    $(".mobilgoster").click(function () {
        $(".gizlemeyiac").toggle();
    });
</script>
<script>
    var dataTables = $('#dataTable').DataTable({
        initComplete: function () {
            this.api().columns([2, 3, 4]).every(function () {
                var column = this;
                var select = $('<select class="filtre"><option value=""></option></select>')
                    .appendTo($(column.footer()).empty())
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search(val ? '^' + val + '$' : '', true, false)
                            .draw();
                    });

                column.data().unique().sort().each(function (d, j) {
                    var val = $('<div/>').html(d).text();

                    if (val.length > 29) {
                        filtremetin = val.substr(0, 30) + "...";
                    } else {
                        filtremetin = val;
                    }
                    select.append('<option value="' + val + '">' + filtremetin + '</option>')
                });
            });
        },
        "ordering": true,  //Tabloda sıralama özelliği gözüksün mü? true veya false
        "searching": true,  //Tabloda arama yapma alanı gözüksün mü? true veya false
        "lengthChange": true, //Tabloda öğre gösterilme gözüksün mü? true veya false
        "info": true,
        dom: "<'row mobilgizleexport gizlemeyiac'<'col-md-6'l><'col-md-6'f><'col-md-4 d-none d-print-block'B>>rtip",
        buttons: [
            {
                extend: 'copyHtml5',
                className: 'kopyalama-buton',
            },
            {
                extend: 'excelHtml5',
                className: 'excel-buton',
            },
            {
                extend: 'pdfHtml5',
                className: 'pdf-buton',
            },
            {
                extend: 'csvHtml5',
                className: 'csv-buton',
            }
        ]
    });
    //Sonradan yapılan butona tıklandığında asıl dışa aktarma butonunun çalışması
    function fnAction(action) {
        switch (action) {
            case "excel":
                $('.excel-buton').trigger('click');
                break;
            case "pdf":
                $('.pdf-buton').trigger('click');
                break;
            case "copy":
                $('.kopyalama-buton').trigger('click');
                break;
            case "csv":
                $('.csv-buton').trigger('click');
                break;
        }
    }
    //Tablo filtreleme işlemleri
    $('#hepsi').on('click', function () {
        dataTables
            .columns()
            .search('')
            .columns('.sold_out')
            .search('YES')
            .draw();
        dataTables.column(3).search("").draw();
    });
    $('#acil').on('click', function () {
        dataTables
            .columns()
            .search('')
            .columns('.sold_out')
            .search('YES')
            .draw();
        dataTables.column(3).search("Acil").draw();
    });
    $('#normal').on('click', function () {
        dataTables
            .columns()
            .search('')
            .columns('.sold_out')
            .search('YES')
            .draw();
        dataTables.column(3).search("Normal").draw();
    });
    $('#acelesiyok').on('click', function () {
        dataTables
            .columns()
            .search('')
            .columns('.sold_out')
            .search('YES')
            .draw();
        dataTables.column(3).search("Acelesi Yok").draw();
    });
    $('#bitti').on('click', function () {
        dataTables
            .columns()
            .search('')
            .columns('.sold_out')
            .search('YES')
            .draw();
        dataTables.column(4).search("Bitti").draw();
    });
    $('#devam').on('click', function () {
        dataTables
            .columns()
            .search('')
            .columns('.sold_out')
            .search('YES')
            .draw();
        dataTables.column(4).search("Devam Ediyor").draw();
    });
    $('#yeni').on('click', function () {
        dataTables
            .columns()
            .search('')
            .columns('.sold_out')
            .search('YES')
            .draw();
        dataTables.column(4).search("Yeni Başladı").draw();
    });
</script>