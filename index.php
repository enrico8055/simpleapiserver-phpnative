<?php
    $conn = new mysqli("remotemysql.com:3306", "Kc4FegeTMa", "RR7CK8JY96", "Kc4FegeTMa"); //connect ke database

    if(isset($_GET['action']) && $_GET['action'] == 2 && isset($_GET['tanggal']) && isset($_GET['jenis']) && isset($_GET['harga']) && isset($_GET['user'])){ //UNTUK MEMASUKKAN DATA KE DB PENGELUARAN
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
    }else if(isset($_GET['action']) && $_GET['action'] == 5 && isset($_GET['tanggal'])){ //AMBIL SISA UANG DARI SALDO - PENGELUARAN
        $tanggal = $_GET['tanggal'];

        $getData1 = $conn->query("SELECT * FROM tblPengeluaran WHERE tanggal ='".$tanggal."' order by id desc"); //ambil semua data dari db
        
        if($getData1 -> num_rows == 0){
            echo json_encode(
                array('result' => 'no data')
            );
        }else{
            $result1 = array(); //tempat menampung semua data

            while($row = $getData1 -> fetch_assoc()){ //kita ambil data per baris lalu masukkan ke tempat penampungan
                array_push($result1, array(
                    'id' => $row['id'],
                    'tanggal' => $row['tanggal'],
                    'jenis' => $row['jenis'],
                    'harga' => $row['harga'],
                    'user' => $row['user'],
                    'waktuProses' => $row['waktuProses']
                ));
            };
        }
        
        $getData = $conn->query("SELECT saldo, (SELECT SUM(harga) FROM tblPengeluaran WHERE tanggal ='".$tanggal."') as total FROM tblPemasukan WHERE tanggal ='".$tanggal."'");
        $result = $getData -> fetch_assoc();

        echo json_encode( 
            array('result' => array(
                'sisa' => $result['saldo'] - $result['total'],
                'total' => $result['total'],
                'saldo' => $result['saldo'],
                'rincikeuangan' => $result1
            ))
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
    }else if(isset($_GET['action']) && $_GET['action'] == 10 && isset($_GET['id'])){ //AMBIL KURAN GBRP PERSEN TABUNGAN KE TARGET
        $id = $_GET['id'];

        $getData = $conn->query("SELECT * FROM tblRinciTabungan WHERE id=".$_GET['id']);
        
        $target = $conn->query("SELECT target FROM tblTabungan WHERE id =".$id);
        $result1 = $target -> fetch_assoc();

        $jumlah = $conn->query("SELECT SUM(jumlah) as total FROM tblRinciTabungan WHERE id =".$id);
        $result2 = $jumlah -> fetch_assoc();

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
        }

        echo json_encode( 
            array('result' => array(
                'persen' => 1-($result1['target'] - $result2['total'])/$result1['target'],
                'total' => $result2['total'],
                'rincitabungan' => $result
            ))
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
    }else if(isset($_GET['action']) && $_GET['action'] == 13 && isset($_GET['id'])){ //UNTUK AMBIL DATA REKAP DARI DB
        $getData = $conn->query("SELECT * FROM tblKulakan where id = ".$_GET['id']." order by no desc"); //ambil semua data dari db
        
        if($getData -> num_rows == 0){
            echo json_encode(
                array('result' => 'no data')
            );
        }else{
            $result = array(); //tempat menampung semua data

            while($row = $getData -> fetch_assoc()){ //kita ambil data per baris lalu masukkan ke tempat penampungan
                array_push($result, array(
                    'id' => $row['id'],
                    'namasupplier' => $row['namasupplier'],
                    'jumlahbarang' => $row['jumlahbarang'],
                    'hargabarang' => $row['hargabarang'],
                    'tanggal' => $row['tanggal']
                ));
            };

            echo json_encode( //return/ kembalikan data di penampungan berupa json result
                array('result' => $result)
            );
        }
    }else if(isset($_GET['action']) && $_GET['action'] == 14 && isset($_GET['id']) ){ //UNTUK AMBIL DATA REKAP DARI DB
        $getData = $conn->query("SELECT * FROM tblPenjualan where id=".$_GET['id']." order by no desc"); //ambil semua data dari db
        if($getData -> num_rows == 0){
            echo json_encode(
                array('result' => 'no data')
            );
        }else{
            $result = array(); //tempat menampung semua data
            
            while($row = $getData -> fetch_assoc()){ //kita ambil data per baris lalu masukkan ke tempat penampungan
                array_push($result, array(
                    'namapembeli' => $row['namapembeli'],
                    'jumlahjual' => $row['jumlahjual'],
                    'hargajual' => $row['hargajual'],
                    'ongkos' => $row['ongkos'],
                    'tanggal' => $row['tanggal']
                ));
            };

            echo json_encode( //return/ kembalikan data di penampungan berupa json result
                array('result' => $result)
            );
        }
    }else if(isset($_GET['action']) && $_GET['action'] == 15 && isset($_GET['jmlBarang']) && isset($_GET['hargaBarang']) && isset($_GET['id']) && isset($_GET['namasupplier']) && isset($_GET['tanggal'])){ //UNTUK MEMASUKKAN DATA REKAP KE DB
        $jmlBarang = $_GET['jmlBarang'];
        $hargaBarang = $_GET['hargaBarang'];
        $namasupplier = $_GET['namasupplier'];
        $tanggal = $_GET['tanggal'];

        $conn->query("INSERT INTO tblKulakan(id, namasupplier, tanggal, jumlahbarang, hargabarang) VALUES (".$_GET['id'].",'".$namasupplier."','".$tanggal."', ".$jmlBarang.",".$hargaBarang.")");

        if($conn -> error != null){ //kalo query nya ada error
            echo json_encode(
                array('result' => 'query failed')
            );
        }else{ //kalo query berhasil
            echo json_encode(
                array('result' => 'success')
            );
        }
    }else if(isset($_GET['action']) && $_GET['action'] == 16 && isset($_GET['namapembeli']) && isset($_GET['hargajual']) && isset($_GET['jumlahbarang']) && isset($_GET['ongkir']) && isset($_GET['id']) && isset($_GET['tanggal'])){ //UNTUK MEMASUKKAN DATA REKAP KE DB
        $namapembeli = $_GET['namapembeli'];
        $hargajual = $_GET['hargajual'];
        $jumlahbarang = $_GET['jumlahbarang'];
        $ongkir = $_GET['ongkir'];
        $id = $_GET['id'];
        $tanggal = $_GET['tanggal'];

        $conn->query("INSERT INTO tblPenjualan(id, namapembeli, tanggal, jumlahjual, hargajual, ongkos) VALUES (".$id.",'".$namapembeli."','".$tanggal."',".$jumlahbarang.",".$hargajual.",".$ongkir.")");

        if($conn -> error != null){ //kalo query nya ada error
            echo json_encode(
                array('result' => 'query failed')
            );
        }else{ //kalo query berhasil
            echo json_encode(
                array('result' => 'success')
            );
        }
    }else if(isset($_GET['action']) && $_GET['action'] == 17 && isset($_GET['id']) ){ //UNTUK AMBIL DATA REKAP DARI DB
        $getData = $conn->query("SELECT (SELECT sum(jumlahjual*hargajual) - sum(ongkos) FROM tblPenjualan WHERE id = 1121) - (sum(jumlahbarang*hargabarang)) as laba, sum(jumlahbarang) - (SELECT sum(jumlahjual) FROM tblPenjualan WHERE id = 1121) as sisastok FROM tblKulakan WHERE id = ".$_GET['id']); //ambil semua data dari db
        if($getData -> num_rows == 0){
            echo json_encode(
                array('result' => 'no data')
            );
        }else{
            $result = array(); //tempat menampung semua data
            
            while($row = $getData -> fetch_assoc()){ //kita ambil data per baris lalu masukkan ke tempat penampungan
                array_push($result, array(
                    'laba' => $row['laba'],
                    'sisastok' => $row['sisastok']
                ));
            };

            echo json_encode( //return/ kembalikan data di penampungan berupa json result
                array('result' => $result)
            );
        }
    }else if(isset($_GET['action']) && $_GET['action'] == 18 ){ //UNTUK AMBIL DATA REKAP DARI DB
        $getData = $conn->query("SELECT * FROM tblDataKerja"); //ambil semua data dari db
        if($getData -> num_rows == 0){
            echo json_encode(
                array('result' => 'no data')
            );
        }else{
            $result = array(); //tempat menampung semua data
            
            while($row = $getData -> fetch_assoc()){ //kita ambil data per baris lalu masukkan ke tempat penampungan
                array_push($result, array(
                    'id' => $row['id'],
                    'jenis' => $row['jenis'],
                    'keterangan' => $row['keterangan']
                ));
            };

            echo json_encode( //return/ kembalikan data di penampungan berupa json result
                array('result' => $result)
            );
        }
    }else{ //KALO USER KIRIM PARAMETER GAK JELAS
        echo json_encode(
            array('result' => 'access not permitted')
        );
    }
?>