@if(!is_null($user))
<div class="box-body" style="padding: 0; font-size:11px;">
   <div class="col-md-4" style="padding: 0">
<center><img src="{{url($user->avatar_medium)}}" class="img-responsive img-thumbnail" >      
</center>
</div> 

   <div class="col-md-8" style="padding: 1px">
       <div class="table-responsive">
<table class="table-striped table-bordered table  table-condensed">
    <caption class="text-bold" style="color:#fff; text-align:center; background: #09c!important">User Info</caption>
    <tbody>
        <tr>
            <th>Name</th>
            <td>{{$user->name}}</td>
            <th>Profile For</th>
            <td>{{$user->profile_for}}</td>
            </tr>
            <tr>
            <th>Email</th>
            <td>{{$user->email}}</td>
            <th>State</th>
            <td>{{$user->state}}</td>
            </tr>
            <tr>
            <th>Mobile</th>
            <td>{{$user->mobile}}</td>
            <th>District</th>
            <td>{{$user->district}}</td>
            </tr>
            <tr>
            <th>Gender</th>
            <td>{{$user->gender}}</td>
            <th>City</th>
            <td>{{$user->city}}</td>
        </tr>
        <tr>
            <th>Aadhaar</th>
            <td>{{$user->aadhaar}}</td>
            <th>DOB</th>
            <td>{{$user->dob}}</td>
            
        </tr>
        <tr>
            <th>Birth Place</th>
            <td>{{$user->birth_place}}</td>
            <th>Birth Time</th>
            <td>{{$user->birth_time}}</td>
        </tr>
        <tr>
            <th>Height</th>
            <td>{{$user->height}}</td>
            <th>Income</th>
            <td>{{$user->income}}</td>
        </tr>
        <tr>
            <th>Gotra</th>
            <td>{{$user->gotra}}</td>
            <th>Gotra Nanihal</th>
            <td>{{$user->gotra_nanihal}}</td>
        </tr>
        <tr>
            <th>Marital Status</th>
            <td>{{$user->marital_status}}</td>
            <th>Manglik</th>
            <td>{{$user->manglik}}</td>
        </tr>
        @php 
          $role = auth()->user()->role()->first()->name;
        @endphp

        @if($role == 'super_admin')
        <tr>
            
            <th colspan="2"><input type="text" class="form-control" placeholder="New Password" style="border-left:3px solid red;" id="usr_pwd" user-id="{{$user->id}}" ></th>
            <th><center><button class="btn btn-danger" link="{{ url('admin/users/change-password')}}" id="change_pwd">Change</button></center></th>
        <td><span id="change_response_{{$user->id}}"></span></td>
        </tr>
       @endif
    </tbody>
</table>    
</div>

</div> 
<div class="clearfix"></div>
@if($id_proof->count() > 0)
    <div class="col-md-12" style="padding: 1px" >
        <p class="text-bold" style="color:#fff; text-align:center; background: #09c!important;padding:8px;">Identity Proof</p>
    </div>
@foreach($id_proof as $key => $value )
<div class="col-md-4 col-sm-6 col-xs-6" style="padding: 1px">
<img src="{{url($value->getUrl('big'))}}" onclick="viewbig('{{url($value->getUrl('big'))}}')" data-toggle="modal" data-target="#bigimage" class="img-responsive img-thumbnail">
</div>
@endforeach
@endif

<script>
function viewbig(src) {
  $("#img-response").attr("src",src);  
}

</script>

<div class="clearfix"></div>
<div class="col-md-6" style="padding: 1px">
<div class="table-responsive">
    <table class="table-striped table-bordered table  table-condensed">
        <caption class="text-bold" style="color:#fff; text-align:center; background: #09c!important;">Important Details</caption>
        <tbody>
            <tr>
                <th>Education</th>
                <td>{{$user->qualification}}</td>
                <th>Work Place</th>
                <td>{{$user->work_place}}</td>
            </tr>
            <tr>
                <th>Occupation</th>
                <td>{{$user->occupation}}</td>
                <th>Organisation Name</th>
                <td>{{$user->organisation_name}}</td>
            </tr>
           
 
        </tbody>
    </table>
</div>


</div>


<div class="col-md-6" style="padding: 1px">
<div class="table-responsive">
    <table class="table-striped table-bordered table  table-condensed">
        <caption class="text-bold" style="color:#fff; text-align:center; background: #09c!important;">Life Styles</caption>
        <tbody>
            <tr>
                <th>Dietry</th>
                <td>{{$user->dietary}}</td>
                <th>Drinking</th>
                <td>{{$user->drinking}}</td>
            </tr>
            <tr>
                <th>Smoking</th>
                <td>{{$user->smoking}}</td>
                <th>Language</th>
                <td>{{$user->language}}</td>
            </tr>
            <tr>
                <th>Hobbies</th>
                <td>{{$user->hobbies}}</td>
                <th>Interest</th>
                <td>{{$user->interests}}</td>

            </tr>

        </tbody>
    </table>
</div>

</div>
<div class="clearfix"></div>
<div class="col-md-12" style="padding: 1px">

<div class="table-responsive">
    <table class="table-striped table-bordered table  table-condensed">
        <caption class="text-bold" style="color:#fff; text-align:center; background: #09c!important;">Family Details</caption>
        <tbody>
            <tr>
                <th>Grand Father</th>
                <td>{{$user->grand_father_name}}</td>
                <th>Maternal Grand Father</th>
                <td>{{$user->maternal_grand_father_name_address}}</td>
            </tr>
            <tr>
                <th>Father Name</th>
                <td>{{$user->father_name}}</td>
                <th>Mother Name</th>
                <td>{{$user->mother_name}}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td>{{$user->permanent_address}}</td>
                <th>Whatsapp No</th>
                <td>{{$user->whatsapp_no}}</td>
            </tr>
            <tr>
                <th>Mobile</th>
                <td>{{$user->mobile2}}</td>
                <th>Family Pin</th>
                <td>{{$user->family_pin}}</td>
            </tr>
            <tr>
                <th>Father Occupation</th>
                <td>{{$user->father_occupation}}</td>
                <th>Mother Occupation</th>
                <td>{{$user->mother_occupation}}</td>
            </tr>
            <tr>
                <th>Brother</th>
                <td>{{$user->brother}}</td>
                <th>Sister</th>
                <td>{{$user->sister}}</td>
            </tr>
            <tr>
                <th>Family Income</th>
                <td>{{$user->family_income}}</td>
                <th>Family Status</th>
                <td>{{$user->family_status}}</td>
            </tr>
            <tr>
                <th>Family Type</th>
                <td>{{$user->family_type}}</td>
                <th>Family Value</th>
                <td>{{$user->family_value}}</td>
            </tr>
            <tr>
                <th>Family State</th>
                <td>{{$user->family_state}}</td>
                <th>Family District</th>
                <td>{{$user->family_district}}</td>
            </tr>
            <tr>
                <th>Family City</th>
                <td>{{$user->family_city}}</td>
                
            </tr>


        </tbody>
    </table>
</div>

</div>


</div>
@endif