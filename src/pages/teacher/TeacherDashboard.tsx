import { DashboardLayout } from "@/components/layout/DashboardLayout";
import { StatCard } from "@/components/dashboard/StatCard";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Button } from "@/components/ui/button";
import { Users, BookOpen, ClipboardCheck, Calendar, Clock, CheckCircle, AlertCircle } from "lucide-react";
import { PieChart, Pie, Cell, ResponsiveContainer } from "recharts";

const classes = [
  { name: "Mathematics 101", grade: "6th Grade", students: 32, time: "Mon, Wed, Fri 9:00 AM" },
  { name: "Advanced Algebra", grade: "8th Grade", students: 28, time: "Tue, Thu 11:00 AM" },
  { name: "Geometry Basics", grade: "7th Grade", students: 30, time: "Mon, Wed 2:00 PM" },
];

const recentSubmissions = [
  { student: "Emma Wilson", assignment: "Homework #5", time: "2 hours ago", status: "pending" },
  { student: "James Brown", assignment: "Quiz #3", time: "5 hours ago", status: "graded" },
  { student: "Sophia Lee", assignment: "Project Report", time: "1 day ago", status: "pending" },
];

const attendanceData = [
  { name: "Present", value: 28, color: "hsl(142, 76%, 36%)" },
  { name: "Absent", value: 2, color: "hsl(0, 84%, 60%)" },
  { name: "Late", value: 2, color: "hsl(38, 92%, 50%)" },
];

const upcomingClasses = [
  { time: "9:00 AM", class: "Mathematics 101", room: "Room 101", students: 32 },
  { time: "11:00 AM", class: "Advanced Algebra", room: "Room 203", students: 28 },
  { time: "2:00 PM", class: "Geometry Basics", room: "Room 105", students: 30 },
];

export default function TeacherDashboard() {
  return (
    <DashboardLayout title="Teacher Dashboard" userRole="teacher">
      {/* Stats Row */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <StatCard
          icon={<BookOpen className="w-6 h-6 text-blue-600" />}
          iconBgColor="bg-blue-100"
          label="My Classes"
          value="5"
        />
        <StatCard
          icon={<Users className="w-6 h-6 text-purple-600" />}
          iconBgColor="bg-purple-100"
          label="Total Students"
          value="156"
        />
        <StatCard
          icon={<ClipboardCheck className="w-6 h-6 text-green-600" />}
          iconBgColor="bg-green-100"
          label="Attendance Rate"
          value="94%"
        />
        <StatCard
          icon={<Calendar className="w-6 h-6 text-amber-600" />}
          iconBgColor="bg-amber-100"
          label="Classes Today"
          value="3"
        />
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* My Classes */}
        <div className="lg:col-span-2">
          <Card className="animate-fade-in">
            <CardHeader className="flex flex-row items-center justify-between">
              <CardTitle className="flex items-center gap-2">
                <BookOpen className="w-5 h-5 text-accent" />
                My Classes
              </CardTitle>
              <Button variant="outline" size="sm">View All</Button>
            </CardHeader>
            <CardContent>
              <div className="space-y-4">
                {classes.map((classItem, index) => (
                  <div key={index} className="flex items-center justify-between p-4 bg-muted rounded-lg">
                    <div className="flex items-center gap-4">
                      <div className="w-12 h-12 rounded-lg bg-accent/20 flex items-center justify-center">
                        <BookOpen className="w-6 h-6 text-accent" />
                      </div>
                      <div>
                        <h4 className="font-medium text-foreground">{classItem.name}</h4>
                        <p className="text-sm text-muted-foreground">{classItem.grade}</p>
                      </div>
                    </div>
                    <div className="text-right">
                      <p className="text-sm font-medium text-foreground">{classItem.students} students</p>
                      <p className="text-xs text-muted-foreground">{classItem.time}</p>
                    </div>
                  </div>
                ))}
              </div>
            </CardContent>
          </Card>
        </div>

        {/* Today's Attendance */}
        <div>
          <Card className="animate-fade-in" style={{ animationDelay: "0.1s" }}>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <ClipboardCheck className="w-5 h-5 text-accent" />
                Today's Attendance
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div className="flex items-center justify-center">
                <div className="relative w-40 h-40">
                  <ResponsiveContainer width="100%" height="100%">
                    <PieChart>
                      <Pie
                        data={attendanceData}
                        cx="50%"
                        cy="50%"
                        innerRadius={45}
                        outerRadius={65}
                        paddingAngle={2}
                        dataKey="value"
                      >
                        {attendanceData.map((entry, index) => (
                          <Cell key={`cell-${index}`} fill={entry.color} strokeWidth={0} />
                        ))}
                      </Pie>
                    </PieChart>
                  </ResponsiveContainer>
                  <div className="absolute inset-0 flex items-center justify-center">
                    <div className="text-center">
                      <p className="text-2xl font-bold text-foreground">32</p>
                      <p className="text-xs text-muted-foreground">Total</p>
                    </div>
                  </div>
                </div>
              </div>
              <div className="flex justify-center gap-4 mt-4">
                {attendanceData.map((item, index) => (
                  <div key={index} className="flex items-center gap-2">
                    <div className="w-3 h-3 rounded-full" style={{ backgroundColor: item.color }} />
                    <span className="text-sm text-muted-foreground">{item.name}: {item.value}</span>
                  </div>
                ))}
              </div>
            </CardContent>
          </Card>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        {/* Recent Submissions */}
        <Card className="animate-fade-in" style={{ animationDelay: "0.2s" }}>
          <CardHeader className="flex flex-row items-center justify-between">
            <CardTitle className="flex items-center gap-2">
              <ClipboardCheck className="w-5 h-5 text-accent" />
              Recent Submissions
            </CardTitle>
            <Button variant="outline" size="sm">View All</Button>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {recentSubmissions.map((submission, index) => (
                <div key={index} className="flex items-center justify-between p-3 bg-muted rounded-lg">
                  <div className="flex items-center gap-3">
                    <Avatar className="w-10 h-10">
                      <AvatarFallback className="bg-primary/10 text-primary text-sm">
                        {submission.student.split(' ').map(n => n[0]).join('')}
                      </AvatarFallback>
                    </Avatar>
                    <div>
                      <p className="font-medium text-foreground">{submission.student}</p>
                      <p className="text-sm text-muted-foreground">{submission.assignment}</p>
                    </div>
                  </div>
                  <div className="text-right">
                    <span className={`inline-flex items-center gap-1 text-xs font-medium px-2 py-1 rounded ${
                      submission.status === "graded"
                        ? "bg-green-100 text-green-700"
                        : "bg-amber-100 text-amber-700"
                    }`}>
                      {submission.status === "graded" ? (
                        <CheckCircle className="w-3 h-3" />
                      ) : (
                        <AlertCircle className="w-3 h-3" />
                      )}
                      {submission.status === "graded" ? "Graded" : "Pending"}
                    </span>
                    <p className="text-xs text-muted-foreground mt-1">{submission.time}</p>
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>

        {/* Upcoming Classes */}
        <Card className="animate-fade-in" style={{ animationDelay: "0.3s" }}>
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <Clock className="w-5 h-5 text-accent" />
              Today's Schedule
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {upcomingClasses.map((item, index) => (
                <div key={index} className="flex items-center gap-4 p-3 bg-muted rounded-lg">
                  <div className="text-center min-w-[60px]">
                    <p className="text-sm font-semibold text-accent">{item.time}</p>
                  </div>
                  <div className="flex-1">
                    <h4 className="font-medium text-foreground">{item.class}</h4>
                    <p className="text-xs text-muted-foreground">{item.room} • {item.students} students</p>
                  </div>
                  <Button variant="outline" size="sm">Start</Button>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>
      </div>
    </DashboardLayout>
  );
}
