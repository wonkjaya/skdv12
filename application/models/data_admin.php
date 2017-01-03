<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Data_admin extends CI_Model {

    function check_login($username, $password) {
        $this->db->where("username", $username);
        $this->db->where("password", $password);
        $query = $this->db->get("login");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data_ambil['login'] = 'ada';
                $data_ambil['username'] = $row->username;
                $data_ambil['type'] = $row->type;
                $this->session->set_userdata($data_ambil);
            }
            return true;
            //header('location:'.base_url().'');
        } else {
            return false;
        }
    }

    function check_name($username, $type) {
        if ($type == "1") {
            $this->db->where("kd_admin", $username);
            $query = $this->db->get("data_admin");
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $data_ambil['nama'] = $row->nama;
                    $this->session->set_userdata($data_ambil);
                }
                return true;
                //header('location:'.base_url().'');
            } else {
                return false;
            }
        } else if ($type == "2") {
            $this->db->where("nip", $username);
            $query = $this->db->get("dosen");
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $data_ambil['nama'] = $row->nama;
                    $this->session->set_userdata($data_ambil);
                }
                return true;
            } else {
                return false;
            }
        } else if ($type == "3") {
            $this->db->where("nim", $username);
            $query = $this->db->get("data_mahasiswa");
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $data_ambil['nama'] = $row->nama;
                    $this->session->set_userdata($data_ambil);
                }
                return true;
            } else {
                return false;
            }
        }
    }

    /* ---------------------Query Admin---------------------
     * 
     */

    function tabel_status($limit, $offset) {
        $this->db->join('semester', 'semester.kd_semester = status_kuliah.kd_semester');
        $this->db->limit($limit, $offset);
        $this->db->order_by("tahun_ajar1", "desc");
        $query = $this->db->get('status_kuliah');
        return $query->result();
    }

    function ambil_semester() {
        $query = $this->db->get('semester');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function check_status($tahun_ajar1, $tahun_ajar2, $kd_semester) {
        $this->db->where("tahun_ajar1", $tahun_ajar1);
        $this->db->where("tahun_ajar2", $tahun_ajar2);
        $this->db->where("kd_semester", $kd_semester);
        $query = $this->db->get("status_kuliah");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function check_status_aktif() {
        $query = $this->db->query("SELECT * FROM `status_kuliah` WHERE `status`='1'");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function ambil_tahun_ajar() {
        $query = $this->db->query("SELECT `status_kuliah`.`tahun_ajar1`,`status_kuliah`.`tahun_ajar2` FROM `status_kuliah` WHERE `status_kuliah`.`status`='1'");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $ambil['tahun_ajar1'] = $row->tahun_ajar1;
                $ambil['tahun_ajar2'] = $row->tahun_ajar2;
                $this->session->set_userdata($ambil);
            }
            return $ambil;
        } else {
            return FALSE;
        }
    }

    function ambil_type($type) {
        $this->db->where('kd_type', $type);
        $query = $this->db->get('type_login');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function add_data_status($tahun_ajar1, $tahun_ajar2, $kd_semester, $status) {
        $this->db->set('tahun_ajar1', $tahun_ajar1);
        $this->db->set('tahun_ajar2', $tahun_ajar2);
        $this->db->set('kd_semester', $kd_semester);
        $this->db->set('status', $status);
        $query = $this->db->insert('status_kuliah');
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    function delete_data_status($id) {
        $query = $this->db->query("DELETE FROM  `status_kuliah` WHERE  `status_kuliah`.`kd` ='" . $id . "'");
        return true;
    }

    function delete_data_matkul($id) {
        $query = $this->db->query("DELETE FROM  `matkul` WHERE  `matkul`.`kd_matkul` ='" . $id . "'");
        return true;
    }

    function update_data_status($id, $tahun_ajar1, $tahun_ajar2, $kd_semester, $status) {
        $data = array(
            'tahun_ajar1' => $tahun_ajar1,
            'tahun_ajar2' => $tahun_ajar2,
            'kd_semester' => $kd_semester,
            'status' => $status
        );
        $this->db->where('kd', $id);
        $this->db->update('status_kuliah', $data);
        return true;
    }

    function update_data_matkul($kd_matkul, $nm_matkul, $jml_sks, $kd_semester) {
        $data = array(
            'nm_matkul' => $nm_matkul,
            'jml_sks' => $jml_sks,
            'kd_semester' => $kd_semester,
        );
        $this->db->where('kd_matkul', $kd_matkul);
        $this->db->update('data_matkul', $data);
        return true;
    }

    function get_data_status_id($id) {
        return $this->db->get_where('status_kuliah', array('kd' => $id))->row();
    }

    function get_data_biodata_id($id) {
        return $this->db->get_where('data_dosen', array('nip' => $id))->row();
    }

    function get_data_matkul($id) {
        return $this->db->get_where('data_matkul', array('kd_matkul' => $id))->row();
    }

    function check_login_dosen($nip) {
        $query = $this->db->query("SELECT * FROM `login` WHERE `username`='" . $nip . "'");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function hapus_login($id) {
        $query = $this->db->query("DELETE FROM  `login` WHERE  `username` ='" . $id . "'");
        return true;
    }

    function delete_biodata_dosen($id) {
        $query = $this->db->query("DELETE FROM  `dosen` WHERE  `nip` ='" . $id . "'");
        return true;
    }

    function jmlh_tabel_status() {
        $query = $this->db->get('status_kuliah');
        return $query->num_rows();
    }
    
    function jmlh_tabel_kuisoner_total_nilai($status) {
        $query = $this->db->query("SELECT * FROM data_kuisoner_total_nilai o, data_matkul s, data_kelas a WHERE 
o.`status`='".$status."' AND s.`kd_matkul` = o.`data_matkul`
GROUP BY o.`data_matkul`");
        return $query->num_rows();
    }

     function jmlh_tabel_data_pertemuan_matkul($status) {
        $query = $this->db->query("SELECT  DISTINCT s.`kd_matkul`, a.`nm_matkul` FROM data_pertemuan s,  matkul a WHERE s.`kd_status` = '".$status."' AND s.`kd_matkul`  = a.`kd_matkul`;");
        return $query->num_rows();
    }

    function cek_status() {
        $this->db->select('status');
        $query = $this->db->get('status_kuliah');
        $result = $query->result();
        return $result;
    }

    function jmlh_tabel_biodata_dosen() {
        $query = $this->db->get('status_kuliah');
        return $query->num_rows();
    }

    function jmlh_tabel_matkul() {
        $query = $this->db->get('data_matkul');
        return $query->num_rows();
    }

    function tabel_biodata_dosen($limit, $offset) {
        $this->db->limit($limit, $offset);
        $this->db->order_by("nip", "desc");
        $query = $this->db->get('data_dosen');
        return $query->result();
    }

    function tabel_matkul($limit, $offset) {
        $this->db->join('semester', 'semester.kd_semester = data_matkul.kd_semester');
        $this->db->limit($limit, $offset);
        $this->db->order_by("kd_matkul", "asc");
        $query = $this->db->get('data_matkul');
        return $query->result();
    }
    
    function tabel_kuisoner($limit, $offset,$status) {
        $query = $this->db->query("SELECT * 
            FROM data_kuisoner_total_nilai o, data_matkul s, data_kelas a  WHERE 
o.`status`='".$status."' AND s.`kd_matkul` = o.`data_matkul`
GROUP BY o.`data_matkul`  LIMIT ".$offset.",".$limit."");        
        return $query->result();
    }
    
     function tabel_kuisoner_k($limit, $offset,$status) {
        $query = $this->db->query("SELECT  DISTINCT s.`kd_matkul`, a.`nm_matkul` FROM data_pertemuan s,  data_matkul a WHERE s.`kd_status` = '".$status."' AND s.`kd_matkul`  = a.`kd_matkul`  LIMIT ".$offset.",".$limit."");        
        return $query->result();
    }
    
     function tabel_lihat_nilai_ipd($matkul,$status) {
        $query = $this->db->query("SELECT  h.`nim`, GROUP_CONCAT(  i.`nilai` ORDER BY i.`id` ASC
SEPARATOR  ';' ) AS nilai_mhs, SUM(i.`nilai`) AS nilai ,COUNT(*) AS total  FROM kuisoner_total_nilai h, detail_data_kuisoner_total_nilai i 
WHERE h.`id` = i.`id_kuisoner_total_nilai` AND h.`matkul`='".$matkul."' AND h.`status`='".$status."'
GROUP BY h.`nim` order by h.`nim`");        
        return $query->result();
    }

    function add_data_biodata_dosen($nip, $nama, $alamat, $tempat, $tanggal, $jabatan, $telp, $jk) {
        $this->db->set('nip', $nip);
        $this->db->set('nama', $nama);
        $this->db->set('alamat', $alamat);
        $this->db->set('tempat_lahir', $tempat);
        $this->db->set('tanggal_lahir', $tanggal);
        $this->db->set('jabatan', $jabatan);
        $this->db->set('no_telp', $telp);
        $this->db->set('jenis_kelamin', $jk);
        $query = $this->db->insert('data_dosen');
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    function add_data_matkul($kd_matkul, $nm_matkul, $jml_sks, $semester) {
        $this->db->set('kd_matkul', $kd_matkul);
        $this->db->set('nm_matkul', $nm_matkul);
        $this->db->set('jml_sks', $jml_sks);
        $this->db->set('kd_semester', $semester);
        $query = $this->db->insert('data_matkul');
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    function update_data_biodata_dosen($nip, $nama, $alamat, $tempat, $tanggal, $jabatan, $telp, $jk) {
        $data = array(
            'tanggal_lahir' => $tanggal,
            'nama' => $nama,
            'alamat' => $alamat,
            'jabatan' => $jabatan,
            'tempat_lahir' => $tempat,
            'jenis_kelamin' => $jk,
            'no_telp' => $telp
        );
        $this->db->where('nip', $nip);
        $this->db->update('data_dosen', $data);
        return true;
    }

    function add_data_login($nip, $password) {
        $this->db->set('username', $nip);
        $this->db->set('password', $password);
        $this->db->set('type', 2);
        $query = $this->db->insert('login');
        if ($query) {
            return true;
        } else {
            return false;
        }
    }
    
    function grafik_kuisoner($matkul, $status){
        $query = $this->db->query("SELECT k.`pilihan`,COUNT(k.`nilai`)  AS total FROM detail_data_kusioner k, kuisoner s, data_pertemuan l WHERE k.`id_kuisoner` = s.`id`
AND l.`id` = s.`id_data_pertemuan`
AND l.`kd_matkul` = '".$matkul."' AND l.`kd_status` ='".$status."' AND k.`nilai` != '0' 
GROUP BY k.`pilihan` ORDER BY `total`");        
        return $query->result();
    }
    
     function cek_grafik_kuisoner($matkul, $status){
        $query = $this->db->query("SELECT k.`pilihan`,COUNT(k.`nilai`)  AS total FROM detail_data_kusioner k, kuisoner s, data_pertemuan l WHERE k.`id_kuisoner` = s.`id`
AND l.`id` = s.`id_data_pertemuan`
AND l.`kd_matkul` = '".$matkul."' AND l.`kd_status` ='".$status."' AND k.`nilai` != '0' 
GROUP BY k.`pilihan` ORDER BY `total`");        
        if($query->num_rows()>0){
            return true;
        }else{
            return false;
        }
    }
    
    function krsmahasiswa($dataarray)
    {
        for($i=0;$i<count($dataarray);$i++){
            $data = array(
                'nim'=>$dataarray[$i]['nim'],
                'kd_kls'=>$dataarray[$i]['kd_kls'],
                'kd_matkul'=>$dataarray[$i]['kd_matkul'],
                'id_status'=>$dataarray[$i]['status']
            );
            $this->db->insert('data_krsmahasiswa', $data);
        }
        return true;
    }
    
    function krsdosen($dataarray)
    {
        for($i=0;$i<count($dataarray);$i++){
            $data = array(
                'nip'=>$dataarray[$i]['nip'],
                'kd_kls'=>$dataarray[$i]['kd_kls'],
                'kd_matkul'=>$dataarray[$i]['kd_matkul'],
                'status'=>$dataarray[$i]['status']
            );
            $this->db->insert('data_krsdosen', $data);
        }
        return true;
    }
    
      function data_matkul($dataarray)
    {
        for($i=0;$i<count($dataarray);$i++){
            $data = array(
                'kd_matkul'=>$dataarray[$i]['kd_matkul'],
                'nm_matkul'=>$dataarray[$i]['nm_matkul'],
                'jml_sks'=>$dataarray[$i]['jml_sks'],
                'kd_semester'=>$dataarray[$i]['semester']
            );
            $this->db->insert('data_matkul', $data);
        }
        return true;
    }
    
      
      function data_upload_mahasiswa($dataarray)
    {
        for($i=0;$i<count($dataarray);$i++){
            $data = array(
                'nim'=>$dataarray[$i]['nim'],
                'nama'=>$dataarray[$i]['nama']
            );
            $this->db->insert('data_mahasiswa', $data);
        }
        return true;
    }
    
    function data_upload_login($dataarray)
    {
        for($i=0;$i<count($dataarray);$i++){
            $data = array(
                'username'=>$dataarray[$i]['username'],
                'password'=>  md5($dataarray[$i]['password']),
                'type' =>$dataarray[$i]['type']
            );
            $this->db->insert('login', $data);
        }
        return true;
    }


    /* ------------------------end Query Admin 
     * 
     */


    /* -----------------------------Query Mahasiswa
     * 
     */

    function add_kuisoner($id, $nim) {
        $this->db->set('id_data_pertemuan', $id);
        $this->db->set('nim', $nim);
        $this->db->insert('data_kuisoner');
        return true;
    }

    function add_kuisoner_total_nilai($matkul, $kelas, $status, $nim) {
        $this->db->set('status', $status);
        $this->db->set('nim', $nim);
        $this->db->set('data_matkul', $matkul);
        $this->db->set('data_kelas', $kelas);
        $this->db->insert('data_kuisoner_total_nilai');
        return true;
    }

    function add_kuisoner_detail($id,$soal,$nilai) {
        $this->db->set('id_kuisoner', $id);
        $this->db->set('pilihan', $soal);     
        $this->db->set('nilai', $nilai);
        $this->db->insert('detail_data_kusioner');
        return true;
    }

    function add_kuisoner_detail_total_nilai($id, $nilai) {
        $this->db->set('id_kuisoner_total_nilai', $id);
        $this->db->set('nilai', $nilai);
        $this->db->insert('detail_data_kuisoner_total_nilai');
        return true;
    }

    function ambil_id_kuisoner($id, $nim) {
        $query = $this->db->query("SELECT id FROM `siakad`.`kuisoner` WHERE 
                                        `id_data_pertemuan`= '" . $id . "' AND 
                                        `nim`='" . $nim . "'");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $id = $row->id;
            }
            return $id;
        }
        return false;
    }

    function ambil_id_kuisoner_total_nilai($nim, $matkul, $kelas, $status) {
        $query = $this->db->query("SELECT id FROM `siakad`.`kuisoner_total_nilai` WHERE 
                                        `matkul`= '" . $matkul . "' AND `data_kelas`='" . $kelas . "' and `status`='" . $status . "' and
                                        `nim`='" . $nim . "'");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $id = $row->id;
            }
            return $id;
        }
        return false;
    }

    function ambil_tabel_mahasiswa($nim, $kd_status) {
        $query = $this->db->query("SELECT * FROM `data_krsmahasiswa`,`data_matkul`,`data_kelas` WHERE `data_matkul`.`kd_matkul`=`data_krsmahasiswa`.`kd_matkul` and `data_krsmahasiswa`.`kd_kls` = `data_kelas`.`kd_kls` and `data_krsmahasiswa`.`id_status`='" . $kd_status . "'and `data_krsmahasiswa`.`nim` ='" . $nim . "'");
        return $query->result();
    }

    function ambil_tabel_data_pertemuan_mhs($kd_matkul, $kd_data_kelas, $kd_status) {
        $this->db->where('kd_matkul', $kd_matkul);
        $this->db->where('kd_data_kelas', $kd_data_kelas);
        $this->db->where('kd_status', $kd_status);
        $this->db->order_by('minggu_ke ASC');
        $query = $this->db->get('data_data_pertemuan');
        return $query->result();
    }

    function ambil_opsi_lihat_nilai($id_data_pertemuan, $nim) {
        $query = $this->db->query("SELECT  `opsi`.`keterangan`, `data_pertemuan_opsi`.`id` FROM `data_pertemuan`,`data_pertemuan_opsi`,`opsi`,`data_pertemuan_opsi_nilai` WHERE `data_pertemuan`.`id` = `data_pertemuan_opsi`.`id_data_pertemuan` AND
`opsi`.`id_opsi` = `data_pertemuan_opsi`.`id_opsi` and `data_pertemuan_opsi`.`id` = `data_pertemuan_opsi_nilai`.`id_data_pertemuan_opsi` AND `data_pertemuan`.`id` = '" . $id_data_pertemuan . "' and `data_pertemuan_opsi_nilai`.`nim` ='" . $nim . "'");
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }


    function get_nilai_detail_presensi($id, $nim) {
        $query = $this->db->query("SELECT GROUP_CONCAT(  `data_pertemuan_opsi_nilai`.`nilai` 
SEPARATOR  ';' ) as nilai_mhs, COUNT(*) AS total_nilai FROM `data_pertemuan`, `data_pertemuan_opsi` , `data_pertemuan_opsi_nilai` WHERE `data_pertemuan`.`id` = `data_pertemuan_opsi`.`id_data_pertemuan` AND
`data_pertemuan_opsi_nilai`.`id_data_pertemuan_opsi` = `data_pertemuan_opsi`.`id` AND `data_pertemuan`.`id` = '" . $id . "' AND `data_pertemuan_opsi_nilai`.`nim` ='" . $nim . "'");
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    

    function get_jumlah_data_pertemuan_mhs($matkul, $kelas, $status, $nim) {
        $query = $this->db->query("SELECT COUNT(*) AS jumlah_data_pertemuan FROM`siakad`.`absensi`INNER JOIN `siakad`.`data_data_pertemuan_absensi` 
                                           ON (`absensi`.`id_data_pertemuan_absensi` = `data_data_pertemuan_absensi`.`id_data_pertemuan_absensi`)
                                           WHERE  `data_data_pertemuan_absensi`.`kd_matkul`='" . $matkul . "' AND `data_data_pertemuan_absensi`.`kd_data_kelas`='" . $kelas . "' 
                                               AND `data_data_pertemuan_absensi`.`kd_status`='" . $status . "' AND`no_nim`='" . $nim . "'");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $hasil = $row->jumlah_data_pertemuan;
            }
        } else {
            $hasil = 0;
        }
        return $hasil;
    }

    function get_tdk_hadir_mhs($matkul, $kelas, $status, $nim) {
        $query = $this->db->query("SELECT COUNT(*) AS total_tidak_hadir FROM`siakad`.`absensi`INNER JOIN `siakad`.`data_data_pertemuan_absensi` 
                                           ON (`data_absensi`.`id_data_pertemuan_absensi` = `data_data_pertemuan_absensi`.`id_data_pertemuan_absensi`)
                                           WHERE  `data_data_pertemuan_absensi`.`kd_matkul`='" . $matkul . "' AND `data_data_pertemuan_absensi`.`kd_data_kelas`='" . $kelas . "' 
                                               AND `data_data_pertemuan_absensi`.`kd_status`='" . $status . "' AND 
                                                   `hadir`='0' AND`no_nim`='" . $nim . "'");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $hasil = $row->total_tidak_hadir;
            }
        } else {
            $hasil = 0;
        }
        return $hasil;
    }

    function get_hadir_mhs($matkul, $kelas, $status, $nim) {
        $query = $this->db->query("SELECT COUNT(*) AS total_hadir FROM`siakad`.`absensi`INNER JOIN `siakad`.`data_data_pertemuan_absensi` 
                                           ON (`absensi`.`id_data_pertemuan_absensi` = `data_data_pertemuan_absensi`.`id_data_pertemuan_absensi`)
                                           WHERE  `data_data_pertemuan_absensi`.`kd_matkul`='" . $matkul . "' AND `data_data_pertemuan_absensi`.`kd_data_kelas`='" . $kelas . "' 
                                               AND `data_data_pertemuan_absensi`.`kd_status`='" . $status . "' AND 
                                                   `hadir`='1' AND`no_nim`='" . $nim . "'");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $hasil = $row->total_hadir;
            }
        } else {
            $hasil = 0;
        }
        return $hasil;
    }

    function ambil_tabel_absensi_mhs($matkul, $kelas, $status, $nim) {
        $query = $this->db->query("SELECT * FROM `siakad`.`data_absensi` INNER JOIN `siakad`.`data_data_pertemuan_absensi` 
                                           ON (`data_absensi`.`id_data_pertemuan_absensi` = `data_data_pertemuan_absensi`.`id_data_pertemuan_absensi`)
                                           WHERE  `data_data_pertemuan_absensi`.`kd_matkul`='" . $matkul . "' AND `pdata_data_pertemuan_absensi`.`kd_data_kelas`='" . $kelas . "' 
                                               AND `data_data_pertemuan_absensi`.`kd_status`='" . $status . "' AND`no_nim`='" . $nim . "'");
        return $query->result();
    }

    function check_id_kuisoner_k($matkul, $kelas, $status, $nim) {
        $query = $this->db->query("select * from data_kuisoner_total_nilai a, detail_data_kuisoner_total_nilai b where `a`.`id` = `b`.`id_kuisoner_total_nilai` and `a`.`data_matkul` ='" . $matkul . "' and `a`.`data_data_kelas`='" . $kelas . "'and `a`.`status`='" . $status . "' and `a`.`nim` ='" . $nim . "'");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function ambil_total_minggu_mahasiswa($matkul, $kelas, $status) {
        $query = $this->db->query("SELECT `data_data_pertemuan`.`minggu_ke` FROM 
`data_data_pertemuan`,`data_data_pertemuan_opsi`,`data_data_pertemuan_opsi_nilai` WHERE `data_data_pertemuan`.`id` = `data_data_pertemuan_opsi`.`id_data_pertemuan` AND
`data_data_pertemuan_opsi`.`id` = `data_data_pertemuan_opsi_nilai`.`id_data_pertemuan_opsi` AND `data_data_pertemuan`.`kd_matkul`='" . $matkul . "' AND 
`data_data_pertemuan`.`kd_data_kelas`='" . $kelas . "' AND `data_data_pertemuan`.`kd_status`='" . $status . "'");
        return $query->result();
    }

    function ambil_nilai_simultan_mahasiswa($matkul, $kelas, $status, $nim, $minggu_ke) {
        $query = $this->db->query("SELECT `data_data_pertemuan_opsi_nilai`.`nim` , ROUND(SUM(`data_data_pertemuan_opsi_nilai`.`nilai`)/COUNT(`data_data_pertemuan_opsi`.`id_opsi`) * (`data_data_pertemuan`.`nilai_bobot`/100),2)  AS nilai FROM 
`data_data_pertemuan`,`data_data_pertemuan_opsi`,`data_data_pertemuan_opsi_nilai` WHERE `data_data_pertemuan`.`id` = `data_data_pertemuan_opsi`.`id_data_pertemuan` AND
`data_data_pertemuan_opsi`.`id` = `data_data_pertemuan_opsi_nilai`.`id_data_pertemuan_opsi` AND `data_data_pertemuan`.`kd_matkul`='" . $matkul . "' AND 
`data_data_pertemuan`.`kd_data_kelas`='" . $kelas . "' AND `data_data_pertemuan`.`kd_status`='" . $status . "' AND `data_data_pertemuan`.`minggu_ke`='" . $minggu_ke . "' and `data_data_pertemuan_opsi_nilai`.`nim` ='" . $nim . "'
GROUP BY `data_data_pertemuan_opsi_nilai`.`nim`");
        return $query->result_array();
    }

    function get_total_data_pertemuan_absensi_mahasiswa($matkul, $kelas, $status) {
        $query = $this->db->query("SELECT COUNT(`tanggal`) AS data_data_pertemuan FROM `siakad`.`data_data_pertemuan_absensi` 
                                            WHERE `data_data_pertemuan_absensi`.`kd_matkul` = '" . $matkul . "' AND 
                                                   `data_data_pertemuan_absensi`.`kd_data_kelas`='" . $kelas . "'AND `data_data_pertemuan_absensi`.`kd_status`='" . $status . "'
                                                   ");
        return $query->row();
    }

    function get_total_absensi_mahasiswa($matkul, $kelas, $status, $nim) {
        $query = $this->db->query("SELECT COUNT(*) AS total_tidak_hadir FROM`siakad`.`data_absensi`INNER JOIN `siakad`.`data_data_pertemuan_absensi` 
                                           ON (`data_absensi`.`id_data_pertemuan_absensi` = `data_data_pertemuan_absensi`.`id_data_pertemuan_absensi`)
                                           WHERE  `data_data_pertemuan_absensi`.`kd_matkul`='" . $matkul . "' AND `data_data_pertemuan_absensi`.`kd_data_kelas`='" . $kelas . "' 
                                               AND `data_data_pertemuan_absensi`.`kd_status`='" . $status . "' AND
                                                   `hadir`='0' AND`no_nim`='" . $nim . "'");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $hasil = $row->total_tidak_hadir;
            }
        } else {
            $hasil = 0;
        }
        return $hasil;
    }

    function get_total_minggu_mahasiswa($matkul, $kelas, $status) {
        $query = $this->db->query("SELECT  `data_data_pertemuan`.`minggu_ke` FROM  `siakad`.`data_data_pertemuan` 
                                       WHERE `kd_matkul` ='" . $matkul . "' 
                                              AND `kd_data_kelas` ='" . $kelas . "'and `kd_status`='" . $status . "' order by  `data_data_pertemuan`.`minggu_ke` desc limit 1");
        if ($query->num_rows() > 0) {
            $data = $query->row();
        } else {
            $data = 0;
        }
        return $data;
    }

    /* ----------------------------end Query Mahasiswa
     *
     */

    /* ----------------------Query Dosen---------------------
     * 
     */

    function ambil_krs_dosen($nip, $status) {
        $query = $this->db->query("SELECT * FROM data_krsdosen a,matkul b, data_data_kelas c WHERE a.`kd_matkul` = b.`kd_matkul` AND  a.`kd_kls` = c.`kd_kls` and a.nip ='" . $nip . "' and a.status='" . $status . "'");
        return $query->result();
    }

    function check_mahasiswa($kelas, $matkul, $status) {
        $query = $this->db->query("SELECT `data_mahasiswa`.`nama`, `data_mahasiswa`.`nim` FROM `siakad`.`data_mahasiswa`
                                        INNER JOIN `siakad`.`data_krsmahasiswa` ON 
                                  (`data_mahasiswa`.`nim` = `data_data_krsmahasiswa`.`nim`) WHERE 
                                  `data_krsmahasiswa`.`kd_kls` = '" . $kelas . "'AND `data_krsmahasiswa`.`kd_matkul`=	'" . $matkul . "'
                                   AND`data_krsmahasiswa`.`id_status`='" . $status . "'");
        if ($query->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    function ambil_data_pertemuan($matkul, $kelas, $status) {
        $query = $this->db->query("SELECT `minggu_ke`FROM `siakad`.`data_data_pertemuan` 
                                       WHERE `data_data_pertemuan`.`kd_matkul` ='" . $matkul . "'AND 
                                       `data_data_pertemuan`.`kd_data_kelas`='" . $kelas . "'and 
                                       `data_data_pertemuan`.`kd_status`='" . $status . "'
                                       ORDER BY `minggu_ke` DESC LIMIT 1");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data_pertemuan = $row->minggu_ke + 1;
            }
        } else {
            $data_pertemuan = 0 + 1;
        }
        return $data_pertemuan;
    }

    function ambil_tabel_data_pertemuan($kd_matkul,  $kd_data_kelas, $kd_status) {
        $this->db->where('kd_matkul', $kd_matkul);
        $this->db->where('kd_data_kelas', $kd_data_kelas);
        $this->db->where('kd_status', $kd_status);
        $this->db->order_by('minggu_ke ASC');
        $query = $this->db->get('data_data_pertemuan');
        return $query->result();
    }

    function ambil_simultan($kelas, $matkul, $status) {
        $query = $this->db->query("SELECT `data_mahasiswa`.`nama`, `data_mahasiswa`.`nim` FROM `siakad`.`data_mahasiswa`
                                        INNER JOIN `siakad`.`data_krsmahasiswa` ON 
                                  (`data_mahasiswa`.`nim` = `data_krsmahasiswa`.`nim`) WHERE 
                                  `data_krsmahasiswa`.`kd_kls` = '" . $kelas . "'AND `data_krsmahasiswa`.`kd_matkul`=	'" . $matkul . "'
                                   AND`data_krsmahasiswa`.`id_status`='" . $status . "'");
        return $query->result();
    }

    function ambil_opsi() {
        // $this->db->where('id_dosen',$nip);
        $query = $this->db->get('opsi');
        return $query->result();
    }

   

    //model dosen
    function ambil_status() {
        $query = $this->db->query("SELECT `status_kuliah`.`kd` FROM `status_kuliah` WHERE `status_kuliah`.`status`='1'");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $ambil = $row->kd;
            }
            return $ambil;
        } else {
            return FALSE;
        }
    }

    function ambil_opsi_update($id_data_pertemuan) {
        $query = $this->db->query("SELECT  `opsi`.`keterangan`, `data_data_pertemuan_opsi`.`id` FROM `data_data_pertemuan`,`data_data_pertemuan_opsi`,`opsi` WHERE `data_data_pertemuan`.`id` = `data_data_pertemuan_opsi`.`id_data_pertemuan` AND
`opsi`.`id_opsi` = `data_data_pertemuan_opsi`.`id_opsi` AND `data_data_pertemuan`.`id` = '" . $id_data_pertemuan . "'");
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

   

    function ambil_tabel_absensi($kd_matkul,  $kd_data_kelas, $kd_status) {
        $query = $this->db->query("SELECT * FROM `data_data_pertemuan_absensi`,`data_data_pertemuan` WHERE `data_data_pertemuan_absensi`.`id_data_pertemuan` = `data_data_pertemuan`.`id` AND 
`data_data_pertemuan_absensi`.`kd_matkul`='".$kd_matkul."' AND `data_data_pertemuan_absensi`.`kd_data_kelas`='".$kd_data_kelas."' AND `data_data_pertemuan_absensi`.`kd_status`='".$kd_status."'  order by `data_data_pertemuan`.`minggu_ke`");
        return $query->result();
    }

    function add_data_data_pertemuan($kd_matkul, $kd_dosen, $kd_data_kelas, $kd_status, $minggu_ke, $bobot) {
        $this->db->set('kd_matkul', $kd_matkul);
        $this->db->set('kd_dosen', $kd_dosen);
        $this->db->set('kd_data_kelas', $kd_data_kelas);
        $this->db->set('kd_status', $kd_status);
        $this->db->set('minggu_ke', $minggu_ke);
        $this->db->set('nilai_bobot', $bobot);
        $this->db->insert('data_data_pertemuan');
        return true;
    }

    function add_data_data_pertemuan_absensi($kd_matkul, $kd_dosen, $kd_data_kelas, $kd_status, $tanggal, $kompetensi) {
        $this->db->set('kd_matkul', $kd_matkul);
        $this->db->set('kd_dosen', $kd_dosen);
        $this->db->set('kd_data_kelas', $kd_data_kelas);
        $this->db->set('kd_status', $kd_status);
        $this->db->set('id_data_pertemuan', $kompetensi);
        $this->db->set('tanggal', $tanggal);
        $this->db->insert('data_data_pertemuan_absensi');
        return true;
    }

  

    function add_data_pertemuan_opsi($id, $opsi) {
        $this->db->set('id_data_pertemuan', $id);
        $this->db->set('id_opsi', $opsi);
        $this->db->insert('data_data_pertemuan_opsi');
        return true;
    }

    function ambil_tanggal_abensi($matkul, $kelas, $status, $id_data_pertemuan_absensi) {
        $query = $this->db->query("SELECT tanggal,id_data_pertemuan FROM `siakad`.`data_data_pertemuan_absensi` WHERE 
                                        `kd_matkul`= '" . $matkul . "' AND 
                                        `kd_data_kelas`='" . $kelas . "' 
                                        AND `kd_status`='" . $status . "' AND
                                        `id_data_pertemuan_absensi` ='" . $id_data_pertemuan_absensi . "'    
                                       ");
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    function delete_data_data_pertemuan($id) {
        $query = $this->db->query("Delete `data_data_pertemuan`,`data_data_pertemuan_opsi` FROM `siakad`.`data_data_pertemuan` INNER JOIN `siakad`.`data_data_pertemuan_opsi` 
        ON (`data_data_pertemuan`.`id` = `data_data_pertemuan_opsi`.`id_data_pertemuan`)
        where `data_data_pertemuan`.`id`='" . $id . "'");
        return $query;
    }

   
    
    function delete_data_data_pertemuan_nilai_detail($id) {
        $query = $this->db->query("DELETE FROM `data_data_pertemuan_opsi_nilai` WHERE `data_data_pertemuan_opsi_nilai`.`id_data_pertemuan_opsi`='" . $id . "'");
        return $query;
    }
    
   
    
    function check_id_nilai($id){
        $query = $this->db->query("SELECT `data_data_pertemuan_opsi`.`id` FROM `data_pertemuan_opsi` WHERE  `data_data_pertemuan_opsi`.`id_data_pertemuan`='".$id."'");
        return $query->result();
    }
    
    


    function delete_data_data_pertemuan_absensi($id) {
        $this->db->query("DELETE FROM  `data_data_pertemuan_absensi` WHERE  `data_data_pertemuan_absensi`.`id_data_pertemuan_absensi` ='" . $id . "'");
        return true;
    }

    function delete_data_absensi($id) {
        $this->db->query("DELETE FROM  `data_absensi` WHERE  `data_absensi`.`id_data_pertemuan_absensi` ='" . $id . "'");
        return true;
    }

    function get_data_data_pertemuan_id($id) {
        return $this->db->get_where('data_data_pertemuan', array('id' => $id))->row();
    }

    function update_data_data_pertemuan($id, $nilai_bobot, $kd_kls, $kd_matkul, $status, $minggu_ke) {
        $data = array(
            'nilai_bobot' => $nilai_bobot,
            'kd_data_kelas' => $kd_kls,
            'kd_matkul' => $kd_matkul,
            'minggu_ke' => $minggu_ke,
            'kd_status' => $status
        );
        $this->db->where('id', $id);
        $this->db->update('data_data_pertemuan', $data);
        return true;
    }

    function update_data_data_pertemuan_absensi($date, $kompetensi, $id_data_pertemuan_absensi) {
        $data = array(
            'tanggal' => $date,
            'id_data_pertemuan' => $kompetensi
        );
        $this->db->where('id_data_pertemuan_absensi', $id_data_pertemuan_absensi);
        $this->db->update('data_data_pertemuan_absensi', $data);
        return true;
    }

    function ambil_id_terakhir($matkul, $kelas, $status) {
        $query = $this->db->query("SELECT id FROM `siakad`.`data_data_pertemuan` WHERE 
                                        `kd_matkul`= '" . $matkul . "' AND 
                                        `kd_data_kelas`='" . $kelas . "' AND 
                                       `kd_status`='" . $status . "' 
                                        ORDER BY `minggu_ke` DESC LIMIT 1");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $id_terakhir = $row->id;
            }
            return $id_terakhir;
        }
        return false;
    }

    function ambil_id_absensi($matkul, $kelas, $dosen, $status, $tanggal, $kompetensi) {
        $query = $this->db->query("SELECT id_data_pertemuan_absensi FROM `siakad`.`data_data_pertemuan_absensi` WHERE 
                                        `kd_matkul`= '" . $matkul . "' AND 
                                        `kd_data_kelas`='" . $kelas . "' AND 
                                        `kd_dosen`='" . $dosen . "' 
                                        AND `kd_status`='" . $status . "' AND `id_data_pertemuan`='" . $kompetensi . "' and
                                        `tanggal` ='" . $tanggal . "'    
                                       ");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $id = $row->id_data_pertemuan_absensi;
            }
            return $id;
        }
        return false;
    }

    function ambil_id_data_pertemuan($matkul, $kelas, $dosen, $status, $minggu_ke) {
        $query = $this->db->query("SELECT id FROM `siakad`.`data_data_pertemuan` WHERE 
                                        `kd_matkul`= '" . $matkul . "' AND 
                                        `kd_data_kelas`='" . $kelas . "' AND 
                                        `kd_dosen`='" . $dosen . "' 
                                        AND `kd_status`='" . $status . "'
                                        AND `minggu_ke`='" . $minggu_ke . "'");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $id = $row->id;
            }
            return $id;
        }
        return false;
    }

    function ambil_id_data_pertemuan_opsi($id_data_pertemuan) {
        $query = $this->db->query("SELECT id FROM `siakad`.`data_pertemuan_opsi` WHERE 
                                        `id_data_pertemuan`= '" . $id_data_pertemuan . "'");
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    

    function ambil_data_pertemuan_id($id) {
        $query = $this->db->query("SELECT  * FROM  `siakad`.`data_pertemuan` 
                                       WHERE `data_pertemuan`.`id`='" . $id . "'");
        return $query->row();
    }

    function check_tanggal($tanggal,  $kd_matkul, $kd_data_kelas, $status, $kompetensi) {
        $query = $this->db->query("SELECT `tanggal` FROM `data_pertemuan_absensi` WHERE `data_pertemuan_absensi`.`tanggal` = '" . $tanggal . "' AND
                                 `data_pertemuan_absensi`.`kd_data_kelas` ='" . $kd_data_kelas . "' AND `data_pertemuan_absensi`.`kd_matkul`='" . $kd_matkul . "' AND `data_pertemuan_absensi`.`kd_status`='" . $status . "' and `data_pertemuan_absensi`.`id_data_pertemuan`='" . $kompetensi . "'");
        if ($query->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    function ambil_simultan_array($kelas, $matkul, $status) {
        $query = $this->db->query("SELECT `mahasiswa`.`nama`, `mahasiswa`.`nim` FROM `siakad`.`mahasiswa`
                                        INNER JOIN `siakad`.`krsmahasiswa` ON 
                                  (`mahasiswa`.`nim` = `krsmahasiswa`.`nim`) WHERE 
                                  `krsmahasiswa`.`kd_kls` = '" . $kelas . "'AND `krsmahasiswa`.`kd_matkul`=	'" . $matkul . "'
                                   AND`krsmahasiswa`.`id_status`='" . $status . "'");
        return $query->result_array();
    }


    function add_simultan_nilai($id_data_pertemuan_opsi, $nim) {
        $this->db->set("nim", $nim);
        $this->db->set("id_data_pertemuan_opsi", $id_data_pertemuan_opsi);
        $this->db->set("nilai", 0);
        $query = $this->db->insert("data_pertemuan_opsi_nilai");
        if ($query) {
            return true;
        } else {
            return false;
        }
    }


    

    function add_simultan_absensi($nim, $hadir, $id_data_pertemuan) {
        $this->db->set("no_nim", $nim);
        $this->db->set("hadir", $hadir);
        $this->db->set("id_data_pertemuan_absensi", $id_data_pertemuan);
        $query = $this->db->insert("absensi");
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    function check_id_simultan($id_data_pertemuan) {
        $this->db->where('id_data_pertemuan', $id_data_pertemuan);
        $query = $this->db->get('nilai');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function check_id_presensi_nilai($id_data_pertemuan) {
        $query = $this->db->query("select * from `data_pertemuan`,`data_pertemuan_opsi`,`data_pertemuan_opsi_nilai` where `data_pertemuan`.`id` = `data_pertemuan_opsi`.`id_data_pertemuan` and
`data_pertemuan_opsi`.`id` = `data_pertemuan_opsi_nilai`.`id_data_pertemuan_opsi` and `data_pertemuan`.`id`='" . $id_data_pertemuan . "'");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    

    function check_id_kuisoner($id_data_pertemuan, $mahasiswa) {
        $query = $this->db->query("select * from kuisoner a, detail_data_kusioner b where `a`.`id` = `b`.`id_kuisoner` and `a`.`id_data_pertemuan` ='" . $id_data_pertemuan . "' and `a`.`nim` ='" . $mahasiswa . "'");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_id_simultan($id) {
        $query = $this->db->query("SELECT `mahasiswa`.`nama`,`data_pertemuan_opsi_nilai`.`nim` , GROUP_CONCAT(  `data_pertemuan_opsi_nilai`.`nilai` ORDER BY `data_pertemuan_opsi_nilai`.`id`
SEPARATOR  ';' ) as nilai_mhs, count(*) as total_mhs,  GROUP_CONCAT(  `data_pertemuan_opsi_nilai`.`id` 
SEPARATOR  ';' ) as id_mhs FROM `data_pertemuan`,`data_pertemuan_opsi`,`data_pertemuan_opsi_nilai`,`mahasiswa` WHERE `data_pertemuan`.`id` = `data_pertemuan_opsi`.`id_data_pertemuan` AND
`data_pertemuan_opsi`.`id` = `data_pertemuan_opsi_nilai`.`id_data_pertemuan_opsi` AND `data_pertemuan`.`id`='" . $id . "' AND `mahasiswa`.`nim` = `data_pertemuan_opsi_nilai`.`nim` group by `data_pertemuan_opsi_nilai`.`nim`");
        return $query->result_array();
    }


    function get_id_simultan_mhs($id) {
        $query = $this->db->query("SELECT `data_data_pertemuan_opsi_nilai_kategori`.`nim` , `mahasiswa`.`nama` FROM `data_pertemuan`,`data_data_pertemuan_opsi_kategori`,`data_data_pertemuan_opsi_nilai_kategori`,`data_mahasiswa`,`data_kategori`,`data_opsi_kategori` WHERE `data_data_pertemuan`.`id` = `data_data_pertemuan_opsi_kategori`.`id_data_pertemuan` AND
`data_data_pertemuan_opsi_kategori`.`id` = `data_data_pertemuan_opsi_nilai_kategori`.`id_data_pertemuan_opsi` AND `data_data_pertemuan`.`id`='" . $id . "' AND `data_mahasiswa`.`nim` = `data_data_pertemuan_opsi_nilai_kategori`.`nim` AND `data_kategori`.`kd_kat` = `data_data_pertemuan_opsi_nilai_kategori`.`nilai` AND `data_opsi_kategori`.`id_opsi` = `data_data_pertemuan_opsi_kategori`.`id_opsi`  GROUP BY `data_data_pertemuan_opsi_nilai_kategori`.`nim` ");
        return $query->result_array();
    }

    function get_id_simultan_absensi($id) {
        $query = $this->db->query("SELECT  * FROM  `siakad`.`mahasiswa` INNER JOIN `siakad`.`absensi` 
                                       ON (`mahasiswa`.`nim` = `absensi`.`no_nim`) WHERE `id_data_pertemuan_absensi`='" . $id . "'");
        return $query->result();
    }


    function update_id_presensi($nim, $nilai, $id) {
        $data = array(
            'nim' => $nim,
            'nilai' => $nilai
        );
        $this->db->where('id', $id);
        $this->db->update('data_data_pertemuan_opsi_nilai', $data);
        return true;
    }


    function update_simultan_absensi($nim, $hadir, $id_data_pertemuan_absensi_ganti, $id_absensi) {
        $data = array(
            'no_nim' => $nim,
            'hadir' => $hadir,
            'id_data_pertemuan_absensi' => $id_data_pertemuan_absensi_ganti
        );
        $this->db->where('id', $id_absensi);
        $this->db->update('absensi', $data);
    }

    function get_minggu($matkul, $kelas, $status) {
        $query = $this->db->query("SELECT  `data_pertemuan`.`minggu_ke` FROM  `siakad`.`data_pertemuan` 
                                       WHERE `kd_matkul` ='" . $matkul . "' 
                                              AND `kd_data_kelas` ='" . $kelas . "' and `kd_status`='" . $status . "' order by `data_pertemuan`.`minggu_ke` asc");
        return $query->result();
    }

    function total_nilai_minggu($kelas, $minggu, $matkul) {
        $query = $this->db->query("SELECT  ROUND((`lisan`+`rerata`)/2 * (`data_pertemuan`.`nilai_bobot`/100),2) AS 
                                            nilai FROM `siakad`.`nilai` INNER JOIN `siakad`.`data_pertemuan` ON 
                                            (`nilai`.`id_data_pertemuan` = `data_pertemuan`.`id`) WHERE `kd_matkul` ='" . $matkul . "' 
                                              AND `kd_data_kelas` ='" . $kelas . "' AND `minggu_ke`='" . $minggu . "'");
        return $query->result_array();
    }


    function get_total_minggu($matkul, $kelas, $status, $dosen) {
        $query = $this->db->query("SELECT  `data_pertemuan`.`minggu_ke` FROM  `siakad`.`data_pertemuan` 
                                       WHERE `kd_matkul` ='" . $matkul . "' 
                                              AND `kd_data_kelas` ='" . $kelas . "'and `kd_status`='" . $status . "' and `kd_dosen`='" . $dosen . "' order by  `data_pertemuan`.`minggu_ke` desc limit 1");
        if ($query->num_rows() > 0) {
            $data = $query->row();
        } else {
            $data = 0;
        }
        return $data;
    }

    function get_total_data_pertemuan_absensi($matkul, $kelas, $status, $dosen) {
        $query = $this->db->query("SELECT COUNT(`tanggal`) AS data_pertemuan FROM `siakad`.`data_pertemuan_absensi` 
                                            WHERE `data_pertemuan_absensi`.`kd_matkul` = '" . $matkul . "' AND 
                                                   `data_pertemuan_absensi`.`kd_data_kelas`='" . $kelas . "'AND `data_pertemuan_absensi`.`kd_status`='" . $status . "'
                                                   AND `data_pertemuan_absensi`.`kd_dosen`='" . $dosen . "'");
        return $query->row();
    }

    function get_total_mahasiswa($matkul, $kelas, $status) {
        $query = $this->db->query("SELECT count(`mahasiswa`.`nim`) as total_mahasiswa FROM `siakad`.`mahasiswa`
                                        INNER JOIN `siakad`.`krsmahasiswa` ON 
                                  (`mahasiswa`.`nim` = `krsmahasiswa`.`nim`) WHERE 
                                  `krsmahasiswa`.`kd_kls` = '" . $kelas . "'AND `krsmahasiswa`.`kd_matkul`=	'" . $matkul . "'
                                   AND `krsmahasiswa`.`id_status`='" . $status . "'");
        return $query->row();
    }

    function get_total_absensi($matkul, $kelas, $status, $dosen, $nim) {
        $query = $this->db->query("SELECT COUNT(*) AS total_tidak_hadir FROM`siakad`.`absensi`INNER JOIN `siakad`.`data_pertemuan_absensi` 
                                           ON (`absensi`.`id_data_pertemuan_absensi` = `data_pertemuan_absensi`.`id_data_pertemuan_absensi`)
                                           WHERE  `data_pertemuan_absensi`.`kd_matkul`='" . $matkul . "' AND `data_pertemuan_absensi`.`kd_data_kelas`='" . $kelas . "' 
                                               AND `data_pertemuan_absensi`.`kd_status`='" . $status . "' AND `data_pertemuan_absensi`.`kd_dosen`='" . $dosen . "' AND
                                                   `hadir`='0' AND`no_nim`='" . $nim . "'");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $hasil = $row->total_tidak_hadir;
            }
        } else {
            $hasil = 0;
        }
        return $hasil;
    }

    //mahasiswa get

    function get_data_pertemuan_presensi($id) {
        $query = $this->db->query("SELECT * FROM `siakad`.`data_pertemuan` WHERE 
                                  `data_pertemuan`.`id` = '" . $id . "'");
        return $query->row();
    }

    function ambil_data_pertemuan_presensi($kelas, $matkul, $status) {
        $query = $this->db->query("SELECT  * FROM `data_pertemuan` WHERE 
                                  `data_pertemuan`.`kd_data_kelas` = '" . $kelas . "'AND `data_pertemuan`.`kd_matkul`='" . $matkul . "'
                                   AND`data_pertemuan`.`kd_status`='" . $status . "'");
        return $query->result();
    }

    function ambil_mahasiswa($kelas, $matkul, $status) {
        $query = $this->db->query("SELECT  `mahasiswa`.`nim`,`mahasiswa`.`nama` FROM `siakad`.`mahasiswa`
                                        INNER JOIN `siakad`.`krsmahasiswa` ON 
                                  (`mahasiswa`.`nim` = `krsmahasiswa`.`nim`) WHERE 
                                  `krsmahasiswa`.`kd_kls` = '" . $kelas . "'AND `krsmahasiswa`.`kd_matkul`=	'" . $matkul . "'
                                   AND`krsmahasiswa`.`id_status`='" . $status . "'");
        return $query->result();
    }

   
    function ambil_nilai_simultan($matkul, $kelas, $status, $dosen, $minggu_ke) {
        $query = $this->db->query("SELECT `data_pertemuan_opsi_nilai`.`nim` , ROUND(SUM(`data_pertemuan_opsi_nilai`.`nilai`)/COUNT(`data_pertemuan_opsi`.`id_opsi`) * (`data_pertemuan`.`nilai_bobot`/100),2)  AS nilai FROM 
`data_pertemuan`,`data_pertemuan_opsi`,`data_pertemuan_opsi_nilai` WHERE `data_pertemuan`.`id` = `data_pertemuan_opsi`.`id_data_pertemuan` AND
`data_pertemuan_opsi`.`id` = `data_pertemuan_opsi_nilai`.`id_data_pertemuan_opsi` AND `data_pertemuan`.`kd_matkul`='" . $matkul . "' AND 
`data_pertemuan`.`kd_data_kelas`='" . $kelas . "' AND `data_pertemuan`.`kd_dosen`='" . $dosen . "' AND `data_pertemuan`.`kd_status`='" . $status . "' AND `data_pertemuan`.`minggu_ke`='" . $minggu_ke . "' 
GROUP BY `data_pertemuan_opsi_nilai`.`nim`");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            throw new Exception("record kosong");
        }
    }

    function check_nilai_simultan($matkul, $kelas, $status) {
        $query = $this->db->query("SELECT count(*) as nilai FROM 
`data_pertemuan`,`data_pertemuan_opsi`,`data_pertemuan_opsi_nilai` WHERE `data_pertemuan`.`id` = `data_pertemuan_opsi`.`id_data_pertemuan` AND
`data_pertemuan_opsi`.`id` = `data_pertemuan_opsi_nilai`.`id_data_pertemuan_opsi` AND `data_pertemuan`.`kd_matkul`='" . $matkul . "' AND 
`data_pertemuan`.`kd_data_kelas`='" . $kelas . "'  AND `data_pertemuan`.`kd_status`='" . $status . "'");
        return $query->row();
    }

    function ambil_total_minggu($matkul, $kelas, $status, $dosen) {
        $query = $this->db->query("SELECT `data_pertemuan`.`minggu_ke` FROM 
`data_pertemuan`,`data_pertemuan_opsi`,`data_pertemuan_opsi_nilai` WHERE `data_pertemuan`.`id` = `data_pertemuan_opsi`.`id_data_pertemuan` AND
`data_pertemuan_opsi`.`id` = `data_pertemuan_opsi_nilai`.`id_data_pertemuan_opsi` AND `data_pertemuan`.`kd_matkul`='" . $matkul . "' AND 
`data_pertemuan`.`kd_data_kelas`='" . $kelas . "' AND `data_pertemuan`.`kd_dosen`='" . $dosen . "' AND `data_pertemuan`.`kd_status`='" . $status . "'");
        return $query->result();
    }

}