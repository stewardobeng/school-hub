import { NavLink, useLocation } from "react-router-dom";
import {
  LayoutDashboard,
  Users,
  GraduationCap,
  ClipboardCheck,
  BookOpen,
  FileText,
  CreditCard,
  Settings,
  LogOut,
} from "lucide-react";
import { cn } from "@/lib/utils";

interface SidebarProps {
  userRole: "admin" | "student" | "teacher";
}

const adminLinks = [
  { to: "/admin", icon: LayoutDashboard, label: "Dashboard" },
  { to: "/admin/students", icon: Users, label: "Students" },
  { to: "/admin/teachers", icon: GraduationCap, label: "Teachers" },
  { to: "/admin/attendance", icon: ClipboardCheck, label: "Attendance" },
  { to: "/admin/courses", icon: BookOpen, label: "Courses" },
  { to: "/admin/exam", icon: FileText, label: "Exam" },
  { to: "/admin/payment", icon: CreditCard, label: "Payment" },
];

const studentLinks = [
  { to: "/student", icon: LayoutDashboard, label: "Dashboard" },
  { to: "/student/courses", icon: BookOpen, label: "My Courses" },
  { to: "/student/grades", icon: FileText, label: "Grades" },
  { to: "/student/schedule", icon: ClipboardCheck, label: "Schedule" },
  { to: "/student/assignments", icon: FileText, label: "Assignments" },
];

const teacherLinks = [
  { to: "/teacher", icon: LayoutDashboard, label: "Dashboard" },
  { to: "/teacher/classes", icon: BookOpen, label: "My Classes" },
  { to: "/teacher/students", icon: Users, label: "Students" },
  { to: "/teacher/attendance", icon: ClipboardCheck, label: "Attendance" },
  { to: "/teacher/grading", icon: FileText, label: "Grading" },
];

export function Sidebar({ userRole }: SidebarProps) {
  const location = useLocation();
  
  const links = userRole === "admin" 
    ? adminLinks 
    : userRole === "student" 
    ? studentLinks 
    : teacherLinks;

  return (
    <aside className="fixed left-0 top-0 z-40 h-screen w-60 bg-sidebar flex flex-col">
      {/* Logo */}
      <div className="flex items-center gap-2 px-6 py-6">
        <div className="flex items-center justify-center w-8 h-8">
          <svg viewBox="0 0 24 24" className="w-8 h-8" fill="none">
            <circle cx="12" cy="8" r="3" fill="hsl(var(--gold))" />
            <circle cx="6" cy="16" r="2.5" fill="hsl(var(--gold))" />
            <circle cx="18" cy="16" r="2.5" fill="hsl(var(--gold))" />
            <path d="M12 11v3M8 14l2 1M16 14l-2 1" stroke="hsl(var(--gold))" strokeWidth="1.5" />
          </svg>
        </div>
        <span className="text-xl font-bold text-primary-foreground">SCHOOL</span>
      </div>

      {/* Navigation */}
      <nav className="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        {links.map((link) => {
          const isActive = location.pathname === link.to;
          return (
            <NavLink
              key={link.to}
              to={link.to}
              className={cn(
                "sidebar-link",
                isActive && "active"
              )}
            >
              <link.icon className="w-5 h-5" />
              <span>{link.label}</span>
            </NavLink>
          );
        })}
      </nav>

      {/* Bottom Actions */}
      <div className="px-3 py-4 border-t border-sidebar-border space-y-1">
        <NavLink to="/settings" className="sidebar-link">
          <Settings className="w-5 h-5" />
          <span>Settings</span>
        </NavLink>
        <NavLink to="/" className="sidebar-link">
          <LogOut className="w-5 h-5" />
          <span>Logout</span>
        </NavLink>
      </div>
    </aside>
  );
}
