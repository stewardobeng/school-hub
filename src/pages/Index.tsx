import { Link } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { GraduationCap, Users, BookOpen, Shield } from "lucide-react";

const roles = [
  {
    title: "Admin Dashboard",
    description: "Manage students, teachers, courses, and view analytics",
    icon: Shield,
    path: "/admin",
    color: "bg-blue-100 text-blue-600",
  },
  {
    title: "Student Dashboard",
    description: "View courses, grades, assignments, and schedule",
    icon: GraduationCap,
    path: "/student",
    color: "bg-green-100 text-green-600",
  },
  {
    title: "Teacher Dashboard",
    description: "Manage classes, attendance, and grading",
    icon: Users,
    path: "/teacher",
    color: "bg-purple-100 text-purple-600",
  },
];

export default function Index() {
  return (
    <div className="min-h-screen bg-background flex items-center justify-center p-6">
      <div className="max-w-4xl w-full">
        {/* Header */}
        <div className="text-center mb-12 animate-fade-in">
          <div className="flex items-center justify-center gap-3 mb-4">
            <div className="w-12 h-12 flex items-center justify-center">
              <svg viewBox="0 0 24 24" className="w-12 h-12" fill="none">
                <circle cx="12" cy="8" r="3" fill="hsl(var(--accent))" />
                <circle cx="6" cy="16" r="2.5" fill="hsl(var(--accent))" />
                <circle cx="18" cy="16" r="2.5" fill="hsl(var(--accent))" />
                <path d="M12 11v3M8 14l2 1M16 14l-2 1" stroke="hsl(var(--accent))" strokeWidth="1.5" />
              </svg>
            </div>
            <h1 className="text-4xl font-bold text-foreground">SCHOOL</h1>
          </div>
          <p className="text-lg text-muted-foreground max-w-md mx-auto">
            Complete School Management System
          </p>
        </div>

        {/* Role Selection */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {roles.map((role, index) => (
            <Card 
              key={role.path} 
              className="hover:shadow-lg transition-all duration-300 animate-fade-in cursor-pointer group"
              style={{ animationDelay: `${index * 0.1}s` }}
            >
              <CardHeader className="text-center pb-2">
                <div className={`w-16 h-16 rounded-full ${role.color} flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform`}>
                  <role.icon className="w-8 h-8" />
                </div>
                <CardTitle className="text-xl">{role.title}</CardTitle>
                <CardDescription className="text-sm">{role.description}</CardDescription>
              </CardHeader>
              <CardContent className="pt-2">
                <Link to={role.path}>
                  <Button className="w-full bg-primary hover:bg-primary/90">
                    Enter Dashboard
                  </Button>
                </Link>
              </CardContent>
            </Card>
          ))}
        </div>

        {/* Features */}
        <div className="mt-12 text-center animate-fade-in" style={{ animationDelay: "0.4s" }}>
          <p className="text-sm text-muted-foreground mb-4">Key Features</p>
          <div className="flex flex-wrap justify-center gap-4">
            {["Student Management", "Teacher Management", "Course Management", "Attendance Tracking", "Grade Management", "Payment System"].map((feature) => (
              <span key={feature} className="px-4 py-2 bg-muted rounded-full text-sm text-muted-foreground">
                {feature}
              </span>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
