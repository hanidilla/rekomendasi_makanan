@extends('pages.web.layouts.main')


@section('content')

    <section class="slider_section ">
      <div class="dot_design">
        <img src="images/dots.png" alt="">
      </div>
      <div id="customCarousel1" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="container ">
              <div class="row">
                <div class="col-md-6">
                  <div class="detail-box">
                    <div class="play_btn">
                      <button >
                        <i class="fa fa-home" aria-hidden="true"></i>
                      </button>
                    </div>
                    <h1>
                     Selamat <br>
                     <span>
                       Datang Di
                     </span>
                    </h1>
                    <p>
                      Sistem Informasi Rekomendasi Makanan Diabetes, Nutrisi atau gizi adalah substansi organik yang dibutuhkan organisme untuk fungsi normal dari sistem tubuh, pertumbuhan, dan pemeliharaan kesehatan. Penelitian di bidang nutrisi mempelajari hubungan antara makanan dan minuman terhadap kesehatan dan penyakit, khususnya dalam menentukan diet yang optimal
                    </p>
                    <a href="#about" style="background-color: #bd59d4">
                      Lanjut Yuk
                    </a>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="img-box">
                    <img src="images/nakam.png" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  <section class="about_section" id="about">
    <div class="container  ">
      <div class="row">
        <div class="col-md-6 ">
          <div class="img-box">
            <img src="images/makan.png" alt="">
          </div>
        </div>
        <div class="col-md-6">
          <div class="detail-box">
            <div class="heading_container">
              <h2>
                Tentang <span>Sistem</span>
              </h2>
            </div>
            <p>
              Sistem penentu rekomendasi makanan untuk diabetes merupakan sebuah sistem yang dirancang untuk memberikan panduan dan rekomendasi nutrisi kepada individu yang mengidap diabetes. Tujuan utama dari sistem ini adalah untuk membantu pengendalian gula darah, menjaga berat badan yang sehat, dan mempromosikan pola makan seimbang.
            </p>
            <a href="#pencarian" style="background-color: #bd59d4">
              Kepoin Yuk
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end about section -->
  <section class="book_section layout_padding" id="pencarian">
    <div class="container">
      <div class="row">
        <div class="col">
          <form action="/">
            <h4>
              CARI <span>DATA KAMU DISINI</span>
            </h4>
            <div class="form-row ">
              <div class="form-group col-lg-12">
                <label for="inputPatientName">Kode Pasien</label>
                <input type="text" class="form-control" id="inputPatientName" name="kode_pasien" required placeholder="Contoh : RKMDM5652">
                @if ($ada != null)
                	<br>
                	<m style="color: red">{{$ada}}</m>
                	@endif
                <p style="padding-top: 5%" align="center">
					
                	<b>Note</b> : Jika ada kendala silahkan hubungi admin <a target="_blank" 
                	href="https://wa.me/6289676237780">disini</a>

                	
                </p>

            </div>
    
            <div class="btn-box col-lg-12" align="center">
              <button type="submit" class="btn" style="background-color: #bd59d4">Cek Sekarang</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>




  @endsection