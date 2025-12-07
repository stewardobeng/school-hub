import { DashboardLayout } from "@/components/layout/DashboardLayout";
import { StatCard } from "@/components/dashboard/StatCard";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Progress } from "@/components/ui/progress";
import { BookOpen, CheckCircle, Clock, Trophy, Calendar, FileText } from "lucide-react";

const courses = [
  { name: "Mathematics", progress: 85, grade: "A", nextClass: "Mon 9:00 AM" },
  { name: "Physics", progress: 72, grade: "B+", nextClass: "Tue 11:00 AM" },
  { name: "English Literature", progress: 90, grade: "A+", nextClass: "Wed 10:00 AM" },
  { name: "Chemistry", progress: 68, grade: "B", nextClass: "Thu 2:00 PM" },
];

const assignments = [
  { title: "Algebra Problem Set", course: "Mathematics", due: "Dec 10", status: "pending" },
  { title: "Lab Report #5", course: "Physics", due: "Dec 12", status: "pending" },
  { title: "Essay: Shakespeare", course: "English Literature", due: "Dec 15", status: "submitted" },
];

const schedule = [
  { time: "9:00 AM", subject: "Mathematics", room: "Room 101", teacher: "Mr. Johnson" },
  { time: "11:00 AM", subject: "Physics", room: "Lab 3", teacher: "Dr. Smith" },
  { time: "2:00 PM", subject: "English", room: "Room 205", teacher: "Ms. Davis" },
];

export default function StudentDashboard() {
  return (
    <DashboardLayout title="Student Dashboard" userRole="student">
      {/* Stats Row */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <StatCard
          icon={<BookOpen className="w-6 h-6 text-blue-600" />}
          iconBgColor="bg-blue-100"
          label="Enrolled Courses"
          value="6"
        />
        <StatCard
          icon={<CheckCircle className="w-6 h-6 text-green-600" />}
          iconBgColor="bg-green-100"
          label="Completed"
          value="24"
        />
        <StatCard
          icon={<Clock className="w-6 h-6 text-amber-600" />}
          iconBgColor="bg-amber-100"
          label="Pending Tasks"
          value="5"
        />
        <StatCard
          icon={<Trophy className="w-6 h-6 text-purple-600" />}
          iconBgColor="bg-purple-100"
          label="GPA"
          value="3.8"
        />
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Courses Progress */}
        <div className="lg:col-span-2">
          <Card className="animate-fade-in">
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <BookOpen className="w-5 h-5 text-accent" />
                My Courses
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div className="space-y-4">
                {courses.map((course, index) => (
                  <div key={index} className="p-4 bg-muted rounded-lg">
                    <div className="flex items-center justify-between mb-2">
                      <h4 className="font-medium text-foreground">{course.name}</h4>
                      <span className="text-sm font-semibold text-accent">{course.grade}</span>
                    </div>
                    <div className="flex items-center gap-4">
                      <Progress value={course.progress} className="flex-1" />
                      <span className="text-sm text-muted-foreground">{course.progress}%</span>
                    </div>
                    <p className="text-xs text-muted-foreground mt-2">
                      Next class: {course.nextClass}
                    </p>
                  </div>
                ))}
              </div>
            </CardContent>
          </Card>
        </div>

        {/* Today's Schedule */}
        <div>
          <Card className="animate-fade-in" style={{ animationDelay: "0.1s" }}>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <Calendar className="w-5 h-5 text-accent" />
                Today's Schedule
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div className="space-y-4">
                {schedule.map((item, index) => (
                  <div key={index} className="flex gap-4 p-3 bg-muted rounded-lg">
                    <div className="text-center">
                      <p className="text-sm font-semibold text-accent">{item.time}</p>
                    </div>
                    <div className="flex-1">
                      <h4 className="font-medium text-foreground">{item.subject}</h4>
                      <p className="text-xs text-muted-foreground">{item.room} • {item.teacher}</p>
                    </div>
                  </div>
                ))}
              </div>
            </CardContent>
          </Card>
        </div>
      </div>

      {/* Assignments */}
      <Card className="mt-6 animate-fade-in" style={{ animationDelay: "0.2s" }}>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <FileText className="w-5 h-5 text-accent" />
            Upcoming Assignments
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            {assignments.map((assignment, index) => (
              <div 
                key={index} 
                className={`p-4 rounded-lg border ${
                  assignment.status === "submitted" 
                    ? "bg-green-50 border-green-200" 
                    : "bg-card border-border"
                }`}
              >
                <h4 className="font-medium text-foreground">{assignment.title}</h4>
                <p className="text-sm text-muted-foreground">{assignment.course}</p>
                <div className="flex items-center justify-between mt-3">
                  <span className="text-xs text-muted-foreground">Due: {assignment.due}</span>
                  <span className={`text-xs font-medium px-2 py-1 rounded ${
                    assignment.status === "submitted"
                      ? "bg-green-100 text-green-700"
                      : "bg-amber-100 text-amber-700"
                  }`}>
                    {assignment.status === "submitted" ? "Submitted" : "Pending"}
                  </span>
                </div>
              </div>
            ))}
          </div>
        </CardContent>
      </Card>
    </DashboardLayout>
  );
}
