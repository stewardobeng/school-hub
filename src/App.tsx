import { Toaster } from "@/components/ui/toaster";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import Index from "./pages/Index";
import NotFound from "./pages/NotFound";
import AdminDashboard from "./pages/admin/AdminDashboard";
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
          <Route path="/admin/students" element={<AdminDashboard />} />
          <Route path="/admin/teachers" element={<AdminDashboard />} />
          <Route path="/admin/attendance" element={<AdminDashboard />} />
          <Route path="/admin/courses" element={<AdminDashboard />} />
          <Route path="/admin/exam" element={<AdminDashboard />} />
          <Route path="/admin/payment" element={<AdminDashboard />} />
          
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
          
          {/* Settings */}
          <Route path="/settings" element={<Index />} />
          
          {/* ADD ALL CUSTOM ROUTES ABOVE THE CATCH-ALL "*" ROUTE */}
          <Route path="*" element={<NotFound />} />
        </Routes>
      </BrowserRouter>
    </TooltipProvider>
  </QueryClientProvider>
);

export default App;
