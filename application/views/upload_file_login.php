<div class="container">
       <?php
$operasi = $this->session->userdata('operation');
if ($operasi != null) {
    if ($operasi == "sukses") {
        echo '<div class="alert alert-success">
                 <a class="close" data-dismiss="alert">×</a>
                 <i class="icon icon-thumbs-up-alt"></i> <b>Selamat</b>' . $this->session->userdata('message') . '</i>
                 </div>';
    } else if ($operasi == "validasi") {
        echo '<div class="alert alert-error">
      <a class="close" data-dismiss="alert">×</a>
      <i class="icon icon-warning-sign"></i> <b>Maaf</b> ' . $this->session->userdata('message') . '</i>
    </div>';
    } else if ($operasi == "duplicate") {
        echo '<div class="alert alert-error">
      <a class="close" data-dismiss="alert">×</a>
      <i class="icon icon-warning-sign"></i> <b>Maaf</b> ' . $this->session->userdata('message') . '</i>
    </div>';
    } else if ($operasi == "gagal") {
        echo '<div class="alert alert-error">
      <a class="close" data-dismiss="alert">×</a>
      <i class="icon icon-warning-sign"></i> <b>Maaf</b> ' . $this->session->userdata('message') . '</i>
    </div>';
    }
}
$this->session->unset_userdata('operation');
$this->session->unset_userdata('message');
$this->session->unset_userdata('message1');
?>
<div class="row-fluid">
        <!-- Forms: Box -->
        <div class="span12">
            <!-- Forms: Top Bar -->
            <div class="top-bar">
                <h3><i class="icon-cog"></i>Upload File Login</h3>
            </div>       
            <div class="well">
                <div class="pull-right" style="margin:30px 30px 30px 30px;">
                    <p><h4>[DATA LOGIN]</h4></p> 
                </div>
                <blockquote style="padding:20px 10px 20px 10px; color:#333; margin:30px 30px 30px 30px;" >
                    <p>Silahkan upload file login, file yang digunakan harus sesuai dengan format yang sudah diberikan</p>
                    <p>Harus ditambah satu kolom dengan isian type login. Type 1 untuk admin, type 2 untuk dosen dan type 3 untuk Mahasiswa.(Lihat Contoh Excel)</p>
                </blockquote>
             <?php echo form_open_multipart('admin/proses_upload_file_login');?>
                         <label  style="margin:10px; padding-left:130px; font-size: 14px; ">Pilih File :                
                <input type="file" class="span4 m-wrap"  id="file_upload" name="userfile" size="20" /></label>
                 <div class="form-actions" style="padding-left:150px; ">
                        <button type="submit" class="btn btn-primary">Upload</button>   
                    </div>
                </form>
            </div>
        </div>
</div>