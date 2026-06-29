 <?php if ($role == 'A'): ?>
   <div class="row">
     <div class="col-lg-3 col-6 ">
       <div class="small-box text-bg-primary "> <!--resizable-card-->
         <div class="inner">
           <h3>150</h3>
           <p>New Orders</p>
         </div>
         <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
           aria-hidden="true">
           <path
             d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z">
           </path>
         </svg>
         <a href="#"
           class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover redirect-dash">
           Plus d'informations <i class="bi bi-link-45deg"></i>
         </a>
       </div>
     </div>
     <div class="col-lg-3 col-6 ">
       <div class="small-box text-bg-success"> <!--resizable-card-->
         <div class="inner">
           <h3>53<sup class="fs-5">%</sup></h3>

           <p>Bounce Rate</p>
         </div>
         <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
           aria-hidden="true">
           <path
             d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z">
           </path>
         </svg>
         <a href="#"
           class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover redirect-dash">
           Plus d'informations <i class="bi bi-link-45deg"></i>
         </a>
       </div>
     </div>
     <?php if ($role == 'A'): ?>
       <div class="col-lg-3 col-6 " id="statsUsers">
         <div class="small-box text-bg-warning"> <!--resizable-card-->
           <div class="inner">
             <h3 id="nbr_all" style="color:#fff !important">-</h3>

             <p style="color:#fff !important">Utilisateurs</p>
           </div>
           <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="small-box-icon" viewBox="0 0 16 16" aria-hidden="true">
             <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
           </svg>
           <a href="Utilisateurs.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover redirect-dash" style="color:#fff !important">
             Plus d'informations <i class="bi bi-link-45deg"></i>
           </a>
         </div>
       </div>
     <?php endif; ?>
     <?php if ($role == 'A'): ?>
       <div class="col-lg-3 col-6">
         <div class="small-box text-bg-danger"><!--resizable-card-->
           <div class="inner">
             <h3>
               <?= $size . " MB"; ?>
             </h3>
             <p>Utilisation de la BD</p>
           </div>
           <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
             aria-hidden="true">
             <path clip-rule="evenodd" fill-rule="evenodd"
               d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z">
             </path>
             <path clip-rule="evenodd" fill-rule="evenodd"
               d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z">
             </path>
           </svg>
           <a href="Parametres.php#panel-DB"
             class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover redirect-dash">
             Plus d'informations <i class="bi bi-link-45deg"></i>
           </a>
         </div>
       </div>
     <?php endif; ?>
   </div>
 <?php endif; ?>