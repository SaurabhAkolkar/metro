@include('admin.header')
<div class="content-wrapper">
<input type="hidden" id="set_status_url" value="{{url('admin/users/set_status')}}">
    <!-- Content Header (Page header) -->
    {{--Edit user Modal--}}
    <div class="modal fade" id="edit-user">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-custom">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit user</h4>
                </div>
                <div class="modal-body edit-user-response">

                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->



    {{--Send user Notification--}}
    <div class="modal fade" id="user-notify">
        <div class="modal-dialog zoomInDown animated">
            <div class="modal-content">
                <div class="modal-header bg-custom">
                    <button type="button" class="btn btn-danger btn-xs pull-right" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">close</span></button>
                    <h4 class="modal-title">User Notify</h4>
                </div>
                <div class="modal-body">
                 <form class="form-horizontal" action="{{url('admin/user-notify')}}" id="user-notify-form">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-10">
                                {{csrf_field()}}
                                <input type="text" class="form-control" name="title" placeholder="Name">
                                <span class="text-red" id="err-notify-title"></span>
                                <span class="text-red" id="err-notify-user_id"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="description" placeholder="Description"></textarea>
                                <span class="text-red" id="err-notify-description"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-8" id="user-notify-response">

                            </div>
                            <div class="col-sm-4">
                                <a  class="btn btn-success btn-sm pull-right user-notify-send">Send</a>
                            </div>
                        </div>
                        </div>
                 </form>
                </div>
    
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    {{--View user Modal--}}
    <div class="modal fade" id="view-user">
        <div class="modal-dialog modal-lg zoomInDown animated">
            <div class="modal-content">
                <div class="modal-header bg-custom">
                    <button type="button" class="btn btn-danger btn-xs pull-right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">close</span></button>
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

{{-- Image modal --}}
<div class="modal fade" id="bigimage">
    <div class="modal-dialog zoomInDown animated" style="width:fit-content;">
        <div class="modal-content" style="background: ;">
            
                
            
            <center>
                <div>
                <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 0;">
                                          <span aria-hidden="true">close</span></button>
                <img  id="img-response" class="img-responsive"/>
            </div>
            </center>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->



    {{--Add user Modal--}}
    <div class="modal fade" id="add-user">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-custom">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add user</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{url('users')}}" id="add-user-form">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10">
                                    {{csrf_field()}}
                                    <input type="text" class="form-control" name="name" placeholder="Name">
                                    <span class="text-red user-err" id="err-name"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="contact" class="col-sm-2 control-label">Contact</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="contact" placeholder="Contact">
                                    <span class="text-red user-err" id="err-contact"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" name="email" placeholder="Email">
                                    <span class="text-red user-err" id="err-email"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="company" class="col-sm-2 control-label">Company</label>
                                <div class="col-sm-10">
                                    <input type="company" class="form-control" name="company" placeholder="Company">
                                </div>
                            </div>

                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <success id="success-add" class="pull-left"></success>
                    <button type="button" class="btn btn-primary add-user">Add</button>
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
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Users List </h3>

                        {{-- <span class="pull-right btn btn-xs btn-danger" data-toggle="modal" data-target="#add-user"><i class="fa fa-user" ></i> Add user </span> --}}
                    </div>
                    <div class="box-body">

                        <div class="col-sm-12" style="padding: 0;">
                            <div class="col-sm-12" style="padding: 0;">
                                <form action="{{route('users_search_result')}}
                                " class="form" method="get" accept-charset="utf-8">
                                    <div class="col-sm-4" style="padding: 0;">
                                        <div class="form-group">
                                            <input class="form-control" name="user_search" value="{{session('string')}}" id="user_search" placeholder="User Search">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary" value="Search">

                                            <a class="btn btn-default" href="{{route('users.index')}}">Reset</a>

                                        </div>
                                    </div>
                                </form>
<form action="{{route('set-gender')}}
                                " class="form" method="post" id="set-gender" accept-charset="utf-8">
            <div class="col-sm-2" style="padding: 0;">
            <div class="form-group">
                @csrf
                <select name="gender"  onChange="event.preventDefault(); document.getElementById('set-gender').submit();"  class="form-control">
                <option value="">All</option>
                <option <?php
                if(session()->get('gender') == "M") echo 'selected'; ?> value="M">Male</option>
                <option <?php if(session()->get('gender') == "F") echo 'selected'; ?> value="F">Female</option>
            </select>
            </div>
        </div>
</form>

                            </div>
                            <div class="col-sm-12" style="padding: 0;">
                                @if(session('user_msg'))
                                <div class="alert alert-warning text-bold">{{session('user_msg')}}<button type="button" class="close pull-right" data-dismiss="alert"
                                        aria-hidden="true">×</button></div>
                                @endif
                                <div class="table-responsive" id="user-table">
                                    <table class="table table-striped table-bordered table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Sno</th>
                                                <th>Name</th>
                                                <th>Avatar</th>
                                                <th>Contact</th>
                                                <th>Action</th>
                                                <th>Joined</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i = ($users->currentpage() - 1) * $users->perpage() + 1;
                                            
                                            $role = auth()->user()->role()->first()->name; 
                                      
                                            @endphp 
                                            @foreach($users as $user) 

        @php
            $x = $user->toArray(); $remaining_fields = $total_fields = 0;
            // wasRecentlyCreated 
            foreach ($x as $key => $value) { if($value === "" || is_null($value)){ 
                // dd($key); 
                $remaining_fields++;
             } 
             $total_fields++; 
            }
             $user->profile_completion = (string)(100 - round(($remaining_fields/$total_fields)*100));
        @endphp
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td><sup><span class="label btn-primary">{{$user->profile_completion}} %</span></sup>{{$user->name}}</td>
                                                <td>
                                                
                                                       
                                                    <img src="{{url($user->avatar_thumb)}}" class="img-responsive img-circle"
                                                style="width:30px; height:30px;" />
                                                 
                                            </td>
                                                <td>{{$user->mobile}}</td>
                                                
                                                
                                                <td>
    <a class="btn btn-xs bg-custom view-user" data-toggle="modal" user-id="{{ $user->id }}" data-target="#view-user"><i class="fa fa-eye"  title="View Details"></i></a>
    @if($user->is_active == "3" || $user->is_active == "0" )
    <span class="set_status"  title="Enable/ Disable" user-id="{{ $user->id }}"><a class="btn btn-xs btn-danger" ><i class="fa fa-ban"></i></a></span>
    @else
    <span class="set_status"  title="Enable/ Disable" user-id="{{ $user->id }}"><a class="btn btn-xs btn-success" ><i class="fa fa-ban"></i></a></span>
    @endif
                                    
                                                </td>
                                                <td>
                                                  <span class="badge bg-custom"> {{date('d F, Y h:i:s A', strtotime($user->created_at)) }}</span>  
                                                </td>
                                            </tr>
                                            @endforeach


                                        </tbody>
                                    </table>
                                    
                                </div>
                                <div>{{ $users->appends(['user_search' => session('string'), 'gender'=> session('gender')  ])->links() }}</div>
                                <div>Records {{ $users->firstItem() }} - {{ $users->lastItem() }} (for page {{ $users->currentPage() }} )</div>
                            </div>
                        </div>
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