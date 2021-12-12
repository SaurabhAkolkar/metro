@include('admin.header')
<style>
    .middle {
        transition: .5s ease;
        opacity: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        text-align: center;
    }

    .card:hover .middle {
        opacity: 1;
    }
</style>
<div class="content-wrapper">
<input type="hidden" id="set_request_update_url" value="{{url('admin/users/set_request_status')}}">

{{--View user Modal--}}
<div class="modal fade" id="view-user">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-custom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">User Details</h4>
            </div>
            <div class="modal-body view-user-response">

            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


  <!-- Main content -->
  <section class="content">
    <!-- Default box -->
   <div class="row">
        
<div class="col-md-12 col-xs-12">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Requests</h3>

            {{-- <span class="pull-right btn btn-xs btn-danger" data-toggle="modal" data-target="#add-user"><i class="fa fa-user" ></i> Add user </span>            --}}
        </div>
        <div class="box-body">
      @if(sizeof($requests)>0)
      @foreach($user as $key => $value) 
      <div style="box-shadow: 1px 1px 12px #000; padding: 5px; margin:0; border:1px;" class="col-md-12 col-xs-12" >     
        <div class="col-md-3 col-xs-4">
            <div class="card" style=" box-shadow: 1px 1px 12px #000; padding: 5px;">
                <img class="card-img-top img-responsive" src="{{ url($value[0]->avatar_medium) }}" alt="Card image" style="width:100%">
                <div class="middle">
                    <a class="btn btn-xs bg-purple view-user" data-toggle="modal" user-id="{{ $value[0]->id }} " data-target="#view-user">See Profile</i></a>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{$value[0]->name}}</h5>
                    <p class="card-text"><span class="badge">Age:{{date_diff(date_create($value[0]->dob), date_create(date("Y-m-d")))->format('%y')}}</span> &nbsp;&nbsp; City : {{$value[0]->city}}</p>
                    
                </div>
            </div>
        </div>
        
       <div class="col-md-6 col-xs-4">
           <br>
           <center id="maa{{$interest_id[$key]}}" class="maa" data-iid="{{$interest_id[$key]}}"><button class="btn btn-success btn-xs" >Mark as Accepted</button>
           <br><br><br>
           <img class="img-responsive" style="vertical-align: middle;" src="{{ url('assets/images/right-arrow.png') }}" alt="Card image"></div></center>
       <div class="col-md-3 col-xs-4">

          <div class="card" style=" box-shadow: 1px 1px 12px #000; padding: 5px;">
            <img class="card-img-top" src="{{ url($value[1]->avatar_medium) }}" alt="Card image" style="width:100%">
            <div class="middle">
                <a class="btn btn-xs bg-purple view-user" data-toggle="modal" user-id="{{ $value[1]->id }} " data-target="#view-user">See Profile</i></a>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{$value[1]->name}}</h5>
            <p class="card-text"><span class="badge">Age:{{date_diff(date_create($value[1]->dob), date_create(date("Y-m-d")))->format('%y')}}</span> &nbsp;&nbsp; City : {{$value[1]->city}}</p>
            
            </div>
        </div>


       </div>
       </div>
       <div class="clearfix"></div>
       <br>
       @endforeach
      @endif
      <div>{{ $requests->links() }}</div> 
        </div>
        </div>
        </div>

      </div>
    


</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

@include('admin.footer')
<script type="text/javascript" src="{{url('assets/admin/js/users.js')}}"></script>