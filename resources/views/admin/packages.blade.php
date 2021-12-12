@include('admin.header')

<div class="content-wrapper">
<!-- Main content -->


{{--Add user Modal--}}
<div class="modal fade" id="add-news">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-custom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Packages </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{route('admin.add-packages')}}" id="add-news-form" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-10">
                                @csrf
                                <input type="text" class="form-control" name="title" placeholder="Title">
                                <span class="text-red news-err" id="err-title"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Price</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="price" placeholder="Price" required="">
                                <span class="text-red news-err" id="err-title"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="contact" class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10">
                                <textarea name="description" class="form-control" placeholder="description"></textarea>
                                <span class="text-red news-err" id="err-description"></span>
                            </div>
                        </div>
                        <div class="form-group">
                          <label for="contact" class="col-sm-2 control-label">Type</label>
                          <div class="col-sm-10">
                              <select class="selectpicker form-control" name="type" data-style="form-control" tabindex="-98" required="">
                                  <option value="0">Free</option>
                                 <option value="1">Monthly</option>
                                 <option value="2">Quarterly</option>
                                 <option value="3">Halfyearly</option>
                                 <option value="4">Yearly</option>
                              </select>
                          </div>
                      </div>

                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <success id="success-add" class="pull-left"></success>
                <button type="button" class="btn btn-primary add-news">Add</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->



  <section class="content">
    <!-- Default box -->
   <div class="row">
        
<div class="col-md-12 col-xs-12">
    <div class="box">
        <div class="box-header with-border">
            <span class="pull-right btn btn-xs btn-danger" data-toggle="modal" data-target="#add-news"><i class="fa fa-user" ></i> Add Packages </span>
            <h3 class="box-title">Packages</h3>

            {{-- <span class="pull-right btn btn-xs btn-danger" data-toggle="modal" data-target="#add-user"><i class="fa fa-user" ></i> Add user </span>            --}}
        </div>
        <div class="box-body" id="news-div">
            @if(session('packages-msg'))
            <div class="alert alert-warning text-bold">{{session('packages-msg')}}</div>
            @endif

      @if(sizeof($packages)>0)
      @foreach($packages as $key => $value)   
       <div class="col-md-2"><h4>Type</h4>
      <?php if($value->subscription_type==0)
        {
            echo "Free";
        }
        elseif($value->subscription_type==1)
        {
            echo "Monthly";
        }
        elseif($value->subscription_type==2)
        {
            echo "Quarterly";
        }
        elseif($value->subscription_type==3)
        {
            echo "Half Yearly";
        }
        elseif($value->subscription_type==4)
        {
            echo "Yearly";
        } ?>
        </div>
        <div class="col-md-2">
      <h4>Title </h4>
       <p>{{$value->title}}</p>    
      </div> 
      <div class="col-md-3">
      <h4>Price </h4>
       <p>{{$value->price}}</p>  
      </div>
      <div class="col-md-3">
      <h4>Description </h4>
       <p>{{$value->description}}</p>    
      </div> 
      <div class="col-md-2"><span style="position:absolute; right:0">
          &nbsp;&nbsp;<a href="#" link="{{url('admin/packages-delete/'.$value->id)}}"  class="btn btn-xs btn-danger delete-confirm"><i class="fa fa-trash"></i></a></div>
      <div class="clearfix"></div> 
      <hr>
       @endforeach
      @endif

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