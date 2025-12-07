import { Toaster } from "@/components/ui/toaster";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import Index from "./pages/Index";
import NotFound from "./pages/NotFound";
import AdminDashboard from "./pages/admin/AdminDashboard";
import AdminStudents from "./pages/admin/AdminStudents";
import AdminStudentDetail from "./pages/admin/AdminStudentDetail";
import AdminTeachers from "./pages/admin/AdminTeachers";
import AdminTeacherDetail from "./pages/admin/AdminTeacherDetail";
import AdminAttendance from "./pages/admin/AdminAttendance";
import AdminCourses from "./pages/admin/AdminCourses";
import AdminExam from "./pages/admin/AdminExam";
import AdminPayment from "./pages/admin/AdminPayment";
import AdminSettings from "./pages/admin/AdminSettings";
import AdminProfile from "./pages/admin/AdminProfile";
import StudentDashboard from "./pages/student/StudentDashboard";
import TeacherDashboard from "./pages/teacher/TeacherDashboard";

const queryClient = new QueryClient();

const App = () => (
  <QueryClientProvider client={queryClient}>
    <TooltipProvider>
      <Toaster />
      <Sonner />
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Index />} />
          
          {/* Admin Routes */}
          <Route path="/admin" element={<AdminDashboard />} />
          <Route path="/admin/students" element={<AdminStudents />} />
          <Route path="/admin/students/:id" element={<AdminStudentDetail />} />
          <Route path="/admin/teachers" element={<AdminTeachers />} />
          <Route path="/admin/teachers/:id" element={<AdminTeacherDetail />} />
          <Route path="/admin/attendance" element={<AdminAttendance />} />
          <Route path="/admin/courses" element={<AdminCourses />} />
          <Route path="/admin/exam" element={<AdminExam />} />
          <Route path="/admin/payment" element={<AdminPayment />} />
          
          {/* Student Routes */}
          <Route path="/student" element={<StudentDashboard />} />
          <Route path="/student/courses" element={<StudentDashboard />} />
          <Route path="/student/grades" element={<StudentDashboard />} />
          <Route path="/student/schedule" element={<StudentDashboard />} />
          <Route path="/student/assignments" element={<StudentDashboard />} />
          
          {/* Teacher Routes */}
          <Route path="/teacher" element={<TeacherDashboard />} />
          <Route path="/teacher/classes" element={<TeacherDashboard />} />
          <Route path="/teacher/students" element={<TeacherDashboard />} />
          <Route path="/teacher/attendance" element={<TeacherDashboard />} />
          <Route path="/teacher/grading" element={<TeacherDashboard />} />
          
          {/* Settings & Profile */}
          <Route path="/settings" element={<AdminSettings />} />
          <Route path="/admin/profile" element={<AdminProfile />} />
          
          {/* ADD ALL CUSTOM ROUTES ABOVE THE CATCH-ALL "*" ROUTE */}
          <Route path="*" element={<NotFound />} />
        </Routes>
      </BrowserRouter>
    </TooltipProvider>
  </QueryClientProvider>
);

export default App;
