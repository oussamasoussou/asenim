@extends('front.layout')

@section('title', 'ICMIC')
@section('body_class', 'starter-page-page icmic-page')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
<style>
    
        
</style>
@endpush

@section('content')


  <main class="main">

    <!-- Page Title 
    <div class="page-title dark-background">
      <div class="container position-relative">
        <h1>Bureau Executive </h1>
        
          
        
      </div>
    </div> Title -->


<section class="executif-section mt-xl-6 mt-5 ">
         <h2 class="title">ICMIC </h2>
        <span class="section-line"></span>
       
   

    <div class="team-container mt-xl-6 mt-5">
  <div class="row">
                                        <div class="col-lg-12 col-12 img-gallery mx-auto">
                                            	  
		  
		  
		  
		   
 <a href="images/gallery/ICMIC01/Days/1.jpeg" data-fancybox="gallery" data-caption="ICMIC01"><img src="images/gallery/ICMIC01/Days/1.jpeg">	</a>
 <a href="images/gallery/ICMIC01/Days/2.jpg" data-fancybox="gallery" data-caption="ICMIC01"><img src="images/gallery/ICMIC01/Days/2.jpg">	</a>
 <a href="images/gallery/ICMIC01/Days/3.jpg" data-fancybox="gallery" data-caption="ICMIC01"><img src="images/gallery/ICMIC01/Days/3.jpg">	</a>
 <a href="images/gallery/ICMIC01/Days/4.jpeg" data-fancybox="gallery" data-caption="ICMIC01"><img src="images/gallery/ICMIC01/Days/4.jpeg">	</a>
 <a href="images/gallery/ICMIC01/Days/5.jpeg" data-fancybox="gallery" data-caption="ICMIC01"><img src="images/gallery/ICMIC01/Days/5.jpeg">	</a>
 <a href="images/gallery/ICMIC01/Days/6.jpg" data-fancybox="gallery" data-caption="ICMIC01"><img src="images/gallery/ICMIC01/Days/6.jpg">	</a> 
 <a href="images/gallery/ICMIC01/Days/7.jpg" data-fancybox="gallery" data-caption="ICMIC01"><img src="images/gallery/ICMIC01/Days/7.jpg">	</a>
 <a href="images/gallery/ICMIC01/Days/8.jpg" data-fancybox="gallery" data-caption="ICMIC01"><img src="images/gallery/ICMIC01/Days/8.jpg">	</a>
 <a href="images/gallery/ICMIC01/Days/9.jpg" data-fancybox="gallery" data-caption="ICMIC01"><img src="images/gallery/ICMIC01/Days/9.jpg">	</a>
 
 
                                        </div>

                                       
                                    </div>
        

    </div>

    

</section>
  </main>

  <!-- =====================================================
       FOOTER
       ===================================================== -->
@endsection

@push('scripts')
<script src="https://unpkg.com/flipping@latest/dist/flipping.web.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
<script>
  Fancybox.bind("[data-fancybox='gallery']", {
    on: {
      init: () => document.body.classList.add("icmic-lightbox-open"),
      destroy: () => document.body.classList.remove("icmic-lightbox-open")
    },
    Thumbs: false,
    Toolbar: {
      display: [
        "close",
        "zoom",
        "fullscreen",
        "slideshow",
        "download"
      ]
    }
  });
   Fancybox.bind("[data-fancybox='Execursion']", {
    Thumbs: false,
    Toolbar: {
      display: [
        "close",
        "zoom",
        "fullscreen",
        "slideshow",
        "download"
      ]
    }
  });
</script>
@endpush
