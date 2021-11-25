<?php
    $conn = new mysqli("remotemysql.com:3306", "Kc4FegeTMa", "RR7CK8JY96", "Kc4FegeTMa"); //connect ke database

    if(isset($_GET['action']) && $_GET['action'] == 1 && isset($_GET['tanggal'])){ //UNTUK AMBIL DATA DARI DB
        $tanggal = $_GET['tanggal'];

        $getData = $conn->query("SELECT * FROM tblPengeluaran WHERE tanggal ='".$tanggal."' order by id desc"); //ambil semua data dari db
        
        if($getData -> num_rows == 0){
            echo json_encode(
                array('result' => 'no data')
            );
        }else{
            $result = array(); //tempat menampung semua data

            while($row = $getData -> fetch_assoc()){ //kita ambil data per baris lalu masukkan ke tempat penampungan
                array_push($result, array(
                    'id' => $row['id'],
                    'tanggal' => $row['tanggal'],
                    'jenis' => $row['jenis'],
                    'harga' => $row['harga'],
                    'user' => $row['user'],
                    'waktuProses' => $row['waktuProses']
                ));
            };

            echo json_encode( //return/ kembalikan data di penampungan berupa json result
                array('result' => $result)
            );
        }
    }else if(isset($_GET['action']) && $_GET['action'] == 2 && isset($_GET['tanggal']) && isset($_GET['jenis']) && isset($_GET['harga']) && isset($_GET['user'])){ //UNTUK MEMASUKKAN DATA KE DB PENGELUARAN
        $tanggal = $_GET['tanggal'];
        $jenis = $_GET['jenis'];
        $harga = $_GET['harga'];
        $user = $_GET['user'];
        
        date_default_timezone_set('Asia/Jakarta');
        $dateSekarang = date("j\-m\-Y h:i:s A");

        $conn->query("INSERT INTO tblPengeluaran(tanggal, jenis, harga, user, waktuProses) VALUES (".$tanggal.", '".$jenis."', ".$harga." ,'".$user."','".$dateSekarang."')");

        if($conn -> error != null){ //kalo query nya ada error
            echo json_encode(
                array('result' => 'query failed')
            );
        }else{ //kalo query berhasil
            echo json_encode(
                array('result' => 'success')
            );
        }
    }else if(isset($_GET['action']) && $_GET['action'] == 3){
        $getData = $conn->query("SELECT * FROM tblTanggal"); //ambil semua data dari db
        
        if($getData -> num_rows == 0){
            echo json_encode(
                array('result' => 'no data')
            );
        }else{
            $result = array(); //tempat menampung semua data

            while($row = $getData -> fetch_assoc()){ //kita ambil data per baris lalu masukkan ke tempat penampungan
                array_push($result, array(
                    'id' => $row['id'],
                    'bulan' => $row['bulan'],
                    'tahun' => $row['tahun'],
                    'url' => $row['url']
                ));
            };

            echo json_encode( //return/ kembalikan data di penampungan berupa json result
                array('result' => $result)
            );
        }
    }else if(isset($_GET['action']) && $_GET['action'] == 4 && isset($_GET['tanggal'])){
        $tanggal = $_GET['tanggal'];
        
        $getData = $conn->query("SELECT SUM(harga) as total FROM tblPengeluaran WHERE tanggal ='".$tanggal."'");

        $result = $getData -> fetch_assoc();

        echo json_encode( 
            array('result' => $result)
        );
    }else if(isset($_GET['action']) && $_GET['action'] == 5 && isset($_GET['tanggal'])){ //AMBIL SISA UANG DARI SALDO - PENGELUARAN
        $tanggal = $_GET['tanggal'];
        
        $getData1 = $conn->query("SELECT saldo FROM tblPemasukan WHERE tanggal ='".$tanggal."'");
        $result1 = $getData1 -> fetch_assoc();

        $getData2 = $conn->query("SELECT SUM(harga) as total FROM tblPengeluaran WHERE tanggal ='".$tanggal."'");
        $result2 = $getData2 -> fetch_assoc();

        echo json_encode( 
            array('result' => $result1['saldo'] - $result2['total'])
        );
    }else if(isset($_GET['action']) && $_GET['action'] == 6 && isset($_GET['tanggal']) && isset($_GET['query'])){ //UNTUK REQ DATA BERDASAR QUERY TANGGAL
        $tanggal = $_GET['tanggal'];
        $query = $_GET['query'];

    
        $getData = $conn->query("SELECT * FROM tblPengeluaran WHERE tanggal ='".$tanggal."' AND waktuProses LIKE '".$query."%' order by id desc"); //ambil semua data dari db
        
        if($getData -> num_rows == 0){
           $result = array();
            array_push($result, array(
                'response' => '404'
            ));
            echo json_encode(
                array('result' => $result)
            );
        }else{
            $result = array(); //tempat menampung semua data

            while($row = $getData -> fetch_assoc()){ //kita ambil data per baris lalu masukkan ke tempat penampungan
                array_push($result, array(
                    'id' => $row['id'],
                    'tanggal' => $row['tanggal'],
                    'jenis' => $row['jenis'],
                    'harga' => $row['harga'],
                    'user' => $row['user'],
                    'waktuProses' => $row['waktuProses'],
                    'response' => '200ok'
                ));
            };

            echo json_encode( //return/ kembalikan data di penampungan berupa json result
                array('result' => $result)
            );
        }
    }else if(isset($_GET['action']) && $_GET['action'] == 7 && isset($_GET['tanggal'])){ //AMBIL SALDO BERDASAR TANGGAL
        $tanggal = $_GET['tanggal'];
        
        $getData = $conn->query("SELECT saldo FROM tblPemasukan WHERE tanggal ='".$tanggal."'");
        $result = $getData -> fetch_assoc();

        echo json_encode( 
            array('result' => $result['saldo'])
        );
    }else if(isset($_GET['action']) && $_GET['action'] == 8 && !isset($_GET['id'])){
        $getData = $conn->query("SELECT * FROM tblTabungan"); //ambil semua data dari db
        
        if($getData -> num_rows == 0){
            echo json_encode(
                array('result' => 'no data')
            );
        }else{
            $result = array(); //tempat menampung semua data

            while($row = $getData -> fetch_assoc()){ //kita ambil data per baris lalu masukkan ke tempat penampungan
                array_push($result, array(
                    'id' => $row['id'],
                    'nama' => $row['nama'],
                    'target' => $row['target']
                ));
            };

            echo json_encode( //return/ kembalikan data di penampungan berupa json result
                array('result' => $result)
            );
        }
    }else if(isset($_GET['action']) && $_GET['action'] == 8 && isset($_GET['id'])){
        $getData = $conn->query("SELECT * FROM tblTabungan WHERE id=".$_GET['id']); //ambil semua data dari db
        
        if($getData -> num_rows == 0){
            echo json_encode(
                array('result' => 'no data')
            );
        }else{
            $result = array(); //tempat menampung semua data

            while($row = $getData -> fetch_assoc()){ //kita ambil data per baris lalu masukkan ke tempat penampungan
                array_push($result, array(
                    'id' => $row['id'],
                    'nama' => $row['nama'],
                    'target' => $row['target']
                ));
            };

            echo json_encode( //return/ kembalikan data di penampungan berupa json result
                array('result' => $result)
            );
        }
    }else if(isset($_GET['action']) && $_GET['action'] == 9 && isset($_GET['id'])){ //AMBIL TOTAL TABUNGAN BERDASAR JENIS TABUNGAN
        $getData = $conn->query("SELECT * FROM tblRinciTabungan WHERE id=".$_GET['id']); //ambil semua data dari db
        
        if($getData -> num_rows == 0){
            echo json_encode(
                array('result' => 'no data')
            );
        }else{
            $result = array(); //tempat menampung semua data

            while($row = $getData -> fetch_assoc()){ //kita ambil data per baris lalu masukkan ke tempat penampungan
                array_push($result, array(
                    'id' => $row['id'],
                    'jumlah' => $row['jumlah'],
                    'tanggal' => $row['tanggal']
                ));
            };

            echo json_encode( //return/ kembalikan data di penampungan berupa json result
                array('result' => $result)
            );
        }
    }else if(isset($_GET['action']) && $_GET['action'] == 10 && isset($_GET['id'])){ //AMBIL KURAN GBRP PERSEN TABUNGAN KE TARGET
        $id = $_GET['id'];
        
        $target = $conn->query("SELECT target FROM tblTabungan WHERE id =".$id);
        $result1 = $target -> fetch_assoc();

        $jumlah = $conn->query("SELECT SUM(jumlah) as total FROM tblRinciTabungan WHERE id =".$id);
        $result2 = $jumlah -> fetch_assoc();

        echo json_encode( 
            array('result' => (1-($result1['target'] - $result2['total'])/$result1['target']))
        );
    }else if(isset($_GET['action']) && $_GET['action'] == 11 && isset($_GET['id'])){ //AMBIL TOTAL TABUNGAN
        $id = $_GET['id'];

        $jumlah = $conn->query("SELECT SUM(jumlah) as total FROM tblRinciTabungan WHERE id =".$id);
        $result2 = $jumlah -> fetch_assoc();

        echo json_encode( 
            array('result' => $result2['total'])
        );
    }else if(isset($_GET['action']) && $_GET['action'] == 12 && isset($_GET['id']) && isset($_GET['jumlah']) && isset($_GET['tanggal'])){ //UNTUK MEMASUKKAN DATA rinci tabungan
        $id = $_GET['id'];
        $jumlah = $_GET['jumlah'];
        $tanggal = $_GET['tanggal'];

        $conn->query("INSERT INTO tblRinciTabungan(id, jumlah, tanggal) VALUES (".$id.",".$jumlah.",'".$tanggal."')");

        if($conn -> error != null){ //kalo query nya ada error
            echo json_encode(
                array('result' => 'query failed')
            );
        }else{ //kalo query berhasil
            echo json_encode(
                array('result' => 'success')
            );
        }
    }else if(isset($_GET['action']) && $_GET['action'] == 13 ){ //UNTUK AMBIL DATA REKAP DARI DB
        $getData = $conn->query("SELECT * FROM tblRekap order by id desc limit 30"); //ambil semua data dari db
        
        if($getData -> num_rows == 0){
            echo json_encode(
                array('result' => 'no data')
            );
        }else{
            $result = array(); //tempat menampung semua data

            while($row = $getData -> fetch_assoc()){ //kita ambil data per baris lalu masukkan ke tempat penampungan
                array_push($result, array(
                    'id' => $row['id'],
                    'tanggal' => $row['tanggal'],
                    'jumlahmasuk' => $row['jumlahmasuk'],
                    'hargamasuk' => $row['hargamasuk']
                ));
            };

            echo json_encode( //return/ kembalikan data di penampungan berupa json result
                array('result' => $result)
            );
        }
    }else if(isset($_GET['action']) && $_GET['action'] == 14 && isset($_GET['id']) ){ //UNTUK AMBIL DATA REKAP DARI DB
        $getTotal = $conn->query("SELECT sum((jumlahjual*hargajual)-ongkir) as total FROM tblRinciRekap WHERE id=".$_GET['id']." group by id");
        $getData = $conn->query("SELECT * FROM tblRinciRekap where id=".$_GET['id']); //ambil semua data dari db
        if($getTotal -> num_rows == 0 || $getData -> num_rows == 0){
            echo json_encode(
                array('result' => 'no data')
            );
        }else{
            $result = array(); //tempat menampung semua data
            
            $row2 = $getTotal -> fetch_assoc();
            while($row = $getData -> fetch_assoc()){ //kita ambil data per baris lalu masukkan ke tempat penampungan
                array_push($result, array(
                    'namapembeli' => $row['namapembeli'],
                    'jumlahjual' => $row['jumlahjual'],
                    'hargajual' => $row['hargajual'],
                    'ongkir' => $row['ongkir'],
                    'total' => $row2['total']
                ));
            };

            echo json_encode( //return/ kembalikan data di penampungan berupa json result
                array('result' => $result)
            );
        }
    }else if(isset($_GET['action']) && $_GET['action'] == 15 && isset($_GET['jmlBarang']) && isset($_GET['hargaBarang'])){ //UNTUK MEMASUKKAN DATA REKAP KE DB
        $jmlBarang = $_GET['jmlBarang'];
        $hargaBarang = $_GET['hargaBarang'];
        
        date_default_timezone_set('Asia/Jakarta');
        $dateSekarang = date("d/m/Y");

        $conn->query("INSERT INTO tblRekap(tanggal, jumlahmasuk, hargamasuk) VALUES ('".$dateSekarang."', ".$jmlBarang.",".$hargaBarang.")");

        if($conn -> error != null){ //kalo query nya ada error
            echo json_encode(
                array('result' => 'query failed')
            );
        }else{ //kalo query berhasil
            echo json_encode(
                array('result' => 'success')
            );
        }
    }else if(isset($_GET['action']) && $_GET['action'] == 16 && isset($_GET['namapembeli']) && isset($_GET['hargajual']) && isset($_GET['jumlahbarang']) && isset($_GET['ongkir']) && isset($_GET['id'])){ //UNTUK MEMASUKKAN DATA REKAP KE DB
        $namapembeli = $_GET['namapembeli'];
        $hargajual = $_GET['hargajual'];
        $jumlahbarang = $_GET['jumlahbarang'];
        $ongkir = $_GET['ongkir'];
        $id = $_GET['id'];

        $conn->query("INSERT INTO tblRinciRekap(id, namapembeli, jumlahjual, hargajual, ongkir) VALUES (".$id.",'".$namapembeli."', ".$jumlahbarang.",".$hargajual.",".$ongkir.")");

        if($conn -> error != null){ //kalo query nya ada error
            echo json_encode(
                array('result' => 'query failed')
            );
        }else{ //kalo query berhasil
            echo json_encode(
                array('result' => 'success')
            );
        }
    }else{ //KALO USER KIRIM PARAMETER GAK JELAS
        echo json_encode(
            array('result' => 'access not permitted')
        );
    }
?>