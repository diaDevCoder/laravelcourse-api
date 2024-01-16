<?php

namespace App\Http\Controllers;

use App\Models\CourseStudents;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index() {
       $students = Student::all();
       return response()->json($students);
    }

    public function store(Request $request) {
        try{
            $request->validate([
                'firstName' => 'required',
                'lastName' => 'required',
                'email' => 'required|email|unique:students,email',
                'dob' => 'required|date',
            ]);

            $student = new Student();
            $student->first_name = $request->firstName;
            $student->last_name = $request->lastName;
            $student->email = $request->email;
            $student->birthdate = $request->dob;
            $student->save();

            return response()->json([
                'message' => 'Student created.'
            ], 201);
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id) {
        $student = Student::find($id);
        if($student) {
            return response()->json($student);
        }else{
            return response()->json([
                'message' => 'Student not found.'
            ], 404);
        }
    }

    public function search($query) {        
        $students = Student::with('courses')->where('first_name', 'LIKE', "%$query%")
                           ->orWhere('last_name', 'LIKE', "%$query%")
                           ->get();

        if($students) {
            return response()->json([$students], 200);
        }else{
            return response()->json(['message' => 'Student not found.'], 404);
        }
    }

    public function update(Request $request, $id) {
        $student = Student::find($id);
        if ($student) {
            try{
                $request->validate([
                    'email' => 'email|unique:students,email'
                ]);

                //UPDATE THE DATA
                $student->first_name = is_null($request->firstName) ? $student->first_name : $request->firstName;
                $student->last_name = is_null($request->lastName) ? $student->last_name : $request->lastName;
                $student->email = is_null($request->email) ? $student->email : $request->email;
                $student->birthdate = is_null($request->dob) ? $student->birthdate : $request->dob;
                $student->save();

                //SHOW MESSAGE AFTER SUCCESS
                return response()->json([
                    'message' => 'Student Updated.'
                ], 201);

            }catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 500);
            }            
        }else{
            return response()->json(['message' => 'Student not found.'], 404);
        }
    }

    //GET ALL COURSES UNDER EACH STUDENTS
    public function getCourses($id){
        $student = Student::with('courses')->find($id);
        if ($student) {
            $courses = $student->courses;        
            return response()->json($courses, 201);
        }else{
            return response()->json(['message' => 'Student not found.'], 404);
        } 
    }

    public function createCourses($sid, $cid) {
        $courseStudent =  new CourseStudents();
        $courseStudent->course_id = $cid;
        $courseStudent->student_id = $sid;
        $courseStudent->save();
        return response()->json(['message' => 'Student course created.'], 201);
    }

    public function destroy($id) {
        $student = Student::with('courses')->find($id);
        if($student) {
            $student->courses()->delete();
            $student->delete();
            return response()->json(['message' => 'Student deleted.'], 200);            
        }else{
            return response()->json(['message' => 'Student not found.'], 404);
        }
    }


}
