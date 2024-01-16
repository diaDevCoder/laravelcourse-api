<?php
namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseStudents;
use Illuminate\Http\Request;

class CourseController extends Controller {
    public function index(){
        $courses = Course::all();
        return response()->json($courses);
    }

    public function show($id) {
        $course = Course::find($id);        
        if ($course) {     
            return response()->json($course);       
        }else{
            return response()->json(['message' => 'Course not found.'], 404);
        }        
    }

    public function search($query) {        
        $courses = Course::where('title', 'LIKE', "%$query%")->get();

        if($courses) {
            return response()->json([$courses], 200);
        }else{
            return response()->json(['message' => 'Course not found.'], 404);
        }
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'desc' => 'required',
        ]);

        $course = new Course();
        $course->title = $request->title;
        $course->description = $request->desc;
        $course->save();

        return response()->json(['message' => 'Course created.'], 201);
    }

    public function update(Request $request, $id) {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Course not found.'], 404);
        }

        $course->title = is_null($request->title) ? $course->title : $request->title;
        $course->title = is_null($request->title) ? $course->title : $request->title;
        $course->save();
        return response()->json(['message' => 'Course updated.'],201);
    }

    //GET ALL STUDENTS UNDER EACH COURSES
    public function getStudents($id){
        $student = CourseStudents::where('course_id', $id)->get();
        if ($student) {       
            return response()->json($student, 201);
        }else{
            return response()->json(['message' => 'Student not found.'], 404);
        } 
    }
    public function destroy($id) {
        $course = Course::with('courseStudents')->find($id);
        if($course) {
            $course->courseStudents()->delete();
            $course->delete();
            return response()->json(['message' => 'Course deleted.'], 200);
        }else{
            return response()->json(['message' => 'Course not found.'], 404);
        }
    }
}
