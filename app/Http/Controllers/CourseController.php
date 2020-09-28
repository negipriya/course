<?php

namespace App\Http\Controllers;
use App\Course;
use Image;
use \Crypt;
use File;
use Validator, Input, Redirect; 
use Illuminate\Http\Request;

class CourseController extends Controller
{
    //

    public function addCourse(Request $request){
    	$course = Course::all();
    	return view('course.addcourse',['course'=>$course]);
    }

    public function insertCourse(Request $request){
    	$addcoursedata = new Course;
        $file = $request->file_name;
          if($file != ""){
          $addcoursedata->image = $file;
          }
         $addcoursedata->title = $request->course_title;
         $addcoursedata->description = $request->description;
         $savecourse =  $addcoursedata->save();
         if($savecourse){
              return redirect()->back()->with('message', 'Successfully Added Course.');
         }
    }

    public function ajaxImage(Request $request)
    {
     if ($request->isMethod('get'))
          return view('ajax-image-upload');
      else {
          $validator = Validator::make($request->all(),
              [
                  'file' => 'image',
              ],
              [
                  'file.image' => 'The file must be an image (jpeg, png, bmp, gif, or svg)'
              ]);
          if ($validator->fails())
              return array(
                  'fail' => true,
                  'errors' => $validator->errors()
              );
          $extension = $request->file('file')->getClientOriginalExtension();
          $dir = public_path('/storage/uploads/images');
          $filename = uniqid() . '_' . time() . '.' . $extension;
          $request->file('file')->move($dir, $filename);
          return $filename;
      }
    }

    public function editCourse(Request $request){
    	$getCourse = Course::find($request->id);
    	return view('course.editcourse',['getCourse'=>$getCourse]);

    }

    public function update_course(Request $request){
         $courseId = $request->id;
         $findCourse = Course::find($courseId);
         $prev_featured = $findCourse->image;
         if($request->course_title !=""){
         		$findCourse->title = $request->course_title;
         } 
         if($request->description !=""){
         		$findCourse->description = $request->description; 
     	 }
         $checkupload = $request->file_name;
         if($checkupload != ""){
             $findCourse->image = $request->file_name;
               File::delete('public/storage/uploads/images/' . $prev_featured); 
         }else{
             $findCourse->image = $prev_featured;
         }
         $update_course = $findCourse->update();
          if($update_course)
        {
          return redirect()->back()->with('message', 'Course Updated Successfully');
        }
        }
}
