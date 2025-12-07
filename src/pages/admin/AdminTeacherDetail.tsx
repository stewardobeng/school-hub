import { useParams, useNavigate } from "react-router-dom";
import { DashboardLayout } from "@/components/layout/DashboardLayout";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Separator } from "@/components/ui/separator";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import {
  ArrowLeft,
  Mail,
  Phone,
  MapPin,
  Calendar,
  GraduationCap,
  BookOpen,
  Award,
  Users,
  Clock,
  User,
  Edit,
} from "lucide-react";

// Mock teacher data - in a real app, this would come from an API
const getTeacherData = (id: string) => {
  const teachers: Record<string, any> = {
    TC001: {
      id: "TC001",
      name: "Dr. Robert Anderson",
      email: "robert.a@school.edu",
      phone: "+1 234-567-9000",
      address: "456 Faculty Lane, New York, NY 10002",
      dateOfBirth: "1980-03-20",
      subject: "Mathematics",
      joinDate: "2020-01-15",
      status: "Active",
      avatar: "",
      education: "Ph.D. in Mathematics, Harvard University",
      experience: "15 years",
      classes: [
        { code: "MATH-101", name: "Mathematics - Algebra", grade: "Grade 10", students: 30, schedule: "Mon, Wed, Fri - 9:00 AM", room: "Room 201" },
        { code: "MATH-201", name: "Advanced Mathematics", grade: "Grade 11", students: 25, schedule: "Tue, Thu - 10:30 AM", room: "Room 203" },
        { code: "MATH-301", name: "Calculus", grade: "Grade 12", students: 20, schedule: "Mon, Wed - 2:00 PM", room: "Room 205" },
      ],
      schedule: [
        { day: "Monday", time: "9:00 AM - 10:30 AM", class: "MATH-101", room: "Room 201" },
        { day: "Monday", time: "2:00 PM - 3:30 PM", class: "MATH-301", room: "Room 205" },
        { day: "Tuesday", time: "10:30 AM - 12:00 PM", class: "MATH-201", room: "Room 203" },
        { day: "Wednesday", time: "9:00 AM - 10:30 AM", class: "MATH-101", room: "Room 201" },
        { day: "Wednesday", time: "2:00 PM - 3:30 PM", class: "MATH-301", room: "Room 205" },
        { day: "Thursday", time: "10:30 AM - 12:00 PM", class: "MATH-201", room: "Room 203" },
        { day: "Friday", time: "9:00 AM - 10:30 AM", class: "MATH-101", room: "Room 201" },
      ],
      students: [
        { id: "ST001", name: "John Smith", grade: "A", attendance: 95 },
        { id: "ST002", name: "Emily Johnson", grade: "A-", attendance: 98 },
        { id: "ST003", name: "Michael Brown", grade: "B+", attendance: 92 },
      ],
    },
  };
  return teachers[id] || teachers["TC001"];
};

export default function AdminTeacherDetail() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const teacher = getTeacherData(id || "TC001");

  const totalStudents = teacher.classes.reduce((sum: number, cls: any) => sum + cls.students, 0);
  const averageAttendance = teacher.students.reduce((sum: number, s: any) => sum + s.attendance, 0) / teacher.students.length;

  return (
    <DashboardLayout title="Teacher Details" userRole="admin">
      <div className="space-y-6">
        {/* Back Button */}
        <Button variant="outline" onClick={() => navigate("/admin/teachers")}>
          <ArrowLeft className="w-4 h-4 mr-2" />
          Back to Teachers
        </Button>

        {/* Teacher Profile Header */}
        <Card>
          <CardContent className="p-6">
            <div className="flex items-start gap-6">
              <Avatar className="w-32 h-32 border-4 border-primary">
                <AvatarImage src={teacher.avatar} alt={teacher.name} />
                <AvatarFallback className="text-4xl bg-primary text-primary-foreground">
                  {teacher.name.split(' ').map(n => n[0]).join('')}
                </AvatarFallback>
              </Avatar>
              <div className="flex-1">
                <div className="flex items-center justify-between mb-4">
                  <div>
                    <h1 className="text-3xl font-bold mb-2">{teacher.name}</h1>
                    <div className="flex items-center gap-4 text-muted-foreground">
                      <span className="font-mono text-sm">ID: {teacher.id}</span>
                      <Badge
                        variant={teacher.status === "Active" ? "default" : "secondary"}
                        className={teacher.status === "Active" ? "bg-green-500" : ""}
                      >
                        {teacher.status}
                      </Badge>
                    </div>
                  </div>
                  <Button>
                    <Edit className="w-4 h-4 mr-2" />
                    Edit Teacher
                  </Button>
                </div>
                <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                  <div className="flex items-center gap-2">
                    <BookOpen className="w-4 h-4 text-muted-foreground" />
                    <span className="text-sm">{teacher.subject}</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <Mail className="w-4 h-4 text-muted-foreground" />
                    <span className="text-sm">{teacher.email}</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <Phone className="w-4 h-4 text-muted-foreground" />
                    <span className="text-sm">{teacher.phone}</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <Calendar className="w-4 h-4 text-muted-foreground" />
                    <span className="text-sm">Joined: {teacher.joinDate}</span>
                  </div>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        {/* Stats Cards */}
        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
          <Card>
            <CardContent className="p-4">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-muted-foreground">Total Classes</p>
                  <p className="text-2xl font-bold">{teacher.classes.length}</p>
                </div>
                <BookOpen className="w-8 h-8 text-blue-600" />
              </div>
            </CardContent>
          </Card>
          <Card>
            <CardContent className="p-4">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-muted-foreground">Total Students</p>
                  <p className="text-2xl font-bold">{totalStudents}</p>
                </div>
                <Users className="w-8 h-8 text-green-600" />
              </div>
            </CardContent>
          </Card>
          <Card>
            <CardContent className="p-4">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-muted-foreground">Avg. Attendance</p>
                  <p className="text-2xl font-bold">{averageAttendance.toFixed(1)}%</p>
                </div>
                <Award className="w-8 h-8 text-purple-600" />
              </div>
            </CardContent>
          </Card>
          <Card>
            <CardContent className="p-4">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-muted-foreground">Experience</p>
                  <p className="text-2xl font-bold">{teacher.experience}</p>
                </div>
                <GraduationCap className="w-8 h-8 text-amber-600" />
              </div>
            </CardContent>
          </Card>
        </div>

        {/* Detailed Information Tabs */}
        <Tabs defaultValue="overview" className="space-y-6">
          <TabsList>
            <TabsTrigger value="overview">Overview</TabsTrigger>
            <TabsTrigger value="classes">Classes</TabsTrigger>
            <TabsTrigger value="schedule">Schedule</TabsTrigger>
            <TabsTrigger value="students">Students</TabsTrigger>
          </TabsList>

          {/* Overview Tab */}
          <TabsContent value="overview" className="space-y-6">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              {/* Personal Information */}
              <Card>
                <CardHeader>
                  <CardTitle className="flex items-center gap-2">
                    <User className="w-5 h-5" />
                    Personal Information
                  </CardTitle>
                </CardHeader>
                <CardContent className="space-y-4">
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <p className="text-sm text-muted-foreground">Date of Birth</p>
                      <p className="font-medium">{teacher.dateOfBirth}</p>
                    </div>
                    <div>
                      <p className="text-sm text-muted-foreground">Age</p>
                      <p className="font-medium">
                        {new Date().getFullYear() - new Date(teacher.dateOfBirth).getFullYear()} years
                      </p>
                    </div>
                  </div>
                  <Separator />
                  <div>
                    <p className="text-sm text-muted-foreground mb-2">Address</p>
                    <div className="flex items-start gap-2">
                      <MapPin className="w-4 h-4 text-muted-foreground mt-0.5" />
                      <p className="font-medium">{teacher.address}</p>
                    </div>
                  </div>
                  <Separator />
                  <div>
                    <p className="text-sm text-muted-foreground">Education</p>
                    <p className="font-medium">{teacher.education}</p>
                  </div>
                  <Separator />
                  <div>
                    <p className="text-sm text-muted-foreground">Experience</p>
                    <p className="font-medium">{teacher.experience}</p>
                  </div>
                </CardContent>
              </Card>

              {/* Professional Information */}
              <Card>
                <CardHeader>
                  <CardTitle className="flex items-center gap-2">
                    <GraduationCap className="w-5 h-5" />
                    Professional Information
                  </CardTitle>
                </CardHeader>
                <CardContent className="space-y-4">
                  <div>
                    <p className="text-sm text-muted-foreground">Subject</p>
                    <Badge variant="outline" className="mt-1">
                      {teacher.subject}
                    </Badge>
                  </div>
                  <Separator />
                  <div>
                    <p className="text-sm text-muted-foreground">Join Date</p>
                    <p className="font-medium">{teacher.joinDate}</p>
                  </div>
                  <Separator />
                  <div>
                    <p className="text-sm text-muted-foreground">Status</p>
                    <Badge
                      variant={teacher.status === "Active" ? "default" : "secondary"}
                      className={teacher.status === "Active" ? "bg-green-500 mt-1" : "mt-1"}
                    >
                      {teacher.status}
                    </Badge>
                  </div>
                  <Separator />
                  <div>
                    <p className="text-sm text-muted-foreground mb-2">Contact</p>
                    <div className="space-y-2">
                      <div className="flex items-center gap-2">
                        <Mail className="w-4 h-4 text-muted-foreground" />
                        <p className="font-medium">{teacher.email}</p>
                      </div>
                      <div className="flex items-center gap-2">
                        <Phone className="w-4 h-4 text-muted-foreground" />
                        <p className="font-medium">{teacher.phone}</p>
                      </div>
                    </div>
                  </div>
                </CardContent>
              </Card>
            </div>
          </TabsContent>

          {/* Classes Tab */}
          <TabsContent value="classes">
            <Card>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <BookOpen className="w-5 h-5" />
                  Assigned Classes
                </CardTitle>
                <CardDescription>All classes currently taught by this teacher</CardDescription>
              </CardHeader>
              <CardContent>
                <div className="rounded-md border">
                  <Table>
                    <TableHeader>
                      <TableRow>
                        <TableHead>Course Code</TableHead>
                        <TableHead>Course Name</TableHead>
                        <TableHead>Grade</TableHead>
                        <TableHead>Students</TableHead>
                        <TableHead>Schedule</TableHead>
                        <TableHead>Room</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {teacher.classes.map((classItem: any, index: number) => (
                        <TableRow key={index}>
                          <TableCell className="font-mono">
                            <Badge variant="outline">{classItem.code}</Badge>
                          </TableCell>
                          <TableCell className="font-medium">{classItem.name}</TableCell>
                          <TableCell>{classItem.grade}</TableCell>
                          <TableCell>
                            <Badge variant="secondary">{classItem.students} Students</Badge>
                          </TableCell>
                          <TableCell>
                            <div className="flex items-center gap-2 text-sm">
                              <Clock className="w-4 h-4 text-muted-foreground" />
                              {classItem.schedule}
                            </div>
                          </TableCell>
                          <TableCell>{classItem.room}</TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </div>
              </CardContent>
            </Card>
          </TabsContent>

          {/* Schedule Tab */}
          <TabsContent value="schedule">
            <Card>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <Calendar className="w-5 h-5" />
                  Weekly Schedule
                </CardTitle>
                <CardDescription>Complete weekly teaching schedule</CardDescription>
              </CardHeader>
              <CardContent>
                <div className="rounded-md border">
                  <Table>
                    <TableHeader>
                      <TableRow>
                        <TableHead>Day</TableHead>
                        <TableHead>Time</TableHead>
                        <TableHead>Class</TableHead>
                        <TableHead>Room</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {teacher.schedule.map((schedule: any, index: number) => (
                        <TableRow key={index}>
                          <TableCell className="font-medium">{schedule.day}</TableCell>
                          <TableCell>
                            <div className="flex items-center gap-2">
                              <Clock className="w-4 h-4 text-muted-foreground" />
                              {schedule.time}
                            </div>
                          </TableCell>
                          <TableCell>
                            <Badge variant="outline" className="font-mono">
                              {schedule.class}
                            </Badge>
                          </TableCell>
                          <TableCell>{schedule.room}</TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </div>
              </CardContent>
            </Card>
          </TabsContent>

          {/* Students Tab */}
          <TabsContent value="students">
            <Card>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <Users className="w-5 h-5" />
                  Students
                </CardTitle>
                <CardDescription>Students taught by this teacher</CardDescription>
              </CardHeader>
              <CardContent>
                <div className="rounded-md border">
                  <Table>
                    <TableHeader>
                      <TableRow>
                        <TableHead>Student ID</TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Grade</TableHead>
                        <TableHead>Attendance</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {teacher.students.map((student: any) => (
                        <TableRow key={student.id}>
                          <TableCell className="font-mono text-sm">{student.id}</TableCell>
                          <TableCell className="font-medium">{student.name}</TableCell>
                          <TableCell>
                            <Badge variant="secondary" className="font-semibold">
                              {student.grade}
                            </Badge>
                          </TableCell>
                          <TableCell>
                            <Badge
                              variant={student.attendance >= 95 ? "default" : "secondary"}
                              className={student.attendance >= 95 ? "bg-green-500" : ""}
                            >
                              {student.attendance}%
                            </Badge>
                          </TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </div>
              </CardContent>
            </Card>
          </TabsContent>
        </Tabs>
      </div>
    </DashboardLayout>
  );
}

