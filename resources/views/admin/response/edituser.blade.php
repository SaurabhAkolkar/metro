<form class="form-horizontal" action="{{url('users/0')}}" id="update-user-form">
  <div class="box-body">
    <div class="form-group">
      <label for="name" class="col-sm-2 control-label">Name</label>

      <div class="col-sm-10">
        {{csrf_field()}}
        <input type="text" class="form-control" name="name" value="{{$user->name}}" placeholder="Name">
        <span class="text-red user-err" id="upd-err-name"></span>
      </div>
    </div>
    <div class="form-group">
      <label for="contact" class="col-sm-2 control-label">Contact</label>

      <div class="col-sm-10">
        <input type="number" class="form-control" name="contact" value="{{$user->mobile}}" placeholder="Contact">
        <span class="text-red user-err" id="upd-err-contact"></span>
      </div>
    </div>
    <div class="form-group">
      <label for="email" class="col-sm-2 control-label">Email</label>

      <div class="col-sm-10">
        <input type="email" class="form-control" name="email" value="{{$user->email}}" placeholder="Email">
        <span class="text-red user-err" id="upd-err-email"></span>
      </div>
    </div>
    <div class="form-group">
      <label for="company" class="col-sm-2 control-label">Company</label>

      <div class="col-sm-10">
        <input type="company" class="form-control" name="company" value="{{$user->company}}" placeholder="Company">
      </div>
    </div>

  </div>

</form>

<div class="modal-footer">
  <success id="success-update" class="pull-left"></success>
  <button type="button" class="btn btn-primary update-user">Save changes</button>
</div>