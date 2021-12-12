<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b></b>
  </div>
  <strong>Developed By - <a href="http://samyotech.com">Samyotech</a></strong>
</footer>

<!-- Control Sidebar -->

<!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="{{ url('assets/admin/js/jquery-2.2.3.min.js') }}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ url('assets/admin/js/bootstrap.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ url('assets/admin/js/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ url('assets/admin/js/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ url('assets/admin/js/app.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ url('assets/admin/js/demo.js') }}"></script>

<!---Date time picker -->
<script type="text/javascript" src="{{ url('assets/admin/js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ url('assets/admin/js/bootstrap-datetimepicker.fr.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ url('assets/admin/js/jquery-confirm.min.js') }}" charset="UTF-8"></script>
{{-- <script type="text/javascript" src="{{ url('assets/admin/js/jquery-ui.js') }}" charset="UTF-8"></script> --}}
{{-- <script type="text/javascript" src="{{ url('assets/admin/plugin/datatable/js/jquery.dataTables.min.js') }}" charset="UTF-8"></script> --}}
{{-- <script type="text/javascript" src="{{ url('assets/admin/plugin/datatable/js/dataTables.bootstrap.min.js') }}" charset="UTF-8"></script> --}}
<style>
  .modal.fade .modal-dialog {
 -webkit-transform: translate(0);
 -moz-transform: translate(0);
 transform: translate(0);
 }
  </style>

<script>
  // This just makes all bootstrap native .modals jive together
$('.modal').on("hidden.bs.modal", function (e) {
    if($('.modal:visible').length)
    {
        $('.modal-backdrop').first().css('z-index', parseInt($('.modal:visible').last().css('z-index')) - 10);
        $('body').addClass('modal-open');
    }
}).on("show.bs.modal", function (e) {
    if($('.modal:visible').length)
    {
        $('.modal-backdrop.in').first().css('z-index', parseInt($('.modal:visible').last().css('z-index')) + 10);
        $(this).css('z-index', parseInt($('.modal-backdrop.in').first().css('z-index')) + 10);
    }
});

</script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-43546007-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-43546007-2');
</script>

</body>

</html>