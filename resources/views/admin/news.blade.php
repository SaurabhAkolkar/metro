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
                <h4 class="modal-title">Add News </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{route('admin.add-news')}}" id="add-news-form" enctype="multipart/form-data">
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
                            <label for="contact" class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10">
                                <textarea name="description" class="form-control" placeholder="description"></textarea>
                                <span class="text-red news-err" id="err-description"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">Image (optional)</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="image" >
                                <span class="text-red news-err" id="err-image"></span>
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
            <span class="pull-right btn btn-xs btn-danger" data-toggle="modal" data-target="#add-news"><i class="fa fa-user" ></i> Add News </span>
            <h3 class="box-title">News</h3>

            {{-- <span class="pull-right btn btn-xs btn-danger" data-toggle="modal" data-target="#add-user"><i class="fa fa-user" ></i> Add user </span>            --}}
        </div>
        <div class="box-body" id="news-div">
            @if(session('news-msg'))
            <div class="alert alert-warning text-bold">{{session('news-msg')}}</div>
            @endif

      @if(sizeof($news)>0)
      @foreach($news as $key => $value)   
      @if($value->image !=="")   
      <div class="col-md-4">
      <img class="img-responsive" src="{{url($value->image)}}">    
      </div> 
      <div class="col-md-8">
          @else
          <div class="col-md-12">
       
       @endif
          <span style="position:absolute; right:0">Date:{{$value->created_at}}
          &nbsp;&nbsp;<a href="#" link="{{url('admin/news-delete/'.$value->id)}}"  class="btn btn-xs btn-danger delete-confirm"><i class="fa fa-trash"></i></a>
         </span>
      <h4>{{$value->heading}} </h4>
       <p>{{$value->description}}</p>    
    
      </div> 
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