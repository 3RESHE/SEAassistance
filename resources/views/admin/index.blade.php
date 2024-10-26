
    @include('admin.css')


    @include('admin.sidebar', ['user' => Auth::user()])

    <!--========== CONTENTS ==========-->

    <main>
        <section>
    <!-- DASHBOARD -->
            @include('admin.stats')
      <!-- DASHBOARD -->
    



            


  


</section>
</main>
@include('admin.footer')

        <!--========== MAIN JS ==========-->
        <script src="admin/main.js"></script>
    </body>
    </html>
    