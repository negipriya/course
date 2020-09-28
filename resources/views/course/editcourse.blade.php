@extends('layouts.default')
@section('title', 'Add Course')
@section('content')
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<div class="sucs-msg">
        <div class="container">
          <div class="verfy_area">
            <div class="row">
                <div class="col-sm-12">
                  <div class="scs-img">
                  </div>
                  @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                    @if (session()->has('error'))
                             <div class="alert alert-danger" role="alert">
                                 {{ session()->get('error') }}
                             </div>
                         @endif
                    <hr />
                  <div class="alert alert-success sucss">
                    <h2>Edit Course</h2>
                  <form enctype="multipart/form-data" method="post" id="addcourse">
                     {{ csrf_field() }}
                     <input type="hidden" name="id" value="{{$getCourse->id}}">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Course Title</label>
                      <input type="text" name="course_title" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Course Title" value="{{$getCourse->title}}">
                      
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Description</label>
                      <textarea class="form-control" name="description" rows="5" id="comment"><?php echo $getCourse->description;?></textarea>
                    </div>

                   <div class="form-group">
                <label for="field-ta" class="col-sm-2 control-label"> Featured Image </label>
                <div class="col-sm-4">
                        <div class="full editp">
                        <label for="name" ></label>
                        <div id="image">
                          @if($getCourse->image =="")
                   <img width="100%" height="100%" id="preview_image" src="{{url('/public/storage/uploads/default.png')}}"/>
                      
                         @else
                         <img width="100%" height="100%" id="preview_image" src="{{asset('storage/uploads/images/')}}/{{$getCourse->image}}"/>
                         @endif
                          <i id="loading" class="fa fa-spinner fa-spin fa-3x fa-fw" style="position: absolute;left: 40%;top: 40%;display: none">
                       </i>
                        </div>
                        <p>
                            <div class="form-group">
                <div class="col-sm-offset-1 col-sm-10">
                  <div class="checkbox">
                    <label>
                            <a href="javascript:uploadcoursefeatured()" style="text-decoration: none;" class="btn btn-success">
                                <i class="glyphicon glyphicon-edit "></i> upload image
                            </a>&nbsp;&nbsp;
                              </div>
                </div>
              </div>
                        </p>
                        <input type="file" id="file" style="display: none"/>
                        <input type="hidden" name="file_name" id="file_name"/>
                        </div>

                    <button type="submit" class="btn btn-primary">Update Course</button>
                  </form>

                  </div>
			        </div>
            </div>
          </div>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>
 <script src="https://cdn.ckeditor.com/4.4.3/basic/adapters/jquery.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
 
  <script>
    
         var k = jQuery.noConflict();
         k(document).ready(function(){
         k('#addcourse').bootstrapValidator({
              excluded: [':disabled'],
    message:'This value is not valid',
        feedbackIcons: {
          valid: 'glyphicon glyphicon-ok',
          invalid: 'glyphicon glyphicon-remove',
          validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
       course_title: {
          validators: {
            notEmpty: {
              message: 'Please Enter Course Title'
            },
          }
        },
         description: {
                    validators: {
                        notEmpty: {
                            message: 'Please Enter Course Description'
                        }
                    }
                }
           }
   })
   .find('[name="description"]')
            .ckeditor()
            .editor
                // To use the 'change' event, use CKEditor 4.2 or later
                .on('change', function() {
                    // Revalidate the bio field
                    k('#addcourse').bootstrapValidator('revalidateField', 'description');
                });
 });

</script>
<script>
  var j = jQuery.noConflict();
    function uploadcoursefeatured(){
        j('#file').click();
    }
     j('#file').change(function () {
         if (j(this).val() != '') {
            upload(this);
      }
    });
    function upload(img) {
        var form_data = new FormData();
        form_data.append('file', img.files[0]);
        form_data.append('_token', '{{csrf_token()}}');
        j('#loading').css('display', 'block');
        j.ajax({
            url: "{{url('ajax-image-upload')}}",
            data: form_data,
            type: 'POST',
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.fail) {
                  j('#preview_image').attr('src', '{{URL::to('/public/storage/uploads/images/default.png')}}');
                    alert(data.errors['file']);
                }
                else {
                    j('#file_name').val(data);
                    j('#preview_image').attr('src', '{{URL::to('/public/storage/uploads/images/')}}/' + data);
                    
                }
                j('#loading').css('display', 'none');
            },
            error: function (xhr, status, error) {
                alert(xhr.responseText);
                j('#preview_image').attr('src', '{{URL::to('/public/storage/uploads/images/default.png')}}');

                
            }
        });
    }
</script>

    
@endsection